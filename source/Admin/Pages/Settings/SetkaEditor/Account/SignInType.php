<?php
namespace Setka\Editor\Admin\Pages\Settings\SetkaEditor\Account;

use Setka\Editor\Plugin;
use Setka\Editor\Service\Constraints\WordPressNonceConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Constraints;

class SignInType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('token', Type\TextType::class, array(
				'label' => __('License key', Plugin::NAME),
				'required' => true,
				'disabled' => true,
				'constraints' => array(
					new Constraints\NotBlank(),
					new Constraints\Length(array('min'=> 32, 'max' => 32)),
				),
				'attr' => array(
					'class' => 'regular-text',
				),
			))
			->add('nonce', Type\HiddenType::class, array(
				'data' => wp_create_nonce(Plugin::NAME .'-sign-up'),
				'constraints' => array(
					new Constraints\NotBlank(),
					new WordPressNonceConstraint(array(
						'name' => Plugin::NAME .'-sign-up',
					))
				),
			))
			->add('submitToken', Type\SubmitType::class, array(
				'label' => _x('Change license key', 'Button label', Plugin::NAME),
				'attr' => array('class' => 'button button-secondary'),
			))
		;
	}
}
