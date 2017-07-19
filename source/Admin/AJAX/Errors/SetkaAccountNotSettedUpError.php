<?php
namespace Setka\Editor\Admin\AJAX\Errors;

use Setka\Editor\Prototypes\Errors\Error;
use Setka\Editor\Plugin;

class SetkaAccountNotSettedUpError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_ajax_setka_account_not_setted_up' );
		$this->setMessage( __( 'Your Setka account not setted up. Please enter your license key.', Plugin::NAME ) );
	}
}
