<?php
namespace Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\Positions\Variations;

use Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\Positions\Position;
use Setka\Editor\Plugin;

class MarketingProfessional extends Position {

	public function __construct() {
		$this->setTitle(__('Marketing professional', Plugin::NAME));
		$this->setValue('persona_4');
	}
}
