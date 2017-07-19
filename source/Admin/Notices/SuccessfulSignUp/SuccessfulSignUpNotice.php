<?php
namespace Setka\Editor\Admin\Notices\SuccessfulSignUp;

use Setka\Editor\Admin\Prototypes\Notices\SuccessNotice;
use Setka\Editor\Plugin;

class SuccessfulSignUpNotice extends SuccessNotice {

	public function __construct(){
		parent::__construct(Plugin::NAME, 'successful_sign_up');
	}

	public function lateConstruct() {
		parent::lateConstruct();
		$this->setContent('<p>' . __('Registration completed. We sent you an email with license key that you need to enter to start the plugin.', Plugin::NAME) . '</p>');
	}
}
