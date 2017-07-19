<?php
namespace Setka\Editor\Admin\Prototypes\Pages\HelpSidebars;

class HelpSidebar implements HelpSidebarInterface {

	protected $content = '';

	public function register() {
		$screen = get_current_screen();
		$screen->set_help_sidebar($this->content ? $this->content : $this->buildContent());
	}

	public function setContent( $content ) {
		$this->content = $content;
		return $this;
	}

	public function getContent() {
		return $this->content;
	}

	public function buildContent() {
		return '';
	}
}
