<?php
namespace Setka\Editor\Admin\Pages\Settings\Tabs;

use Setka\Editor\Admin\Pages\Settings\Loader;
use Setka\Editor\Admin\Prototypes\Pages\Tabs\Tab;
use Setka\Editor\Plugin;

class StartTab extends Tab {

	public function __construct() {
		$this->setName('sign-up');
		$this->setTitle(__('Start', Plugin::NAME));
		if(isset(Loader::$pages[Plugin::NAME])) {
			$this->setUrl(Loader::$pages[Plugin::NAME]->getURL());
		} else {
			throw new \Exception('Cant find required page.');
		}
	}
}
