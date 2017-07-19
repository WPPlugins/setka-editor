<?php
namespace Setka\Editor\API\V1\Errors\CompanyStatus;

use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\Errors\Error;

class PaymentStatusAttributeError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_api_company_status_payment_status_attribute_error' );
		$this->setMessage( __( 'The request from Setka server missed `payment_status` attribute or it has not valid format.', Plugin::NAME ) );
	}
}
