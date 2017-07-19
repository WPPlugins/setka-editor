<?php
namespace Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\Positions\Variations;

use Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\Positions\Position;
use Setka\Editor\Plugin;

class Editor extends Position {

	public function __construct() {
		$this->setTitle(__('Editor', Plugin::NAME));
		$this->setValue('persona_2');
	}
}
