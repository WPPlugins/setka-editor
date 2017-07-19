<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Prototypes;

use Setka\Editor\Admin\Service\SetkaAPI;

interface HelperInterface {

	public function getApi();
	public function setApi( SetkaAPI\API $api );

	public function getResponse();
	public function setResponse( SetkaAPI\Response $response );

	public function getErrors();
	public function setErrors( \Setka\Editor\Prototypes\Errors\ErrorsInterface $errors );

	public function getResponseConstraints();
	public function setResponseConstraints($constraints);
	public function buildResponseConstraints();

	public function parseResponse();
}
