<?php
namespace Setka\Editor\Admin\Pages\Settings\Tabs;

use Setka\Editor\Admin\Pages\Settings\Loader;
use Setka\Editor\Admin\Prototypes\Pages\Tabs\Tab;
use Setka\Editor\Plugin;

class AccountTab extends Tab {

	public function __construct() {
		$this->setName('account');
		$this->setTitle(__('Account', Plugin::NAME));
		if(isset(Loader::$pages[Plugin::NAME])) {
			$this->setUrl(Loader::$pages[Plugin::NAME]->getURL());
		} else {
			throw new \Exception('Cant find required page.');
		}
	}
}
