<?php
namespace Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\Positions\Variations;

use Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\Positions\Position;
use Setka\Editor\Plugin;

class Developer extends Position {

	public function __construct() {
		$this->setTitle(__('Developer', Plugin::NAME));
		$this->setValue('persona_1');
	}
}
