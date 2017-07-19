<?php
namespace Setka\Editor\API\V1\Errors;

use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\Errors\Error;

class MissedDataAttributeError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_api_missed_data_attribute_error' );
		$this->setMessage( __( 'The request from Setka server missed data attribute.', Plugin::NAME ) );
	}
}
