<?php
namespace Setka\Editor\Admin\Prototypes\Pages\HelpTabs;

abstract class HelpTab implements HelpTabInterface {

	protected $id = '';

	protected $title = '';

	protected $content = '';

	public function register() {
		$screen = get_current_screen();
		$screen->add_help_tab(array(
			'id'	  => $this->getId(),
			'title'	  => $this->title ? $this->title : $this->buildTitle(),
			'content' => $this->content ? $this->content : $this->buildContent()
		));
	}

	public function unRegister() {
		$screen = get_current_screen();
		$screen->remove_help_tab($this->getId());
	}

	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	public function getId() {
		return $this->id;
	}

	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	public function getTitle() {
		return $this->title;
	}

	public function buildTitle() {
		return '';
	}

	public function setContent($content) {
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
