<?php
namespace Setka\Editor\Admin\Prototypes\AJAX;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class API {

	/**
	 * @var \Symfony\Component\HttpFoundation\Request
	 */
	protected $request;

	/**
	 * @var \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\JsonResponse
	 */
	protected $response;

	protected $responseData = array();

	public function lateConstruct( $request = null, $response = null ) {
		if( is_a( $request, 'Symfony\Component\HttpFoundation\Request' ) ) {
			$this->setRequest( $request );
		} else {
			$this->setRequest( Request::createFromGlobals() );
		}

		if( $this->getRequest()->request->has('data') ) {

			$data = $this->getRequest()->request->get('data');

			// url-encoded data
			if( is_array( $data ) ) {
				$this->getRequest()->request->set( 'data', new ParameterBag( $data ) );
			}
			// maybe JSON encoded
			elseif( is_string( $data ) ) {
				// WordPress use addslashes() in wp_magic_quotes()
				$dataEncoded = json_decode( stripslashes( $data ), true );
				if( json_last_error() === JSON_ERROR_NONE && is_array( $dataEncoded ) ) {
					$this->getRequest()->request->set('data', new ParameterBag( $dataEncoded ) );
				}
			}

			unset( $data, $dataEncoded );
		}

		if( is_a( $response, 'Symfony\Component\HttpFoundation\Response' ) ) {
			$this->setResponse( $response );
		} else {
			$this->setResponse( new JsonResponse() );
		}
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
	 * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function getResponse() {
		return $this->response;
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\JsonResponse $response
	 */
	public function setResponse( Response $response ) {
		$this->response = $response;
	}

	/**
	 * @return array
	 */
	public function getResponseData() {
		return $this->responseData;
	}

	/**
	 * @param array $responseData
	 */
	public function setResponseData( $responseData ) {
		$this->responseData = $responseData;
	}
}
