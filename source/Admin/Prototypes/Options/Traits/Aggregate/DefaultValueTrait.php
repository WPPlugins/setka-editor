<?php
namespace Setka\Editor\Admin\Prototypes\Options\Traits\Aggregate;

trait DefaultValueTrait {

	/**
	 * @var mixed A default value for this unit.
	 */
	//protected $defaultValue;

	public function getDefaultValue() {
		$parent = $this->getParent();
		$value = array();

		if($parent) {
			$parentNodes = $this->getParent()->getNodes();
			foreach( $parentNodes as $node ) {
				$value[$node->getName()] = $node->getDefaultValue();
			}
		}

		return $value;
	}

	public function setDefaultValue( $defaultValue ) {
		throw new \Exception('Can\'t setup default value for aggregate node.');
	}
}
