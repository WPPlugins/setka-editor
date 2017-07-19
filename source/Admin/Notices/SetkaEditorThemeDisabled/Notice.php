<?php
namespace Setka\Editor\Admin\Notices\SetkaEditorThemeDisabled;

use Setka\Editor\Admin;
use Setka\Editor\Plugin;

class Notice extends Admin\Prototypes\Notices\Notice {

	public function __construct(){
		parent::__construct( Plugin::NAME, 'setka-editor-theme-disabled' );
	}

	public function lateConstruct() {
		parent::lateConstruct();

		$content = __( 'This post uses a disabled style. You can safely edit this post but if you change the style you won’t be able to switch it back.', Plugin::NAME );
		$content = '<p>' . $content . '</p>';

		$this->setContent( $content );
		$this->setAttribute( 'class', 'notice setka-editor-notice notice-error setka-editor-notice-error hidden' );
		$this->setAttribute( 'id', Plugin::NAME . '-notice-' . $this->getName() );
	}

	public function isRelevant() {
		return true;
	}
}
