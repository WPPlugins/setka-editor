<?php
namespace Setka\Editor\API\V1;

use Setka\Editor\Prototypes\Errors\Errors;
use Setka\Editor\Prototypes\Errors\ErrorsInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class API {

	public $actionsClasses = array(
		'Setka\Editor\API\V1\Actions\CompanyStatus\Update\Action',
		'Setka\Editor\API\V1\Actions\Resources\Update\Action',
		'Setka\Editor\API\V1\Actions\Token\Check\Action'
	);

	/**
	 * @var \Setka\Editor\API\V1\Prototypes\AbstractAction[]
	 */
	protected $actionInstances = array();

	/**
	 * @var \Symfony\Component\Validator\Validator\ValidatorInterface
	 */
	protected $validator;

	/**
	 * @var \Symfony\Component\HttpFoundation\Request
	 */
	protected $request;

	/**
	 * @var \Symfony\Component\HttpFoundation\Response
	 */
	protected $response;

	/**
	 * @var \Symfony\Component\HttpFoundation\ParameterBag
	 */
	protected $responseData;

	/**
	 * @var \Setka\Editor\Prototypes\Errors\ErrorsInterface
	 */
	protected $responseErrors;

	/**
	 * API constructor.
	 *
	 * @param $request \Symfony\Component\HttpFoundation\Request
	 */
	public function __construct( Request $request, Response $response, ParameterBag $responseData, Errors $responseErrors, $validator = null ) {
		$this->setRequest( $request );
		$this->setResponse( $response );
		$this->setResponseData( $responseData );
		$this->setResponseErrors( $responseErrors );

		if( !$validator ) {
			$validator = Validation::createValidator();
		}
		$this->setValidator( $validator );
	}

	/**
	 * @return array
	 */
	public function getActionsClasses() {
		return $this->actionsClasses;
	}

	/**
	 * @param array $actionsClasses
	 */
	public function setActionsClasses( array $actionsClasses ) {
		$this->actionsClasses = $actionsClasses;
	}

	/**
	 * @return Prototypes\AbstractAction[]
	 */
	public function getActionInstances() {
		return $this->actionInstances;
	}

	/**
	 * @param Prototypes\AbstractAction[] $actionInstances
	 */
	public function setActionInstances( array $actionInstances ) {
		$this->actionInstances = $actionInstances;
	}

	/**
	 * @return Request
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * @param Request $request
	 */
	public function setRequest( Request $request ) {
		$this->request = $request;
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function getResponse() {
		return $this->response;
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Response $response
	 */
	public function setResponse( Response $response ) {
		$this->response = $response;
	}

	/**
	 * @return ParameterBag
	 */
	public function getResponseData() {
		return $this->responseData;
	}

	/**
	 * @param ParameterBag $responseData
	 */
	public function setResponseData( ParameterBag $responseData ) {
		$this->responseData = $responseData;
	}

	/**
	 * @return \Setka\Editor\Prototypes\Errors\ErrorsInterface
	 */
	public function getResponseErrors() {
		return $this->responseErrors;
	}

	/**
	 * @param $responseErrors
	 */
	public function setResponseErrors( ErrorsInterface $responseErrors ) {
		$this->responseErrors = $responseErrors;
	}

	/**
	 * Shortcut for add response errors.
	 *
	 * @param $error \Setka\Editor\Prototypes\Errors\ErrorInterface
	 *
	 * @return $this Allow for chainable calls.
	 */
	public function addError( $error ) {
	    $this->getResponseErrors()->add( $error );
	    return $this;
	}

	public function getValidator() {
		return $this->validator;
	}

	public function setValidator( $validator ) {
		$this->validator = $validator;
	}

	public function handleRequest() {

		// First of all we need to find an action

		// _POST
		if( $this->getRequest()->request->has('action') ) {
			$actionName = $this->getRequest()->request->get('action');
		}
		// _GET
		elseif( $this->getRequest()->query->has('action') ) {
			$actionName = $this->getRequest()->query->get('action');
		}
		// No action
		else {
			$this->getResponse()->setStatusCode( Response::HTTP_BAD_REQUEST );
			return;
		}

		// If action founded - try to find Action class and run it
		foreach( $this->getActionsClasses() as $class ) {
			$action = new $class($this);
			$this->actionInstances[$this->getFullEndpoint($action)] = $action;
		}

		if( isset( $this->actionInstances[$actionName] ) ) {
			$this->actionInstances[$actionName]->handleRequest();
			$this->actionInstances[$actionName]->__destruct();
		} else {
			$this->getResponse()->setStatusCode( Response::HTTP_NOT_FOUND );
			throw new Exceptions\APIActionDoesntExists( 'API doesn\'t support this action.' );
		}
	}

	function __destruct() {
		unset(
			$this->validator,
			$this->request,
			$this->response
		);
	}

	function getFullEndpoint( Prototypes\ActionInterface $action ) {
		$endpoint = '/webhook/setka-editor/v1/';
		$endpoint .= $action->getEndpoint();
		return $endpoint;
	}
}
