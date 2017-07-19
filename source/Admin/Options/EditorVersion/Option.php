<?php
namespace Setka\Editor\Admin\Options\EditorVersion;

use Setka\Editor\Admin\Prototypes\Options\AbstractOption;
use Setka\Editor\Plugin;
use Symfony\Component\Validator\Constraints;

class Option extends AbstractOption {

	public function __construct() {
		parent::__construct( Plugin::_NAME_ . '_editor_ver', '' );
		$this->setDefaultValue( '0.0.0' );
	}

	public function buildConstraint() {
		return array(
			new Constraints\NotBlank(),
			new Constraints\Type(array(
				'type' => 'string'
			))
		);
	}

	public function sanitize( $instance ) {
		return sanitize_text_field( $instance );
	}
}
