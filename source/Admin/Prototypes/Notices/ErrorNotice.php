<?php
namespace Setka\Editor\Admin\Prototypes\Notices;

class ErrorNotice extends Notice {

	public function lateConstruct() {
		parent::lateConstruct();
		$this->setAttribute('class', 'notice setka-editor-notice notice-error setka-editor-notice-error');
	}
}
