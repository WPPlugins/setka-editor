<?php
namespace Setka\Editor\Admin\Options\SubscriptionStatus;

use Setka\Editor\Admin\Prototypes;
use Setka\Editor\Plugin;
use Symfony\Component\Validator\Constraints;

class Option extends Prototypes\Options\AbstractOption {

	public function __construct() {
		parent::__construct( Plugin::_NAME_ . '_subscription_status', '' );
		$this->setDefaultValue( '' );
	}

	public function buildConstraint() {
		return array(
			new Constraints\NotBlank(),
			new Constraints\Choice(array(
				'choices' => array( 'running', 'blocked' ),
				'strict' => true
			))
		);
	}

	public function sanitize( $instance ) {
		return sanitize_text_field( $instance );
	}
}
