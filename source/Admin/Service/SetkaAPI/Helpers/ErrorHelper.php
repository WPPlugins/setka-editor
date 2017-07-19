<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Helpers;

use Setka\Editor\Admin\Service\SetkaAPI;
use Setka\Editor\Admin\Service\SetkaAPI\Errors;
use Symfony\Component\Validator\Constraints;

class ErrorHelper extends SetkaAPI\Prototypes\HelperAbstract {

	public function buildResponseConstraints() {
		return array(
			new Constraints\NotBlank(),
			new Constraints\Type(array(
				'type' => 'string'
			))
		);
	}

	public function parseResponse() {
		$response = $this->getResponse();
		// Single error message from API
		if(
			$response->content->has('error')
			&&
			!$response->content->has('errors')
		) {
			$validator = $this->getApi()->getValidator();
			$results = $validator->validate(
				$response->content->get('error'),
				$this->getResponseConstraints()
			);
			if( count( $results ) === 0 ) {
				// OK. We have one valid error
				return;
			}
			$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
		}
		// Multiple error messages from API
		elseif(
			$response->content->has('errors')
			&&
			!$response->content->has('error')
		) {
			// Array required
			$errors = $response->content->get('errors');
			$this->validateErrors($errors);
		} else {
			$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
		}
	}

	/**
	 * Validates the errors list.
	 *
	 * @param $errors array A list of errors.
	 */
	protected function validateErrors(&$errors) {
		/**
		 * An example of errors list.
		 * $a = array(
			'errors' => array(
				'email' => array(
					0 => '123',
					1 => '234',
				),
				'first_name' => array(
					0 => '123',
					1 => '456',
				),
			),
		);*/
		if(is_array($errors)) {
			// Validate each error in set
			$validator = $this->getApi()->getValidator();
			$constraint = $this->getResponseConstraints();

			foreach($errors as $error_key => &$error_value) {
				if(is_array($error_value)) {
					$this->validateErrors($error_value);
					return;
				} else {
					$results = $validator->validate($error_value, $constraint);
					if(count($results) !== 0) {
						$this->getErrors()->add(new Errors\ResponseBodyInvalidError());
						return;
					}
				}
			}
		} else {
			$this->getErrors()->add(new Errors\ResponseBodyInvalidError());
		}
	}
}
