<?php
namespace Setka\Editor\Admin\Options\EditorAccessPostTypes;

use Setka\Editor\Admin\Prototypes;
use Setka\Editor\Plugin;
use Symfony\Component\Validator\Constraints;

class Option extends Prototypes\Options\AbstractOption {

	public function __construct() {
		parent::__construct( Plugin::_NAME_ . '_editor_access_post_types', Plugin::_NAME_ . '_general' );
		$this->setDefaultValue( array( 'post', 'page' ) );
	}

	public function buildConstraint() {
		$post_types = get_post_types();
		unset( $post_types['attachment'] );
		unset( $post_types['revision'] );
		unset( $post_types['nav_menu_item'] );
		$post_types = array_values( $post_types );

		return array(
			new Constraints\NotNull(),
			new Constraints\Choice(array(
				'choices' => $post_types,
				'multiple' => true,
				'strict' => true,
			))
		);
	}

	public function sanitize( $instance ) {
		// Disabled for all post types
		$sanitized = array();
		if( is_array( $instance ) ) {
			$validator = $this->getValidator();
			$result = $validator->validate( $instance, $this->getConstraint() );
			if( count($result) === 0 ) {
				return $instance;
			}
		}
		return $sanitized;
	}
}
