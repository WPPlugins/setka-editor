<?php
namespace Setka\Editor\Admin\Notices\SettingsSavedSuccessfully;

use Setka\Editor\Admin;
use Setka\Editor\Plugin;

class SettingsSavedSuccessfullyNotice extends Admin\Prototypes\Notices\Notice {

	public function __construct() {
		parent::__construct(Plugin::NAME, 'settings_saved_successfully');
	}

	public function lateConstruct() {
		parent::lateConstruct();

		$this->setContent( '<p>' . __( 'Settings saved successfully.', Plugin::NAME ) . '</p>' );
		$this->setAttribute( 'class', 'notice setka-editor-notice notice-success setka-editor-notice-sucess' );
	}
}
