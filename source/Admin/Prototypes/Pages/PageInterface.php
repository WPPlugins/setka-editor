<?php
namespace Setka\Editor\Admin\Prototypes\Pages;

use Setka\Editor\Admin\Prototypes\Options;
use Setka\Editor\Admin\Prototypes\Pages\Views\PageViewInterface;

interface PageInterface {

	public function lateConstruct();

	public function register();
	public function unRegister();

	public function registerHelpTabs();

	public function registerHelpSidebar();

	public function registerSettings();

	public function getName();
	public function setName($name);

	public function getOption();
	public function setOption(Options\OptionInterface $option);

	public function getPageTitle();
	public function setPageTitle($title);

	public function getMenuTitle();
	public function setMenuTitle($title);

	public function getCapability();
	public function setCapability($cap);

	public function getMenuSlug();
	public function setMenuSlug($menuSlug);

	public function getHelpTabs();

	/**
	 * Add Help Tabs to this page.
	 *
	 * @param $helpTab HelpTabs\HelpTabInterface Help tab instance.
	 *
	 * @return $this For chain calls.
	 */
	public function addHelpTab(HelpTabs\HelpTabInterface $helpTab);

	public function getHelpSidebar();

	/**
	 * Set Help Sidebar for this page.
	 *
	 * @param $helpSidebar HelpSidebars\HelpSidebarInterface Help Sidebar instance of HelpSidebarInterface.
	 *
	 * @return $this For chain calls.
	 */
	public function setHelpSidebar(HelpSidebars\HelpSidebarInterface $helpSidebar);

	public function getOptionGroup();
	public function setOptionGroup($optionGroup);

	public function getView();
	public function setView(PageViewInterface $view);

	public function getDefaultView();
	public function setDefaultView($view);

	public function getSections();
	public function addSection(Sections\SectionInterface $section);
	public function registerSections();

	public function getURL();

	public function render();

	/**
	 * @return Tabs\TabsInterface
	 */
	public function getTabs();

	/**
	 * @param Tabs\TabsInterface $tabs
	 */
	public function setTabs(Tabs\TabsInterface $tabs);

	/**
	 * @return Tabs\TabsInterface|null
	 */
	public function buildTabs();

	public function setParentPage(MenuPageInterface $page);
	public function getParentPage();
}
