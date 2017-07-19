<?php
namespace Setka\Editor\Admin\Options\EditorAccessRoles;

use Setka\Editor\Admin\Prototypes;
use Setka\Editor\Admin\User\Capabilities;
use Setka\Editor\Plugin;
use Symfony\Component\Validator\Constraints;

class Option extends Prototypes\Options\AbstractOption {

	public function __construct() {
		parent::__construct( Plugin::_NAME_ . '_editor_access_roles', Plugin::_NAME_ . '_general' );
		$this->setDefaultValue( array() );
	}

	public function buildConstraint() {
		$roles = get_editable_roles();

		$roles = array_keys( $roles );

		return array(
			new Constraints\NotNull(),
			new Constraints\Choice(array(
				'choices' => $roles,
				'multiple' => true,
				'strict' => true
			))
		);
	}

	public function sanitize( $instance ) {
		$defaultValue = $this->getDefaultValue();

		/**
		 * WordPress sanitize option two times when option not presented in DB (from update_option() and add_option()).
		 * This behavior without this check remove capabilities and don't restore them because $instance
		 * not an array in second time.
		 */
		if( $instance !== $defaultValue ) {

			$roles = get_editable_roles();
			if( !empty( $roles ) ) {

				// Delete cap from all roles
				foreach( $roles as $role_key => $role_value ) {
					$role = get_role( $role_key );
					$role->remove_cap( Capabilities\UseEditorCapability::NAME );
				}

				// Maybe add our cap
				if( !empty( $instance ) && is_array( $instance ) ) {
					foreach( $roles as $role_key => $role_value ) {
						if( array_search( $role_key, $instance, true ) !== false ) {
							$role = get_role( $role_key );
							$role->add_cap( Capabilities\UseEditorCapability::NAME );
							$defaultValue[$role_key] = $role_key;
						}
					}
				}
			}
		}

		return $defaultValue;
	}

}
