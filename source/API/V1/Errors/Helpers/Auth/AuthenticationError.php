<?php
namespace Setka\Editor\API\V1\Errors\Helpers\Auth;

use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\Errors\Error;

class AuthenticationError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_api_authenticate_error' );
		$this->setMessage( __( 'Sorry. We can\'t authenticate this request.', Plugin::NAME ) );
	}
}
