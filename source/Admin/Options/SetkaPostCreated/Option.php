<?php
namespace Setka\Editor\Admin\Options\SetkaPostCreated;

use Setka\Editor\Admin\Prototypes;
use Setka\Editor\Plugin;
use Symfony\Component\Validator\Constraints;

class Option extends Prototypes\Options\AbstractOption {

	public function __construct() {
		parent::__construct( Plugin::_NAME_ . '_setka_post_created', '' );
		$this->setDefaultValue( '0' );
		$this->setAutoload( false );
	}

	public function buildConstraint() {
		return array(
			new Constraints\NotNull(),
			new Constraints\Type(array(
				'type' => 'string'
			)),
			new Constraints\Choice(array(
				'choices' => array('0', '1'),
				'multiple' => false,
				'strict' => true
			))
		);
	}

	public function sanitize( $instance ) {
		if( $this->validateValue( $instance ) ) {
			return $instance;
		}
		return $this->getDefaultValue();
	}
}
