<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Prototypes;

use Setka\Editor\Admin\Service\SetkaAPI;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ActionInterface {

	public function getMethod();
	public function setMethod($method);

	public function getApi();
	public function setApi( SetkaAPI\API $api );

	public function getResponse();
	public function setResponse( SetkaAPI\Response $response );

	/**
	 * @return \Setka\Editor\Prototypes\Errors\ErrorsInterface
	 */
	public function getErrors();
	public function setErrors( \Setka\Editor\Prototypes\Errors\ErrorsInterface $errors );

	public function getRequestUrlQuery();

	public function getRequestDetails();
	public function setRequestDetails( array $requestDetails = array() );

	public function getConstraint();

	public function parseResponse();

	public function isAuthenticationRequired();
	public function setAuthenticationRequired($authenticationRequired);
}
