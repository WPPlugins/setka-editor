<?php
namespace Setka\Editor\Admin\Notices\SetkaEditorSaveSnippet;

use Setka\Editor\Admin;
use Setka\Editor\Plugin;

/**
 * @deprecated For now we using native windows.alert() in Browser for this type of Notices.
 */
class Notice extends Admin\Prototypes\Notices\Notice {

	public function __construct(){
		parent::__construct( Plugin::NAME, 'setka-editor-save-snippet' );
	}

	public function lateConstruct() {
		parent::lateConstruct();

		$message = __( '<span class="spinner" style="visibility: visible;float: left;margin: 0 5px 0 0;"></span> Start saving snippet. Make AJAX-request.', Plugin::NAME );
		$content = '<p id="'. $this->getPrefixedName() . '-save" class="hidden">' . $message . '</p>';

		$message = __( 'Error on doing AJAX request.', Plugin::NAME );
		$content.= '<p id="'. $this->getPrefixedName() . '-error" class="hidden">' . $message . '</p>';

		$message = __( 'Successfully saved snippet via API.', Plugin::NAME );
		$content.= '<p id="'. $this->getPrefixedName() . '-success" class="hidden">' . $message . '</p>';

		$this->setContent( $content );
		$this->setAttribute( 'class', 'notice setka-editor-notice notice-error setka-editor-notice-error hidden' );
		$this->setAttribute( 'id', $this->getPrefixedName() );
	}

	public function isRelevant() {
		// TODO: improve this
		return true;
	}
}
