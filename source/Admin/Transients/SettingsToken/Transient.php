<?php
namespace Setka\Editor\Admin\Transients\SettingsToken;

use Setka\Editor\Admin\Prototypes\Transients;
use Setka\Editor\Plugin;

/**
 * @deprecated No longer used. This file is stored only for plugin uninstaller.
 */
class Transient extends Transients\Transient {

	public function __construct() {
		$this->setName( Plugin::_NAME_ . '_settings_token' );
		$this->setExpiration( 30 );
	}
}
