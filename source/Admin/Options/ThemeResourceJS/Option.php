<?php
namespace Setka\Editor\Admin\Options\ThemeResourceJS;

use Setka\Editor\Admin\Prototypes;
use Setka\Editor\Plugin;
use Symfony\Component\Validator\Constraints;

class Option extends Prototypes\Options\AbstractOption {
// TODO: rename this class (create new class because we use json now. js in past)
	public function __construct() {
		parent::__construct( Plugin::_NAME_ . '_theme_resource_js', '' );
		$this->setDefaultValue( '' );
	}

	public function buildConstraint() {
		return array(
			new Constraints\NotBlank(),
			new Constraints\Url()
		);
	}

	public function sanitize( $instance ) {
		return sanitize_text_field( $instance );
	}
}
