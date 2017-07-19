<?php
namespace Setka\Editor\Admin\Options\PlanFeatures\Nodes;

use Setka\Editor\Admin\Prototypes\Options\AbstractValueNode;
use Symfony\Component\Validator\Constraints;

class WhiteLabel extends AbstractValueNode {

	/**
	 * WhiteLabel constructor.
	 */
	public function __construct() {
		$this->setName('white_label');
		$this->setDefaultValue(false);
	}


	public function buildConstraint() {
		return new Constraints\Type(array('type' => 'bool'));
	}

	public function sanitize( $instance ) {
		return $instance;
	}
}
