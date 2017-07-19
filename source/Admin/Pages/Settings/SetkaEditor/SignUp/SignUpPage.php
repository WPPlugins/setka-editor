<?php
namespace Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp;

use Setka\Editor\Admin\Notices\SignUpError\SignUpErrorNotice;
use Setka\Editor\Admin\Notices\SuccessfulSignUp\SuccessfulSignUpNotice;
use Setka\Editor\Admin\Pages\Settings\Tabs\AccessTab;
use Setka\Editor\Admin\Pages\Settings\Tabs\StartTab;
use Setka\Editor\Admin\Prototypes\Notices\ErrorNotice;
use Setka\Editor\Admin\Prototypes\Pages;
use Setka\Editor\Admin\Prototypes\Pages\Tabs\Tabs;
use Setka\Editor\Admin\Service\SetkaAPI;
use Setka\Editor\Plugin;
use Setka\Editor\Admin\Options;
use Setka\Editor\Admin\Pages\Settings\Loader;
use Setka\Editor\Admin\Transients;
use Setka\Editor\Prototypes\Errors\ErrorInterface;
use Setka\Editor\Service\Countries\Countries;
use Setka\Editor\Service\SetkaAccount\Account;
use Setka\Editor\Service\SetkaAccount\SignIn;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class SignUpPage extends Pages\MenuPage {

	use Pages\Traits\FormOptionSaverTrait;

	protected $processState = '';

	public function __construct() {
		$this->setPageTitle(__('Start Setka Editor for Free', Plugin::NAME));
		$this->setMenuTitle(_x('Start', 'Menu title', Plugin::NAME));
		$this->setCapability('manage_options');
		// Add menu slug to make work $this->getURL()
		// (we also can set menu slug in lateConstruct() based on value from parent)
		$this->setMenuSlug(Plugin::NAME);

		$this->setName('sign-up');
	}

	/**
	 * @inheritdoc
	 */
	public function lateConstruct() {
		$this->setTabs($this->buildTabs());
		$this->prepareTabs();

		$this->setRequest(Request::createFromGlobals());

		// Registration form
		$this->setFormEntity(new SignUp());
		$this->lateConstructEntity();

		$formBuilder = Loader::$symfony_form_factory->createNamedBuilder(Plugin::_NAME_, SignUpType::class, $this->getFormEntity());
		$form = $formBuilder
			->setAction($this->getURL())
			->getForm();
		$this->setForm($form);

		$this->handleRequest();

		if($this->processState == 'sign-up-success') {
			/**
			 * @var $data SignUp
			 */
			$data = $this->getFormEntity();
			$data->setAccountType('sign-in');

			$formBuilder = Loader::$symfony_form_factory->createNamedBuilder(Plugin::_NAME_, SignUpType::class, $this->getFormEntity());
			$formBuilder->setAction($this->getURL());
			$form = $formBuilder->getForm();
			$this->setForm($form);
		}

		$attributes = array(
			'page' => $this,
			'form' => $form->createView(),
			'translations' => array(
				'start' => __('Sign up to start using Setka Editor Free plan.', Plugin::NAME),
				'email_sub_label' => __('We will send a license key to this address', Plugin::NAME),
				'password_sub_label' => __('To have access to Style Manager', Plugin::NAME),
				'terms_and_conditions' => '<a href="https://editor.setka.io/terms/Terms-and-Conditions-Setka-Editor.pdf" target="_blank">Terms and Conditions</a>',
				'privacy_policy' => '<a href="https://editor.setka.io/terms/Privacy-Policy-Setka-Editor.pdf" target="_blank">Privacy Policy</a>',
				'already_signed_in' => __('You have already started the plugin.', Plugin::NAME),
			),
			'signedIn' => Account::is_logged_in(),
		);
		$view = new Pages\Views\TwigPageView();
		$view->setTemplate('admin/settings/setka-editor/page.html.twig');
		$view->setContext($attributes);
		$this->setView($view);
	}

	/**
	 * @inheritdoc
	 */
	public function handleRequest() {
		$form = $this->getForm()->handleRequest($this->getRequest());

		// Form was submitted, lets validate the form
		if($form->isSubmitted()) {
			if($form->isValid()) {
				$data = $form->getData();
				/**
				 * @var $data SignUp
				 */
				if($data->getAccountType() == 'sign-in') {
					// Sign In
					$this->handleRequestSignIn();
				} else {
					// Sign Up
					$this->handleRequestSignUp();
				}
			} else {
				// Show errors on the page
				// Actually Symfony show the errors automatically near each field
			} // end if valid
		} // end if submitted
	}

	public function handleRequestSignIn() {
		/**
		 * @var $data SignUp
		 */
		$form = $this->getForm();
		$data = $form->getData();

		// First of all validate user submitted token
		// in Local environment (token length).
		$option = new Options\Token\Option();
		$errors = $option->getValidator()->validate($data->getToken(), $option->getConstraint());

		// If token not valid just show errors.
		if(count($errors) !== 0) {
			$field = $form->get('token');
			foreach($errors as $error) {
				$field->addError(new FormError($error->getMessage()));
			}
			return;
		}
		unset($option, $errors);

		// Doing Signing In
		$results = SignIn::sign_in_by_token($data->getToken());

		$haveErrors = false;
		foreach($results as $action) {
			if($action->getErrors()->hasErrors()) {
				$haveErrors = true;
				$field = $form->get('token');
				// TODO: we adding errors twice (if similar errors exists in each action)
				foreach($action->getErrors() as $error) {
					/**
					 * @var $error \Setka\Editor\Prototypes\Errors\Error
					 */
					$field->addError(new FormError($error->getMessage()));
				}
			}
		}

		// Successful signing in
		if(!$haveErrors) {
			//\Setka\Editor\Admin\Notices\Loader::$notices[] = new \Setka\Editor\Admin\Notices\AfterSignIn\Notice();
			// Success notification flag which shows what Setka Editor perfectly setted up!
			$transient = new Transients\AfterSignInNotice\Transient();
			$transient->updateValue(1);

			wp_redirect($this->getURL());
		}
	}

	public function handleRequestSignUp() {
		$form = $this->getForm();
		// Send request to Setka API
		$api = new SetkaAPI\API();
		$action = new SetkaAPI\Actions\SignUpAction();

		$fieldsMap = array(
			// API => Form
			// person and company
			'company_type' => 'accountType',

			// person and company
			'email' => 'email',
			'first_name' => 'firstName',
			'last_name' => 'lastName',
			'region' => 'region',
			'password' => 'password',
			'company_domain' => 'companyDomain',

			// company
			'company_name' => 'companyName',
			'company_size' => 'companySize',
			'company_department' => 'companyDepartment',
		);

		// Prepare data
		$a = $form->getData();
		/**
		 * @var $a SignUp
		 */
		$requestDetails = array(
			'body' => array(
				'signup' => array(
					'company_type'   => $a->getAccountType(),
					'email'          => $a->getEmail(),
					'first_name'     => $a->getFirstName(),
					'last_name'      => $a->getLastName(),
					'region'         => $a->getRegion(),
					'password'       => $a->getPassword(),
					'company_domain' => $a->getCompanyDomain(),
				),
			),
		);
		if($a->getAccountType() == 'company') {
			$requestDetails['body']['signup']['company_name'] = $a->getCompanyName();
			$requestDetails['body']['signup']['company_size'] = $a->getCompanySize()->getValue();
			$requestDetails['body']['signup']['company_department'] = $a->getCompanyDepartment()->getValue();
		}
		$action->setRequestDetails($requestDetails);
		unset($requestDetails);
		$action->configureAndResolveRequestDetails();

		// External request
		$api->request($action);

		// Response handling
		if($action->getErrors()->hasErrors()) {
			//$form->addError(new FormError());
			\Setka\Editor\Admin\Notices\Loader::$notices[] = new SignUpErrorNotice();
			foreach($action->getErrors() as $error) {
				/**
				 * @var $error ErrorInterface
				 */
				$notice = new ErrorNotice(Plugin::NAME, $error->getCode());
				$notice->setContent('<p>' . $error->getMessageHTML() .'</p>');
				\Setka\Editor\Admin\Notices\Loader::$notices[] = $notice;
			}
			unset($error, $notice);
		} else {
			// Lets see the response
			$response = $action->getResponse();
			switch($response->getStatusCode()) {
				case $response::HTTP_CREATED:
					// Successful registration, redirect to success page.
					// Also save registration info for resending sign up emails

					$whiteLabelOption = new Options\WhiteLabel\WhiteLabelOption();
					$whiteLabel = $a->isWhiteLabel();
					if($whiteLabel) {
						$whiteLabel = '1';
					} else {
						$whiteLabel = '0';
					}
					$whiteLabelOption->updateValue($whiteLabel);

					\Setka\Editor\Admin\Notices\Loader::$notices[] = new SuccessfulSignUpNotice();
					$this->processState = 'sign-up-success';
					break;

				case $response::HTTP_UNPROCESSABLE_ENTITY:
					if($response->getContent()->has('error')) {

						$error = $response->getContent()->has('error');
						$notice = new ErrorNotice(Plugin::NAME, 'setka_api_error');
						$notice->setContent('<p>' . esc_html($error) .'</p>');
						\Setka\Editor\Admin\Notices\Loader::$notices[] = $notice;
						unset($error, $notice);

					} elseif ($response->getContent()->has('errors')) {

						$errors = $response->getContent()->get('errors');

						foreach($errors as $errorKey => &$errorValue) {
							//$notice = new ErrorNotice(Plugin::NAME, 'setka_api_error' . esc_attr($error_key));
							//$notice->setContent('<p>' . esc_html($error_value) .'</p>');
							//\Setka\Editor\Admin\Notices\Loader::$notices[] = $notice;

							if(is_array($errorValue)) {
								foreach($errorValue as $errorCode => &$errorMessage) {

									if(isset($fieldsMap[$errorKey])) {
										$field = $form->get($fieldsMap[$errorKey]);
									} else {
										$field = $form;
									}

									if($errorKey == 'email' && $errorMessage == 'has already been taken') {
										// Definitely Setka API should return error codes, not only messages.
										$errorMessage = __('This email has already been taken to create Setka Editor account. Please reset password on editor.setka.io or enter another email.', Plugin::NAME);
									}

									// We can't add html markup to errors since form_errors block simply using
									// message attribute from FormError instance and escaping before output.
									$field->addError(new FormError($errorMessage));
								}
							}
						}
						unset($errors, $errorKey, $errorValue, $errorCode, $errorMessage, $notice);
					}
					break;
			}
		}
	}

	public function buildTabs() {
		$a = new Tabs();

		$tab = new StartTab();
		$a->addTab($tab);

		$tab = new AccessTab();
		$a->addTab($tab);

		return $a;
	}

	protected function lateConstructEntity() {
		/**
		 * @var $a SignUp
		 */
		$a = $this->getFormEntity();
		$user = wp_get_current_user();

		$a->setAccountType('person');

		if($this->getRequest()->query->has('account-type')) {
			$accountType = $this->getRequest()->query->get('account-type');
			if(in_array($accountType, array(
				'person', 'company', 'sign-in',
			))) {
				$a->setAccountType($accountType);
			}
		}

		$firstName = $user->get('first_name');
		if(is_string($firstName))
			$a->setFirstName($firstName);
		unset($firstName);

		$lastName = $user->get('last_name');
		if(is_string($lastName))
			$a->setLastName($lastName);
		unset($lastName);

		$a->setRegion(Countries::getCountryFromWPLocale(get_locale()));

		$a->setCompanyDomain(site_url());

		$a->setTermsAndConditions(false);

		$whiteLabel = new Options\WhiteLabel\WhiteLabelOption();
		$whiteLabel = $whiteLabel->getValue();
		if($whiteLabel == '1') {
			$whiteLabel = true;
		} else {
			$whiteLabel = false;
		}
		$a->setWhiteLabel($whiteLabel);

		if(Account::is_logged_in()) {
			$token = new Options\Token\Option();
			$a->setToken($token->getValue());
		}
	}
}
