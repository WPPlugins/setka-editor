<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Prototypes;

use Setka\Editor\Admin\Service\SetkaAPI;
use Symfony\Component\Validator\Constraint;

abstract class HelperAbstract implements HelperInterface {

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
	 * @var Constraint[]
	 */
	protected $responseConstraints;

	/**
	 * HelperAbstract constructor.
	 */
	public function __construct( $api, $response, $errors ) {
		$this->setApi( $api );
		$this->setResponse( $response );
		$this->setErrors( $errors );
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

	public function getResponseConstraints() {
		if(!$this->responseConstraints) {
			$this->responseConstraints = $this->buildResponseConstraints();
		}
		return $this->responseConstraints;
	}

	public function setResponseConstraints($constraints) {
		$this->responseConstraints = $constraints;
	}

	abstract public function buildResponseConstraints();

	abstract public function parseResponse();

	function __destruct() {
		unset( $this->api );
		unset( $this->errors );
	}
}
