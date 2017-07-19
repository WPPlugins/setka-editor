<?php
namespace Setka\Editor\Admin\Notices\SignUpError;

use Setka\Editor\Admin;
use Setka\Editor\Plugin;

class SignUpErrorNotice extends Admin\Prototypes\Notices\ErrorNotice {

	public function __construct(){
		parent::__construct( Plugin::NAME, 'sign_up_error' );
	}

	public function lateConstruct() {
		parent::lateConstruct();
		$this->setContent( '<p>' . __('Oops… Couldn’t connect to Setka Editor server to complete your registration. Please contact Setka Editor support team <a href="mailto:helpme@setka.io">helpme@setka.io</a>.', Plugin::NAME) . '</p>' );
	}
}
