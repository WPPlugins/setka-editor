<?php
namespace Setka\Editor\Service\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class WordPressNonceConstraintValidator extends ConstraintValidator {

	/**
	 * {@inheritdoc}
	 */
	public function validate($value, Constraint $constraint) {

		if(!$constraint instanceof WordPressNonceConstraint) {
			throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\WordPressNonce');
		}

		if(!is_string($constraint->name)) {
			throw new ConstraintDefinitionException('The option "name" of WordPressNonceConstraint must be a string.');
		}

		$result = wp_verify_nonce($value, $constraint->name);

		// Valid nonce!
		if($result === 1 || $result === 2) {
			return;
		}

		// Wrong Nonce (error)
		$this->context->buildViolation($constraint->message)
			->setInvalidValue($value)
			->addViolation();
	}
}
