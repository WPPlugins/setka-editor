<?php
namespace Setka\Editor\Admin\Prototypes\Pages;

use Setka\Editor\Admin\Prototypes\Options\OptionInterface;
use Setka\Editor\Admin\Prototypes\Pages\Views\PageViewInterface;
use Setka\Editor\Admin\Prototypes\Pages\Views\SubMenuPageView;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractPage implements PageInterface {

	/**
	 * @var string Name of the page.
	 */
	protected $name;

	/**
	 * @var \Setka\Editor\Admin\Prototypes\Options\OptionInterface
	 */
	protected $option;

	/**
	 * @var string
	 */
	protected $parentSlug;

	/**
	 * @var MenuPageInterface
	 */
	protected $parentPage;

	/**
	 * @var string
	 */
	protected $pageTitle;

	/**
	 * @var string
	 */
	protected $menuTitle;

	/**
	 * @var string
	 */
	protected $capability;

	protected $menuSlug;

	/**
	 * @var HelpTabs\HelpTabInterface[]
	 */
	protected $helpTabs = array();

	/**
	 * @var HelpSidebars\HelpSidebarInterface
	 */
	protected $helpSidebar;

	/**
	 * @var string
	 */
	protected $optionGroup;

	/**
	 * @var PageViewInterface
	 */
	protected $view;

	/**
	 * @var string
	 */
	protected $defaultView = SubMenuPageView::class;

	protected $sections;

	/**
	 * @var Tabs\TabsInterface Stack of tabs on this page.
	 */
	protected $tabs;

	/**
	 * @var Request Current HTTP request.
	 */
	protected $request;

	/**
	 * This method called only if user allowed to see this page (based on capability).
	 *
	 * Validation happens in wp-admin/includes/menu.php
	 * 1. admin.php:138
	 * 2. require(ABSPATH . 'wp-admin/menu.php') (138 line)
	 * 3. require_once(ABSPATH . 'wp-admin/includes/menu.php') (282 line).
	 * 4. if ( !user_can_access_admin_page() ) (333 line).
	 */
	public function lateConstruct() {}

	public function registerHelpTabs() {
		foreach($this->helpTabs as $tab) {
			$tab->register();
		}
	}

	public function registerHelpSidebar() {
		if(is_object($this->helpSidebar)) {
			$this->helpSidebar->register();
		}
	}

	public function registerSettings() {
		// Page options
		$option = $this->getOption();
		if($option) {
			$option->register();
		}
		unset($option);

		// Page sections
		$sections = $this->getSections();
		if(!empty( $sections)) {
			foreach($sections as $section) {

				// Section have fields with their own Options
				$fields = $section->getNodes();
				if(!empty($fields)) {
					foreach($fields as $field) {

						// If field have option register it.
						$option = $field->getOption();
						if($option) {
							$option->register();
						}
					}
				}
			}
		}
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = (string)$name;
	}

	public function getOption() {
		return $this->option;
	}

	public function setOption(OptionInterface $option) {
		$this->option = $option;
	}

	public function setParentSlug($slug) {
		$this->parentSlug = $slug;
	}

	public function getParentSlug() {
		return $this->parentSlug;
	}

	public function setPageTitle($title) {
		$this->pageTitle = $title;
	}

	public function getPageTitle() {
		return $this->pageTitle;
	}

	public function setMenuTitle($title) {
		$this->menuTitle = $title;
	}

	public function getMenuTitle() {
		return $this->menuTitle;
	}

	public function setCapability($cap) {
		$this->capability = $cap;
	}

	public function getCapability() {
		return $this->capability;
	}

	public function setMenuSlug($menuSlug) {
		$this->menuSlug = $menuSlug;
	}

	public function getMenuSlug() {
		return $this->menuSlug;
	}

	/**
	 * @inheritdoc
	 */
	public function addHelpTab(HelpTabs\HelpTabInterface $helpTab) {
		$this->helpTabs[$helpTab->getId()] = $helpTab;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getHelpTabs() {
		return $this->helpTabs;
	}

	public function setHelpTabs(array $helpTabs) {
		$this->helpTabs = $helpTabs;
		return $this;
	}

	public function setHelpSidebar(HelpSidebars\HelpSidebarInterface $helpSidebar) {
		$this->helpSidebar = $helpSidebar;
	}

	public function getHelpSidebar() {
		return $this->helpSidebar;
	}

	public function setOptionGroup($optionGroup) {
		$this->optionGroup = $optionGroup;
	}

	public function getOptionGroup() {
		return $this->optionGroup;
	}

	public function getView() {
		return $this->view;
	}

	public function setView(PageViewInterface $view) {
		$this->view = $view;
	}

	/**
	 * @return string
	 */
	public function getDefaultView() {
		return $this->defaultView;
	}

	/**
	 * @param string $defaultView
	 */
	public function setDefaultView($defaultView) {
		$this->defaultView = $defaultView;
	}

	public function getSections() {
		return $this->sections;
	}

	public function addSection(Sections\SectionInterface $section) {
		$this->sections[$section->getName()] = $section;
	}

	public function registerSections() {
		$sections = $this->getSections();

		if( !empty( $sections ) ) {
			foreach( $sections as $section ) {
				$section->register();
			}
		}
	}

	public function render() {
		if(!$this->getView()) {
			$this->setView(new $this->defaultView());
		}
		$this->getView()->render($this);
	}

	/**
	 * @return Tabs\TabsInterface
	 */
	public function getTabs() {
		return $this->tabs;
	}

	/**
	 * @param Tabs\TabsInterface $tabs
	 */
	public function setTabs(Tabs\TabsInterface $tabs) {
		$this->tabs = $tabs;
	}

	public function buildTabs() {}

	/**
	 * This method find current tab and mark it as active.
	 */
	public function prepareTabs() {
		$tabs = $this->getTabs();
		if($tabs) {
			$tab = $tabs->getTab($this->getName());
			if($tab) {
				$tab->markActive();
			}
		}
	}

	public function enqueueScriptStyles() {}

	public function setParentPage(MenuPageInterface $page) {
		$this->parentPage = $page;
	}

	/**
	 * @return MenuPageInterface
	 */
	public function getParentPage() {
		return $this->parentPage;
	}

	/**
	 * @return Request
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * @param Request $request
	 */
	public function setRequest($request) {
		$this->request = $request;
	}
}
