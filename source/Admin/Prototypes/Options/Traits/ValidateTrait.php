<?php
namespace Setka\Editor\Admin\Prototypes\Options\Traits;

trait ValidateTrait {

	public function validate() {
		/**
		 * @var $this \Setka\Editor\Admin\Prototypes\Options\OptionInterface
		 */
		return $this->getValidator()->validate( $this->getValue(), $this->getConstraint() );
	}

	public function isValid() {
		$errors = $this->validate();
		if( count( $errors ) === 0 ) {
			return true;
		}
		return false;
	}

	public function validateValue( $value ) {
		/**
		 * @var $this \Setka\Editor\Admin\Prototypes\Options\OptionInterface
		 */
		return $this->getValidator()->validate( $value, $this->getConstraint() );
	}
}
