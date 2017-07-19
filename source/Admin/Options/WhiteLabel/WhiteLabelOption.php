<?php
namespace Setka\Editor\Admin\Options\WhiteLabel;

use Setka\Editor\Admin\Prototypes\Options\AbstractOption;
use Setka\Editor\Plugin;
use Symfony\Component\Validator\Constraints;

/**
 * Class WhiteLabelOption shows should or not we show credits after posts.
 *
 * @package Setka\Editor\Admin\Options\WhiteLabel
 */
class WhiteLabelOption extends AbstractOption  {

	public function __construct() {
		parent::__construct(Plugin::_NAME_ . '_white_label', '');
		$this->setDefaultValue('0');
	}

	public function buildConstraint() {
		return new Constraints\Type(array('type' => 'bool'));
	}

	public function sanitize($instance) {
		return $instance;
	}
}
