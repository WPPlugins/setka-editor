<?php
namespace Setka\Editor\API\V1\Errors;

use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\Errors\Error;

class HttpMethodError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_api_http_method_error' );
		$this->setMessage( __( 'Invalid HTTP method. The plugin not allows this types of requests.', Plugin::NAME ) );
	}
}
