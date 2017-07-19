<?php
namespace Setka\Editor\Admin\Prototypes\Options\Traits;

trait GroupTrait {

	/**
	 * @var string The group name which used in register_setting() function.
	 */
	protected $group;

	public function getGroup() {
		return $this->group;
	}

	public function setGroup($group) {
		$this->group = $group;
	}
}
