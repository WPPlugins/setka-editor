<?php
namespace Setka\Editor\Admin\Prototypes\Options\Traits\Aggregate;

trait ValueTrait {

	public function getValue() {

		/**
		 * @var $this \Setka\Editor\Admin\Prototypes\Options\NodeInterface
		 */

		$parent = $this->getParent();
		$value = array();

		if($parent) {
			$raw = $parent->getValueRaw();

			if($raw) {
				if(isset($raw[$this->getName()])) {
					return $raw[$this->getName()];
				}
			}
		} else {
			if(isset($this->value)) {
				return $this->value;
			}
			$raw_value = $this->getValueRaw();

			if( $raw_value !== false ) {
				return $raw_value;
			}
		}

		return $value;
	}

	public function setValue($value) {
		$this->value = $value;
	}
}
