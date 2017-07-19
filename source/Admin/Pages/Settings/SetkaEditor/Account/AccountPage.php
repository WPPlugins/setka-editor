<?php
namespace Setka\Editor\Admin\Pages\Settings\SetkaEditor\Account;

use Setka\Editor\Admin\Pages\Settings\Tabs\AccessTab;
use Setka\Editor\Admin\Pages\Settings\Tabs\AccountTab;
use Setka\Editor\Admin\Prototypes\Pages;
use Setka\Editor\Admin\Prototypes\Pages\Tabs\Tabs;
use Setka\Editor\Plugin;
use Setka\Editor\Admin\Pages\Settings\Loader;
use Setka\Editor\Service\SetkaAccount\Account;
use Setka\Editor\Admin\Options;
use Setka\Editor\Service\SetkaAccount\SignOut;
use Symfony\Component\HttpFoundation\Request;

class AccountPage extends Pages\MenuPage {

	use Pages\Traits\FormOptionSaverTrait;

	public function __construct() {
		$this->setPageTitle(__('Account', Plugin::NAME));
		$this->setMenuTitle($this->getPageTitle());
		$this->setCapability('manage_options');
		$this->setMenuSlug(Plugin::NAME);

		$this->setName('account');
	}

	public function lateConstruct() {
		$this->setTabs($this->buildTabs());
		$this->prepareTabs();

		$this->setFormEntity(new SignIn());
		$this->lateConstructEntity();
		$this->setForm(Loader::$symfony_form_factory->createNamed(Plugin::_NAME_, SignInType::class, $this->getFormEntity()));

		$this->handleRequest();

		$attributes = array(
			'page' => $this,
			'form' => $this->getForm()->createView(),
			'translations' => array(
				'already_signed_in' => __('You have already started the plugin.', Plugin::NAME),
			),
			'signedIn' => Account::is_logged_in(),
		);

		$view = new Pages\Views\TwigPageView();
		$view->setTemplate('admin/settings/setka-editor/account/page.html.twig');
		$view->setContext($attributes);
		$this->setView($view);
	}

	public function handleRequest() {
		$form = $this->getForm()->handleRequest(Request::createFromGlobals());

		if($form->isSubmitted()) {
			if($form->isValid()) {
				SignOut::sign_out();
				$url = $this->getURL();
				$url = add_query_arg('account-type', 'sign-in', $url);
				wp_redirect($url);
			}
		}
	}

	public function buildTabs() {
		$a = new Tabs();

		$tab = new AccountTab();
		$a->addTab($tab);

		$tab = new AccessTab();
		$a->addTab($tab);

		return $a;
	}

	protected function lateConstructEntity() {
		/**
		 * @var $a SignIn
		 */
		$a = $this->getFormEntity();

		$token = new Options\Token\Option();
		$a->setToken($token->getValue());
	}
}
