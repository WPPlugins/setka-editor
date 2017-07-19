<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Actions;

use Setka\Editor\Admin\Service\SetkaAPI;
use Setka\Editor\Admin\Service\SetkaAPI\Errors;

class ResendTokenLetterAction extends SetkaAPI\Prototypes\ActionAbstract {

	const ENDPOINT = '\Setka\Editor\Admin\Service\SetkaAPI\Endpoints::RESEND_TOKEN_LETTER';

	const METHOD = 'POST';

	public function __construct() {
		$this->setAuthenticationRequired(false);
	}

	public function getConstraint() {}

	public function parseResponse() {
		$response = $this->getResponse();
		$errors = $this->getErrors();

		if(!$this->parseContent())
			return;

		switch($response->getStatusCode()) {
			// 20x
			case $response::HTTP_OK:
				break;

			// 404
			case $response::HTTP_NOT_FOUND:
				$helper = new SetkaAPI\Helpers\ErrorHelper($this->getApi(), $response, $errors);
				$helper->parseResponse();
				break;

			default:
				$errors->add(new Errors\UnknownError());
				break;
		}
	}
}
