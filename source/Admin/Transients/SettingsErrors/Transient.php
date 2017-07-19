<?php
namespace Setka\Editor\Admin\Transients\SettingsErrors;

use Setka\Editor\Admin\Prototypes\Transients;

/**
 * @deprecated No longer used. This file is stored only for plugin uninstaller.
 */
class Transient extends Transients\Transient {

	public function __construct() {
		$this->setName( 'settings_errors' );
		$this->setExpiration( 30 );
	}
}
