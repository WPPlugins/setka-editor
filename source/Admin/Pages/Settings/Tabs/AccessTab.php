<?php
namespace Setka\Editor\Admin\Pages\Settings\Tabs;

use Setka\Editor\Admin\Pages\Settings\Loader;
use Setka\Editor\Admin\Prototypes\Pages\Tabs\Tab;
use Setka\Editor\Plugin;

class AccessTab extends Tab {

	public function __construct() {
		$this->setName('settings');
		$this->setTitle(_x('Settings', 'Page tab title', Plugin::NAME));
		if(isset(Loader::$pages['settings'])) {
			$this->setUrl(Loader::$pages['settings']->getURL());
		} else {
			throw new \Exception('Cant find required page.');
		}
	}
}