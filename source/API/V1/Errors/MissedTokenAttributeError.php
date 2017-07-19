<?php
namespace Setka\Editor\API\V1\Errors;

use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\Errors\Error;

class MissedTokenAttributeError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_api_missed_token_attribute_error' );
		$this->setMessage( __( 'The request from Setka server missed license key attribute and the plugin cant authenticate this request.', Plugin::NAME ) );
	}
}
