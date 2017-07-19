<?php
namespace Setka\Editor\Admin\Prototypes\Pages\Tabs;

class Tabs implements TabsInterface {

	protected $position = 0;

	/**
	 * @var TabInterface[]
	 */
	protected $tabs = array();

	public function getTabs() {
		return $this->tabs;
	}

	public function addTab(TabInterface $tab) {
		$this->tabs[] = $tab;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getTab($name) {
		$tabPosition = $this->hasTab($name);
		if(is_int($tabPosition)) {
			return $this->tabs[$tabPosition];
		}
		return false;
	}

	public function hasTab($name) {
		foreach($this->tabs as $key => $value)
			if($value->getName() === $name) {
				return $key;
			}
		return false;
	}

	public function current() {
		return $this->tabs[$this->position];
	}

	public function next() {
		++$this->position;
	}

	public function key() {
		return $this->position;
	}

	public function valid() {
		return isset($this->tabs[$this->position]);
	}

	public function rewind() {
		$this->position = 0;
	}
}
