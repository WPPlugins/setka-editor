<?php
namespace Setka\Editor\API\V1\Prototypes;

use Setka\Editor\API\V1;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractAction implements ActionInterface {

	/**
	 * @var \Setka\Editor\API\V1\API
	 */
	protected $api;

	/**
	 * @var $endpoint string The string with name of this action.
	 */
	protected $endpoint;

	public function __construct( V1\API $api ) {
		$this->setApi( $api );
	}

	/**
	 * @return V1\API
	 */
	public function getApi() {
		return $this->api;
	}

	/**
	 * @param V1\API $api
	 */
	public function setApi( V1\API $api ) {
		$this->api = $api;
	}

	/**
	 * @return string
	 */
	public function getEndpoint() {
		return $this->endpoint;
	}

	/**
	 * @param string $endpoint
	 */
	public function setEndpoint( $endpoint ) {
		$this->endpoint = $endpoint;
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Request
	 */
	public function getRequest() {
		return $this->getApi()->getRequest();
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function getResponse() {
		return $this->getApi()->getResponse();
	}

	/**
	 * @return ParameterBag
	 */
	public function getResponseData() {
		return $this->getApi()->getResponseData();
	}

	abstract public function handleRequest();

	abstract public function getConstraint();

	function __destruct() {
		unset(
			$this->api,
			$this->request,
			$this->response
		);
	}
}