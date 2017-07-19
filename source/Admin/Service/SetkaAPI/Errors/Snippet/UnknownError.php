<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Errors\Snippet;

use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\Errors\Error;

class UnknownError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_setka_api_snippet_server_unknown' );
		$this->setMessageHTML( __( 'There is an error while saving the snippet. Please contact Setka Editor support team <a href="mailto:helpme@setka.io">helpme@setka.io</a>.', Plugin::NAME ) );
	}
}
