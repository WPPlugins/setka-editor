<?php
namespace Setka\Editor\Service\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class WordPressNonceConstraint extends Constraint {

	public $message = 'The nonce is not valid.';
	public $name = '_wpnonce';

	/**
	 * {@inheritdoc}
	 */
	public function getDefaultOption() {
		return 'name';
	}
}
