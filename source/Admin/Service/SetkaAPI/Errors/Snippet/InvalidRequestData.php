<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Errors\Snippet;

use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\Errors\Error;

class InvalidRequestData extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_setka_api_snippet_invalid_request_data' );
		$this->setMessage( __( 'Snippet name or snippet body can’t be blank', Plugin::NAME ) );
	}
}
