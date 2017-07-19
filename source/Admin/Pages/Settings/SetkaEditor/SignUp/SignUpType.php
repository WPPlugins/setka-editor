<?php
namespace Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp;

use Setka\Editor\Admin\Utils\FormConfig;
use Setka\Editor\Plugin;
use Setka\Editor\Service\Constraints\WordPressNonceConstraint;
use Setka\Editor\Service\Countries\HubSpotCountriesList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class SignUpType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('accountType', Type\ChoiceType::class, array(
			'expanded' => true,
			'multiple' => false,
			'required' => true,
			'choices' => array(
				__('Individual', Plugin::NAME) => 'person',
				__('Company', Plugin::NAME) => 'company',
				__('I already have the license key', Plugin::NAME) => 'sign-in'
			),
			'validation_groups' => array('person', 'company', 'sign-in'),
			'choice_attr' => array(
				__('Individual', Plugin::NAME) => array(
					'data-form-element-role' => 'conditional-switcher',
					'data-wrapper' => 'wrapper',
				),
				__('Company', Plugin::NAME) => array(
					'data-form-element-role' => 'conditional-switcher',
					'data-wrapper' => 'wrapper',
				),
				__('I already have the license key', Plugin::NAME) => array(
					'data-form-element-role' => 'conditional-switcher',
				),
			),
			'constraints' => array(
				new Constraints\NotBlank(array('groups' => array('person', 'company', 'sign-in'))),
			)
		));

		$builder->add('email', Type\EmailType::class, array(
			'label' => __('Email', Plugin::NAME),
			'required' => false,
			'constraints' => array(
				new Constraints\NotBlank(array(
					'groups' => array('person', 'company'),
				)),
				new Constraints\Email(array(
					'groups' => array('person', 'company'),
				)),
			),
			'validation_groups' => array('person', 'company'),
			'attr' => array(
				'data-form-element-role' => 'conditional-listener',
				'class' => 'regular-text',
			),
		));

		$builder->add('firstName', Type\TextType::class, array(
			'label' => __('First Name', Plugin::NAME),
			'required' => false,
			'constraints' => array(
				new Constraints\NotBlank(array(
					'groups' => array('person', 'company'),
				)),
				new Constraints\Length(array(
					'min' => 2,
					'groups' => array('person', 'company'),
				)),
			),
			'validation_groups' => array('person', 'company'),
			'attr' => array(
				'data-form-element-role' => 'conditional-listener',
			),
		));

		$builder->add('lastName', Type\TextType::class, array(
			'label' => __('Last Name', Plugin::NAME),
			'required' => false,
			'constraints' => array(
				new Constraints\NotBlank(array(
					'groups' => array('person', 'company'),
				)),
				new Constraints\Length(array(
					'min' => 2,
					'groups' => array('person', 'company'),
				)),
			),
			'validation_groups' => array('person', 'company'),
			'attr' => array(
				'data-form-element-role' => 'conditional-listener',
			),
		));

		$builder->add('region', Type\ChoiceType::class, array(
			'label' => __('Country', Plugin::NAME),
			'required' => true,
			'expanded' => false,
			'multiple' => false,
			'choice_loader' => new CallbackChoiceLoader(function() {
				return HubSpotCountriesList::getCountriesAndCodes();
			}),
			'validation_groups' => array('person', 'company'),
			'attr' => array(
				'data-form-element-role' => 'conditional-listener',
				'class' => 'regular-text',
			),
			'constraints' => array(
				new Constraints\NotBlank(array(
					'groups' => array('person', 'company')
				)),
			)
		));

		$builder->add('companyDomain', Type\UrlType::class, array(
			'label' => __('Site url', Plugin::NAME),
			'required' => false,
			'constraints' => array(
				new Constraints\NotBlank(array(
					'groups' => array('person', 'company'),
				)),
				new Constraints\Url(array(
					'groups' => array('person', 'company'),
				)),
			),
			'validation_groups' => array('person', 'company'),
			'attr' => array(
				'data-form-element-role' => 'conditional-listener',
				'class' => 'regular-text',
			),
		));

		$builder->add('companyName', Type\TextType::class, array(
			'label' => __('Company Name', Plugin::NAME),
			'required' => false,
			'constraints' => array(
				new Constraints\NotBlank(array(
					'groups' => array('company'),
				)),
				new Constraints\Length(array(
					'min'=> 2,
					'groups' => array('company'),
				)),
			),
			'validation_groups' => array('company'),
			'attr' => array(
				'data-form-element-role' => 'conditional-listener',
			),
		));

		$builder->add('companySize', Type\ChoiceType::class, array(
			'label' => __('Company size', Plugin::NAME),
			'placeholder' => __('Choose company size', Plugin::NAME),
			'required' => false, // but actually true :)
			'expanded' => false,
			'multiple' => false,
			'choices' => array(
				new NumberOfEmployees\Ranges\EmployeesRange1_5(),
				new NumberOfEmployees\Ranges\EmployeesRange6_20(),
				new NumberOfEmployees\Ranges\EmployeesRange21_50(),
				new NumberOfEmployees\Ranges\EmployeesRange51_150(),
				new NumberOfEmployees\Ranges\EmployeesRange151_300(),
				new NumberOfEmployees\Ranges\EmployeesRange301_1000(),
				new NumberOfEmployees\Ranges\EmployeesRange1001_5000(),
				new NumberOfEmployees\Ranges\EmployeesRange5000Plus(),
			),
			'choice_label' => 'getTitle',
			'validation_groups' => array('company'),
			'attr' => array(
				'data-form-element-role' => 'conditional-listener',
				'class' => 'regular-text',
			),
			'constraints' => array(
				new Constraints\NotBlank(array('groups' => 'company')),
			)
		));

		$builder->add('companyDepartment', Type\ChoiceType::class, array(
			'label' => __('How would you describe yourself?', Plugin::NAME),
			'placeholder' => __('Choose your profession', Plugin::NAME),
			'required' => false, // but actually true :)
			'expanded' => false,
			'multiple' => false,
			'choices' => array(
				new Positions\Variations\Developer(),
				new Positions\Variations\Editor(),
				new Positions\Variations\Blogger(),
				new Positions\Variations\MarketingProfessional(),
				new Positions\Variations\Designer(),
				new Positions\Variations\BusinessDeveloper(),
				new Positions\Variations\Other(),
			),
			'choice_label' => 'getTitle',
			'validation_groups' => array('person', 'company'),
			'attr' => array(
				'data-form-element-role' => 'conditional-listener',
				'class' => 'regular-text',
			),
			'constraints' => array(
				new Constraints\NotBlank(array(
					'groups' => array('person', 'company')
				)),
			)
		));

		$builder->add('password', Type\PasswordType::class, array(
			'label' => __('Password', Plugin::NAME),
			'required' => false,
			'constraints' => array(
				new Constraints\NotBlank(array(
					'groups' => array('person', 'company'),
				)),
				new Constraints\Length(array(
					'min'=> 6,
					'groups' => array('person', 'company'),
				)),
			),
			'validation_groups' => array('person', 'company'),
			'attr' => array(
				'data-form-element-role' => 'conditional-listener',
				// Prevent auto filling in Chrome
				// https://developers.google.com/web/fundamentals/design-and-ui/input/forms/?hl=en
				'autocomplete' => 'new-password'
			),
		));

		$builder->add('token', Type\TextType::class, array(
			'label' => __('License key', Plugin::NAME),
			'required' => false,
			'constraints' => array(
				new Constraints\NotBlank(array(
					'groups' => array('sign-in'),
				)),
			),
			'validation_groups' => array('sign-in'),
			'attr' => array(
				'data-form-element-role' => 'conditional-listener',
				'class' => 'regular-text',
			),
		));

		$builder->add('termsAndConditions', Type\CheckboxType::class, array(
			'label' => __('I agree with Terms and Conditions and Privacy Policy.'),
			'required' => false,
			'constraints' => array(
				new Constraints\Type(array(
					'type' => 'bool',
					'groups' => array('person', 'company'),
				)),
				new Constraints\IdenticalTo(array(
					'value' => true,
					'groups' => array('person', 'company'),
					'message' => __('You need to agree with Terms and Conditions and Privacy Policy.', Plugin::NAME),
				))
			),
			'validation_groups' => array('person', 'company'),
			'attr' => array(
				'data-form-element-role' => 'conditional-listener',
			),
		));

		$builder->add('whiteLabel', Type\CheckboxType::class, array(
			'label' => __('Show “Created with Setka Editor” credits bellow the content', Plugin::NAME),
			'required' => false,
			'validation_groups' => array('person', 'company'),
			'attr' => array(
				'data-form-element-role' => 'conditional-listener',
			),
		));

		$builder->add('nonce', Type\HiddenType::class, array(
			'data' => wp_create_nonce(Plugin::NAME .'-sign-up'),
			'constraints' => array(
				new Constraints\NotBlank(array(
					'groups' => array('person', 'company'),
				)),
				new WordPressNonceConstraint(array(
					'name' => Plugin::NAME .'-sign-up',
					'groups' => array('person', 'company'),
				))
			),
		));

		$builder->add('submit', Type\SubmitType::class, array(
			'label' => __('Sign Up Individual', Plugin::NAME),
			'attr' => array(
				'class' => 'button button-primary',
				'data-form-element-role' => 'conditional-listener',
			),
			'validation_groups' => 'person',
		));

		$builder->add('submitCompany', Type\SubmitType::class, array(
			'label' => __('Sign Up Company', Plugin::NAME),
			'attr' => array(
				'class' => 'button button-primary',
				'data-form-element-role' => 'conditional-listener',
			),
			'validation_groups' => 'company'
		));

		$builder->add('submitToken', Type\SubmitType::class, array(
			'label' => __('Start working with Setka Editor', Plugin::NAME),
			'attr' => array(
				'class' => 'button button-primary',
				'data-form-element-role' => 'conditional-listener',
			),
			'validation_groups' => 'sign-in'
		));

		$builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
			$data = $event->getData();
			// Replace config since WordPress doing escaping
			// for all serialized inputs during POST requests
			$data['config'] = $event->getForm()->get('config')->getViewData();
			$event->setData($data);
		});

		$builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
			$event->getForm()->add('config', Type\HiddenType::class, array(
				'data'=> json_encode(FormConfig::getConfig($event->getForm()))
			));
		});
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'attr' => array(
				'data-form-type' => 'conditional-form'
			),
		));
	}
}
