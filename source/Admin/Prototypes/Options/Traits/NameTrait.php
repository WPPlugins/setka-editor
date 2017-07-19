<?php
namespace Setka\Editor\Admin\Prototypes\Options\Traits;

trait NameTrait {
	/**
	 * @var string The option name which used to save option value in DB.
	 */
	protected $name;

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}
}
