<?php
namespace Setka\Editor\Admin\Pages\Settings\General;

use Setka\Editor\Admin\Pages\Settings\Tabs\AccessTab;
use Setka\Editor\Admin\Pages\Settings\Tabs\GeneralTab;
use Setka\Editor\Admin\Pages\Settings\Tabs\StartTab;
use Setka\Editor\Admin\Prototypes\Pages;
use Setka\Editor\Admin\Prototypes\Pages\Tabs\Tabs;
use Setka\Editor\Plugin;
use Setka\Editor\Admin\Pages\Settings\Loader;

class GeneralPage extends Pages\SubMenuPage {

	public function __construct() {
		$this->setParentSlug(Plugin::NAME . '-sign-up');
		$this->setPageTitle(__( 'General', Plugin::NAME ));
		$this->setMenuTitle($this->getPageTitle());
		$this->setCapability('manage_options');
		$this->setMenuSlug(Plugin::NAME . '-general-new');

		$this->setName('general-new');
	}

	public function lateConstruct() {
		$this->setTabs($this->buildTabs());
		$this->prepareTabs();
		$attributes = array(
			'page' => $this,
			//'form' => $form = Loader::$symfony_form_factory->createNamed(Plugin::_NAME_, 'Setka\Editor\Admin\Pages\Settings\SignIn\SignInType')->createView(),
			'translations' => array(
				'post_production_header' => __('Post production workflow settings', Plugin::NAME),
				'setup_styles' => __('Setup Styles', Plugin::NAME),
				'setup_styles_description' => __('Set and modify your styles by choosing fonts, colors and grids for consistent looking posts.', Plugin::NAME),
				'grid_systems' => __('Setup Grid Systems', Plugin::NAME),
				'grid_systems_description' => __('Create custom grid systems to create more flexible and impressive post designs.', Plugin::NAME),
				'pro' => __('Upgrade to PRO', Plugin::NAME),
				'pro_description' => __('Unleash all the power of Setka Editor.', Plugin::NAME)
			)
		);
		$view = new Pages\Views\TwigPageView();
		$view->setTemplate('admin/settings/general/page.html.twig');
		$view->setContext($attributes);
		$this->setView($view);
	}

	public function buildTabs() {
		$tabs = new Tabs();

		$tab = new StartTab();
		$tabs->addTab($tab);

		$tab = new GeneralTab();
		$tab->markActive();
		$tabs->addTab($tab);

		$tab = new AccessTab();
		$tabs->addTab($tab);

		return $tabs;
	}
}
