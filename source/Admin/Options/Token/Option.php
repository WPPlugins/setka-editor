<?php
namespace Setka\Editor\Admin\Options\Token;

use Setka\Editor\Admin\Prototypes;
use Setka\Editor\Plugin;
use Symfony\Component\Validator\Constraints;

/**
 * Class Option
 *
 * Also known as License key (but by historical reasons it named as token in DB and code).
 *
 * @package Setka\Editor\Admin\Options\Token
 */
class Option extends Prototypes\Options\AbstractOption {

	public function __construct() {
		parent::__construct( Plugin::_NAME_ . '_token', Plugin::_NAME_ . '_auth' );
		$this->setDefaultValue( '' );
	}

	public function buildConstraint() {
		return array(
			new Constraints\NotBlank(array(
				'message' => __( 'Please fill in your license key.', Plugin::NAME )
			)),
			new Constraints\Length(array(
				'min' => 32,
				'max' => 32,
				'exactMessage' => __( 'License key should have exactly {{ limit }} characters.', Plugin::NAME )
			))
		);
	}

	public function sanitize( $instance ) {
		return sanitize_text_field( $instance );
	}
}
