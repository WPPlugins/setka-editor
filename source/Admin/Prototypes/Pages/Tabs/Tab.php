<?php
namespace Setka\Editor\Admin\Prototypes\Pages\Tabs;

class Tab implements TabInterface {

	/**
	 * @var string Unique Tab name.
	 */
	protected $name;

	/**
	 * @var string Visible title (text) of this tab.
	 */
	protected $title;

	/**
	 * @var string An url for this tab.
	 */
	protected $url;

	/**
	 * @var bool Is it tab active or not.
	 */
	protected $active;

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * @return bool
	 */
	public function isActive() {
		return $this->active;
	}

	/**
	 * @return $this For chain calls.
	 */
	public function markActive() {
		$this->active = true;
		return $this;
	}

	/**
	 * @return $this For chain calls.
	 */
	public function markUnActive() {
		$this->active = false;
		return $this;
	}
}
