<?php
namespace Setka\Editor\API\V1\Errors;

use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\Errors\Error;

class RequestDataError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_api_request_data_error' );
		$this->setMessage( __( 'Invalid request data. The Plugin can\'t validate the server request.', Plugin::NAME ) );
	}
}
