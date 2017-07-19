<?php
namespace Setka\Editor\Admin\Prototypes\Options\Traits;

trait ConstraintTrait {

	/**
	 * @var array|\Symfony\Component\Validator\Constraint;
	 */
	protected $constraint;

	public function getConstraint() {
		if( ! $this->constraint ) {
			$this->setConstraint( $this->buildConstraint() );
		}
		return $this->constraint;
	}

	public function setConstraint( $constraint ) {
		$this->constraint = $constraint;
	}

	abstract public function buildConstraint();
}
