<?php
namespace Setka\Editor\Admin\Pages\Settings\Tabs;

use Setka\Editor\Admin\Pages\Settings\Loader;
use Setka\Editor\Admin\Prototypes\Pages\Tabs\Tab;
use Setka\Editor\Plugin;

class GeneralTab extends Tab {

	public function __construct() {
		$this->setName('general');
		$this->setTitle(__('General', Plugin::NAME));
		if(isset(Loader::$pages['general-new'])) {
			$this->setUrl(Loader::$pages['general-new']->getURL());
		} else {
			throw new \Exception('Cant find required page.');
		}
	}
}
