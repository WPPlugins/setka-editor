<?php
namespace Setka\Editor\Admin\Prototypes\Options\Traits\Aggregate;

trait ValueRawTrait {

	public function getValueRaw() {
		/**
		 * @var $this \Setka\Editor\Admin\Prototypes\Options\AggregateNodeInterface
		 */
		$parent = $this->getParent();
		if($parent) {
			$parent_value = $parent->getValueRaw();

			if( isset( $parent_value[$this->getName()] ) ) {
				return $parent_value[$this->getName()];
			}
		}
		return false;
	}
}
