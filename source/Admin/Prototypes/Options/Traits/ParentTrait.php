<?php
namespace Setka\Editor\Admin\Prototypes\Options\Traits;

trait ParentTrait {

	/**
	 * @var \Setka\Editor\Admin\Prototypes\Options\NodeInterface
	 */
	protected $parent;

	public function getParent() {
		return $this->parent;
	}

	public function setParent( $parent ) {
		$this->parent = $parent;
	}
}
