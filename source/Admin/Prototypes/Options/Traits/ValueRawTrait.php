<?php
namespace Setka\Editor\Admin\Prototypes\Options\Traits;

trait ValueRawTrait {

	public function getValueRaw() {
		/**
		 * @var $this \Setka\Editor\Admin\Prototypes\Options\NodeInterface
		 */
		return get_option($this->getName());
	}
}
