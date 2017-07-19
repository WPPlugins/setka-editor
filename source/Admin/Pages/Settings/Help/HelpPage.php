<?php
namespace Setka\Editor\Admin\Pages\Settings\Help;

use Setka\Editor\Admin\Prototypes\Pages;
use Setka\Editor\Plugin;

class HelpPage extends Pages\SubMenuPage {

	public function __construct() {
		$this->setParentSlug(Plugin::NAME);
		$this->setPageTitle(__('Help', Plugin::NAME));
		$this->setMenuTitle($this->getPageTitle());
		$this->setCapability('manage_options');
		$this->setMenuSlug(Plugin::NAME . '-help');

		$this->setName('help');
	}
}
