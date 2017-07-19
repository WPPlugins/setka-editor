<?php
namespace Setka\Editor\Admin\Options\SubscriptionPaymentStatus;

use Setka\Editor\Admin\Prototypes;
use Setka\Editor\Plugin;
use Symfony\Component\Validator\Constraints;

class Option extends Prototypes\Options\AbstractOption {

	public function __construct() {
		parent::__construct( Plugin::_NAME_ . '_subscription_payment_status', '' );
		$this->setDefaultValue( '' );
	}

	public function buildConstraint() {
		return array(
			new Constraints\NotBlank(),
			new Constraints\Choice(array(
				'choices' => array( 'active', 'trialing', 'unpaid', 'canceled', 'past_due' ),
				'strict' => true
			))
		);
	}

	public function sanitize( $instance ) {
		return sanitize_text_field( $instance );
	}
}
