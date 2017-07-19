<?php
namespace Setka\Editor\API\V1\Errors\CompanyStatus;

use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\Errors\Error;

class ActiveUntilAttributeError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_api_company_status_active_until_attribute_error' );
		$this->setMessage( __( 'The request from Setka server missed active_until attribute or it has not valid format.', Plugin::NAME ) );
	}
}
