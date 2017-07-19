<?php
namespace Setka\Editor\Admin\Pages\Settings\Settings;

use Setka\Editor\Admin\Options;
use Setka\Editor\Plugin;
use Setka\Editor\Service\Constraints\WordPressNonceConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Constraints;
use Setka\Editor\Admin\User\Capabilities;

class SettingsType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {

		// Post Types
		$postTypes = get_post_types();
		unset($postTypes['attachment']);
		unset($postTypes['revision']);
		unset($postTypes['nav_menu_item']);

		if(!empty($postTypes)) {
			foreach($postTypes as $key => $value) {
				$postTypeObject = get_post_type_object($value);
				$postTypes[$key] = $postTypeObject->labels->name;
			}
			$postTypes = array_flip($postTypes);

			$option = new Options\EditorAccessPostTypes\Option();

			$builder->add('post_types', Type\ChoiceType::class, array(
				'choices' => $postTypes,
				'multiple' => true,
				'expanded' => true,
				'data' => $option->getValue()
			));
		}
		unset($postTypes, $key, $value, $postTypeObject, $option);

		// Roles
		$roles = get_editable_roles();
		if(!empty($roles)) {
			$rolesVariants = array();
			$rolesSelected = array();
			foreach($roles as $key => $value) {
				$rolesVariants[$value['name']] = $key;

				if(
					isset($value['capabilities'][Capabilities\UseEditorCapability::NAME])
					&&
					$value['capabilities'][Capabilities\UseEditorCapability::NAME] === true
				) {
					$rolesSelected[] = $key;
				}
			}

			$builder->add('roles', Type\ChoiceType::class, array(
				'choices' => $rolesVariants,
				'multiple' => true,
				'expanded' => true,
				'data' => $rolesSelected
			));
		}
		unset($roles, $rolesVariants, $rolesSelected, $key, $value);

		// Powered by line
		$whiteLabelOption = new Options\WhiteLabel\WhiteLabelOption();
		$whiteLabelValue = $whiteLabelOption->getValue();
		if($whiteLabelValue == '1') {
			$whiteLabelValue = true;
		} else {
			$whiteLabelValue = false;
		}
		unset($whiteLabelOption);
		$builder->add('white_label', Type\CheckboxType::class, array(
			'label' => __('Credits', Plugin::NAME),
			'data' => $whiteLabelValue,
			'required' => false,
		));
		unset($whiteLabelValue);

		$builder->add('nonce', Type\HiddenType::class, array(
			'data' => wp_create_nonce(Plugin::NAME .'-save-settings'),
			'constraints' => array(
				new Constraints\NotBlank(),
				new WordPressNonceConstraint(array('name' => Plugin::NAME .'-save-settings'))
			),
		));

		$builder
			->add('submit', Type\SubmitType::class, array(
				'label' => __('Save Changes', Plugin::NAME),
				'attr' => array('class' => 'button button-primary'),
			))
		;
	}
}
