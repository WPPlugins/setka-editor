<?php
namespace Setka\Editor\Admin\AJAX\Errors;

use Setka\Editor\Prototypes\Errors\Error;
use Setka\Editor\Plugin;

class PermissionDeniedError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_ajax_permission_denied' );
		$this->setMessage( __( 'You can’t save snippet. Permission denied.', Plugin::NAME ) );
	}
}
