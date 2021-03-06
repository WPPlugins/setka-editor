<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Errors;

use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\Errors\Error;

class ServerUnauthorizedError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_setka_api_server_unauthorized' );

		$message = sprintf(
			__( 'Oops... Your Setka Editor license key is not valid. Error code: <code>%1$s</code>. Please contact Setka Editor support team <a href="mailto:helpme@setka.io">helpme@setka.io</a>.', Plugin::NAME ),
			esc_html( $this->getCode() )
		);

		$this->setMessageHTML( $message );
	}
}
