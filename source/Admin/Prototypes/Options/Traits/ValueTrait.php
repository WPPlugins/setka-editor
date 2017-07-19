<?php
namespace Setka\Editor\Admin\Prototypes\Options\Traits;

/**
 * Class ValueTrait
 * @package Setka\Editor\Admin\Prototypes\Options\Traits
 */
trait ValueTrait {

	/**
	 * @var mixed A value for this option.
	 */
	protected $value;

	public function getValue() {
		/**
		 * @var $this \Setka\Editor\Admin\Prototypes\Options\NodeInterface
		 */
		if( isset( $this->value ) ) {
			return $this->value;
		}

		$raw_value = $this->getValueRaw();

		if( $raw_value !== false ) {
			return $raw_value;
		}
		return $this->getDefaultValue();
	}

	public function setValue( $value ) {
		$this->value = $value;
		return $this;
	}
}
