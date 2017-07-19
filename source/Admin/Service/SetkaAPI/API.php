<?php
namespace Setka\Editor\Admin\Service\SetkaAPI;

use Setka\Editor\Plugin;
use Setka\Editor\Service\WPErrorUtils;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Validator\Validation;

class API {

	/**
	 * @var \Setka\Editor\Admin\Service\SetkaAPI\AuthCredits
	 */
	private $authCredits;

	/**
	 * @var \Symfony\Component\Validator\Validator\ValidatorInterface
	 */
	private $validator;

	/**
	 * @var \Setka\Editor\Admin\Service\SetkaAPI\Prototypes\ActionInterface
	 */
	private $action;

	public function __construct() {
		$this->setValidator();
	}

	public function getAuthCredits() {
		return $this->authCredits;
	}

	public function setAuthCredits( AuthCredits $authCredits ) {
		$this->authCredits = $authCredits;
	}

	public function getValidator() {
		return $this->validator;
	}

	public function setValidator() {
		$this->validator = Validation::createValidator();
	}

	/**
	 * @return Prototypes\ActionInterface
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * @param Prototypes\ActionInterface $action
	 */
	public function setAction( $action ) {
		$this->action = $action;
	}

	/**
	 * Make API Call based on passed $action.
	 *
	 * @param Prototypes\ActionInterface $action
	 */
	public function request( Prototypes\ActionInterface $action ) {

		$this->setAction( $action );
		$action->setApi( $this );

		$url = $this->getRequestUrl();
		$details = $this->getRequestDetails();

		$response = wp_remote_request( $url, $details );

		// Can't connect or something similar
		if( is_wp_error( $response ) ) {
			$action->getErrors()->add(
				new Errors\ConnectionError( array(
					'error' => $response
				) )
			);
			//WPErrorUtils::convertWPErrorToSetkaErrors( $response, $action->getErrors() );
			return;
		}

		unset($url, $details);

		$action->setResponse( new Response( $response ) );
		unset( $response );
		$action->parseResponse();
	}

	/**
	 * Returns an URL with desired parameters (query args-attrs) to make a request.
	 *
	 * I'm not using https://github.com/thephpleague/uri or http_build_url() because
	 * they require additional libs in PHP such as ext-intl. This libs (additional dependencies)
	 * not good for WordPress plugin.
	 */
	public function getRequestUrl() {
		if( defined( 'SETKA_EDITOR_DEBUG' ) && SETKA_EDITOR_DEBUG === true ) {
			$url = Endpoints::API_DEV;
		}
		else {
			$url = Endpoints::API;
		}

		// Action endpoint
		$action = $this->getAction();
		$action_endpoint = $action::ENDPOINT;
		$url .= constant( $action_endpoint );

		// Query args (attrs) like ?token=123&blabla=123
		$url = add_query_arg( $this->getRequestUrlQuery(), $url );

		return $url;
	}

	public function getRequestUrlQuery() {
		return array_merge_recursive(
			// Required attrs for all requests
			$this->getRequestUrlQueryRequired(),

			// Action specific attrs
			$this->getAction()->getRequestUrlQuery()
		);
	}

	public function getRequestUrlQueryRequired() {
		global $wp_version;
		return array(
			'app_version' => $wp_version,
			'domain'      => get_site_url()
		);
	}

	public function getRequestDetails() {
		return array_merge_recursive(
			$this->getRequestDetailsRequired(),

			$this->getAction()->getRequestDetails()
		);
	}

	public function getRequestDetailsRequired() {
		$details =  array(
			'method' => $this->getAction()->getMethod(),
			'body'   => array(
				'plugin_version' => Plugin::VERSION
			)
		);

		if($this->getAction()->isAuthenticationRequired()) {
			$details['body']['token'] = $this->getAuthCredits()->getToken();
		}

		if( defined( 'SETKA_EDITOR_API_BASIC_AUTH_USERNAME' ) && defined( 'SETKA_EDITOR_API_BASIC_AUTH_PASSWORD' ) ) {
			$details['headers'] = array(
				'Authorization' => 'Basic ' . base64_encode( SETKA_EDITOR_API_BASIC_AUTH_USERNAME . ':' . SETKA_EDITOR_API_BASIC_AUTH_PASSWORD )
			);
		}

		return $details;
	}

	public function parseResponseBody() {

		// Response may contain WP_Error object
		if( is_wp_error( $this->response ) )
			return false;

		// Response may don't have body
		if( !isset( $this->response['body'] ) )
			return false;

		// Ok. We have body. Try to parse it.

		$body = json_decode( $this->response['body'], true );

		$error = json_last_error();

		if( $error === JSON_ERROR_NONE ) {
			return $body;
		}
		return false;
	}

	function __destruct() {
		unset(
			$this->authCredits,
			$this->validator,
			$this->action,
			$this->response
		);
	}
}
