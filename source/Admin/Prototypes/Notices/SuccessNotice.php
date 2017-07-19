<?php
namespace Setka\Editor\Admin\Prototypes\Notices;

class SuccessNotice extends Notice {

	public function lateConstruct() {
		parent::lateConstruct();
		$this->setAttribute('class', 'notice setka-editor-notice notice-success setka-editor-notice-success');
	}
}
