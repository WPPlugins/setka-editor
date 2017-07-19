<?php
namespace Setka\Editor\Admin\Options\PlanFeatures\Nodes;

use Setka\Editor\Admin\Prototypes\Options\AbstractValueNode;
use Symfony\Component\Validator\Constraints;

class WhiteLabelHTML extends AbstractValueNode {

	/**
	 * WhiteLabel constructor.
	 */
	public function __construct() {
		$this->setName('white_label_html');
		$this->setDefaultValue('');
	}


	public function buildConstraint() {
		return new Constraints\Type(array('type' => 'string'));
	}

	public function sanitize( $instance ) {
		return $instance;
	}
}
