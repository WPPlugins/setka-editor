<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Errors;

use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\Errors\Error;

class InternalServerError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_setka_api_internal_server_error' );
		$this->setMessage( __( 'Setka Editor Server is experiencing some technical issues. Please try again in a couple of minutes.', Plugin::NAME ) );
	}
}
