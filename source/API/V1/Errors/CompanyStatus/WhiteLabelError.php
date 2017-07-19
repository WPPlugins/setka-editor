<?php
namespace Setka\Editor\API\V1\Errors\CompanyStatus;

use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\Errors\Error;

class WhiteLabelError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_api_company_status_white_label_markup_attribute_error' );
		$this->setMessage( __( 'The request from Setka server missed `white_label_markup` attribute or it has not valid format.', Plugin::NAME ) );
	}
}
