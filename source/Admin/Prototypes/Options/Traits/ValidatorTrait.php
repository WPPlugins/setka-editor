<?php
namespace Setka\Editor\Admin\Prototypes\Options\Traits;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait ValidatorTrait {

	/**
	 * @var \Symfony\Component\Validator\Validator\ValidatorInterface
	 */
	protected $validator;

	public function getValidator() {
		if( ! $this->validator ) {
			$this->setValidator( $this->buildValidator() );
		}
		return $this->validator;
	}

	public function setValidator( ValidatorInterface $validator ) {
		$this->validator = $validator;
	}

	public function buildValidator() {
		return Validation::createValidator();
	}
}
