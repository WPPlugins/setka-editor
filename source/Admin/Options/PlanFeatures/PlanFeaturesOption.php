<?php
namespace Setka\Editor\Admin\Options\PlanFeatures;

use Setka\Editor\Admin\Prototypes\Options\AbstractOption;
use Setka\Editor\Admin\Prototypes\Options\OptionAggregate;
use Setka\Editor\Plugin;
use Symfony\Component\Validator\Constraints;

class PlanFeaturesOption extends OptionAggregate {

	public function __construct() {
		parent::__construct( Plugin::_NAME_ . '_plan_features', '' );
		$node = new Nodes\WhiteLabel();
		$node->setParent($this);
		$this->addNode($node);

		$node = new Nodes\WhiteLabelHTML();
		$node->setParent($this);
		$this->addNode($node);
	}
}
