<?php
namespace Setka\Editor\Admin\Pages\Settings\UpgradeToPremium;

use Setka\Editor\Admin\Prototypes\Pages;
use Setka\Editor\Plugin;

class UpgradeToPremium extends Pages\SubMenuPage {

	public function __construct() {
		$this->setParentSlug(Plugin::NAME);
		$this->setPageTitle(__( 'Upgrade to Premium', Plugin::NAME ));
		$this->setMenuTitle($this->getPageTitle());
		$this->setCapability('manage_options');
		$this->setMenuSlug(Plugin::NAME . '-upgrade-to-premium');

		$this->setName('upgrade-to-premium');
	}
}