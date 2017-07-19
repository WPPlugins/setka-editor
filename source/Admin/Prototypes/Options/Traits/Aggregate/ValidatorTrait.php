<?php
namespace Setka\Editor\Admin\Prototypes\Options\Traits\Aggregate;

trait ValidatorTrait {

	public function getValidator() {
		return $this->getParent()->getValidator();
	}
}
