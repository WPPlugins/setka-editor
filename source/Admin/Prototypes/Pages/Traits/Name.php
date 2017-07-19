<?php
namespace Setka\Editor\Admin\Prototypes\Pages\Traits;

trait Name {

	protected $name;

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = (string)$name;
	}
}
