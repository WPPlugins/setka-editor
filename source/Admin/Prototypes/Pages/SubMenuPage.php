<?php
namespace Setka\Editor\Admin\Prototypes\Pages;

class SubMenuPage extends AbstractPage implements SubMenuPageInterface {

	public function register() {
		$page = add_submenu_page(
			$this->getParentSlug(),
			$this->getPageTitle(),
			$this->getMenuTitle(),
			$this->getCapability(),
			$this->getMenuSlug(),
			array( $this, 'render' )
		);
		if($page) {

			// All functions attached to actions runs in 2 scenarios:
			// 1. Particular settings page loaded (load-$page action).
			// 2. When page settings is saving (admin_action_update action)

			// Fully build the page object
			add_action('load-' . $page, array($this, 'lateConstruct'));
			add_action('admin_action_update', array($this, 'lateConstruct'));

			// Register settings
			add_action('load-' . $page, array($this, 'registerSettings'), 15);
			add_action('admin_action_update', array($this, 'registerSettings'), 15);

			// Register sections
			add_action('load-' . $page, array($this, 'registerSections'), 20);
			add_action('admin_action_update', array($this, 'registerSections'), 20);

			// Register Help Tabs and Help Sidebar
			add_action('load-' . $page, array($this, 'registerHelpTabs'), 30);
			add_action('admin_action_update', array($this, 'registerHelpTabs'), 30);
			add_action('load-' . $page, array($this, 'registerHelpSidebar'), 40);
			add_action('admin_action_update', array($this, 'registerHelpSidebar'), 40);
		}
	}

	public function unRegister() {
		remove_submenu_page($this->getParentSlug(), $this->getMenuSlug());
	}

	public function getURL() {
		return add_query_arg(
			'page',
			$this->getMenuSlug(),
			admin_url('admin.php')
		);
	}
}
