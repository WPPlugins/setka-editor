<?php
namespace Setka\Editor\Admin\AJAX\Errors;

use Setka\Editor\Prototypes\Errors\Error;
use Setka\Editor\Plugin;

class InvalidRequestDataError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_ajax_invalid_request_data' );
		$this->setMessage( __( 'Invalid request data.', Plugin::NAME ) );
	}
}
