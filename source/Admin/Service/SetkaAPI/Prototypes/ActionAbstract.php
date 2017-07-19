<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Prototypes;

use Setka\Editor\Admin\Service\SetkaAPI;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class ActionAbstract implements ActionInterface {

	protected $method;

	/**
	 * @var \Setka\Editor\Admin\Service\SetkaAPI\API
	 */
	protected $api;

	/**
	 * @var \Setka\Editor\Admin\Service\SetkaAPI\Response
	 */
	public $response;

	/**
	 * @var \Setka\Editor\Prototypes\Errors\ErrorsInterface
	 */
	protected $errors;

	/**
	 * @var array The request details data
	 */
	protected $requestDetails = array();

	/**
	 * @var bool Flag which shows should we authenticate this request or not.
	 */
	protected $authenticationRequired = true;

	public function getMethod() {
		return $this::METHOD;
	}

	public function setMethod($method) {
		$this->method = $method;
	}

	public function getApi() {
		return $this->api;
	}

	public function setApi( SetkaAPI\API $api ) {
		$this->api = $api;
	}

	public function getResponse() {
		return $this->response;
	}

	public function setResponse( SetkaAPI\Response $response ) {
		$this->response = $response;
	}

	public function getErrors() {
		if( !is_a( $this->errors, '\Setka\Editor\Prototypes\Errors\ErrorsInterface' ) ) {
			$this->setErrors( new \Setka\Editor\Prototypes\Errors\Errors() );
		}
		return $this->errors;
	}

	public function setErrors( \Setka\Editor\Prototypes\Errors\ErrorsInterface $errors ) {
		$this->errors = $errors;
	}

	public function getRequestUrlQuery() {
		return array();
	}

	public function getRequestDetails() {
		return $this->requestDetails;
	}

	public function setRequestDetails( array $requestDetails = array() ) {
		$this->requestDetails = $requestDetails;
	}

	abstract public function getConstraint();

	abstract public function parseResponse();

	public function parseContent() {
		try {
			$this->getResponse()->parseContent();
			return true;
		} catch( \Exception $e ) {
			$this->getErrors()->add( new SetkaAPI\Errors\ResponseBodyInvalidError() );
			return false;
		}
	}

	function __destruct() {
		// TODO: remove destructors and their calls
		unset( $this->api );
		unset( $this->errors );
	}

	/**
	 * @return bool
	 */
	public function isAuthenticationRequired() {
		return $this->authenticationRequired;
	}

	/**
	 * @param bool $authenticationRequired
	 */
	public function setAuthenticationRequired($authenticationRequired) {
		$this->authenticationRequired = (bool) $authenticationRequired;
	}
}
