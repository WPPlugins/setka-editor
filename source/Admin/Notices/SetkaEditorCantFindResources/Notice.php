<?php
namespace Setka\Editor\Admin\Notices\SetkaEditorCantFindResources;

use Setka\Editor\Admin;
use Setka\Editor\Plugin;

class Notice extends Admin\Prototypes\Notices\Notice {

	public function __construct(){
		parent::__construct( Plugin::NAME, 'setka-editor-cant-find-resources' );
	}

	public function lateConstruct() {
		parent::lateConstruct();
		$content = __( 'Post style was removed from Style Manager or you’ve changed your license key. Please contact <a href="https://editor.setka.io/support" target="_blank">Setka Editor team</a>.', Plugin::NAME );
		$content = '<p>' . $content . '</p>';

		$this->setContent( $content );
		$this->setAttribute( 'class', 'notice setka-editor-notice notice-error setka-editor-notice-error hidden' );
		$this->setAttribute( 'id', Plugin::NAME . '-notice-' . $this->getName() );
	}

	public function isRelevant() {
		return true;
	}
}
