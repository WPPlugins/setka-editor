<?php
namespace Setka\Editor\Admin\Options\SubscriptionActiveUntil;

use Setka\Editor\Admin\Prototypes;
use Setka\Editor\Plugin;
use Symfony\Component\Validator\Constraints;

class Option extends Prototypes\Options\AbstractOption {

	public function __construct() {
		parent::__construct( Plugin::_NAME_ . '_subscription_active_until', '' );
		$this->setDefaultValue( '' );
	}

	public function buildConstraint() {
		return array(
			new Constraints\NotBlank(),
			new Constraints\DateTime(array(
				'format' => \DateTime::ISO8601
			))
			// Example: 2016-08-25T18:05:35+03:00
		);
	}

	public function sanitize( $instance ) {
		return sanitize_text_field( $instance );
	}
}