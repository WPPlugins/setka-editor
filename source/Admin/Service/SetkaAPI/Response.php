<?php
namespace Setka\Editor\Admin\Service\SetkaAPI;

use Symfony\Component\HttpFoundation;

/**
 * This object represents the answer from Setka API.
 *
 * Ok I know what HttpFoundation\Response represent other logic object
 * but we use it just for write less code (and HTTPFoundation not using any Traits).
 */
class Response extends HttpFoundation\Response {

	/**
	 * @var string|HttpFoundation\ParameterBag
	 */
	public $content;

	public function __construct( $response ) {

		// Headers
		// WP >= 4.6
		if( isset( $response['headers'] ) && is_a( $response['headers'], 'Requests_Utility_CaseInsensitiveDictionary' ) ) {
			$this->headers = new HttpFoundation\HeaderBag( $response['headers']->getAll() );
		}
		// WP < 4.6
		elseif( isset( $response['headers'] ) && is_array( $response['headers'] ) ) {
			$this->headers = new HttpFoundation\HeaderBag( $response['headers'] );
		}
		else {
			$this->headers = new HttpFoundation\HeaderBag( array() );
		}

		// Body
		if( isset( $response['body'] ) && is_string( $response['body'] ) ) {
			// Do not parse body at now
			$this->setContent( $response['body'] );
		}

		// Status
		if( isset( $response['response']['code'] ) ) {
			$this->setStatusCode( $response['response']['code'] );
		}
		else {
			throw new \InvalidArgumentException('Cant find the HTTP status code in response.');
		}
	}

	public function parseContent() {
		if(0 === strpos($this->headers->get('Content-Type'), 'application/json')) {

			$json = json_decode( $this->content, true );
			$error = json_last_error();

			if( $error === JSON_ERROR_NONE ) {
				$this->content = new HttpFoundation\ParameterBag( $json );
			}
			else {
				throw new \InvalidArgumentException('The response body contain invalid JSON data');
			}
		}
		else {
			throw new \InvalidArgumentException('The response body format not supported');
		}
	}

	public function __toString() {}

	public function prepare(\Symfony\Component\HttpFoundation\Request $request){}
	public function sendHeaders(){}

	public function sendContent(){}
	public function send(){}

	public function setProtocolVersion($version){}
	public function getProtocolVersion(){}

	public static function closeOutputBuffers($targetLevel, $flush){}
}
