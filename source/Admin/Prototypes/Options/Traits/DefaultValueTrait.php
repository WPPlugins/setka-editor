<?php
namespace Setka\Editor\Admin\Prototypes\Options\Traits;

trait DefaultValueTrait {

	/**
	 * @var mixed A default value for this unit.
	 */
	protected $defaultValue;

	public function getDefaultValue() {
		return $this->defaultValue;
	}

	public function setDefaultValue( $defaultValue ) {
		$this->defaultValue = $defaultValue;
		return $this;
	}
}
