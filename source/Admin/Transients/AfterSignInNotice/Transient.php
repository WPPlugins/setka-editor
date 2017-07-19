<?php
namespace Setka\Editor\Admin\Transients\AfterSignInNotice;

use Setka\Editor\Admin\Prototypes\Transients;
use Setka\Editor\Plugin;

/**
 * When this transient set to '1' — After Sign In notice is shows up.
 *
 * @since 0.2.0
 */
class Transient extends Transients\Transient {

	public function __construct() {
		$this->setName( Plugin::_NAME_ . '_after_sign_in_notice' );
		$this->setExpiration( 30 );
	}
}
