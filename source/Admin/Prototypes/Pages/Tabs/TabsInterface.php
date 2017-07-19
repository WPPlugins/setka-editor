<?php
namespace Setka\Editor\Admin\Prototypes\Pages\Tabs;

interface TabsInterface extends \Iterator {

	public function getTabs();

	public function addTab(TabInterface $tab);

	/**
	 * Returns single tab instance if they exists.
	 *
	 * @param $name string Name of the tab.
	 *
	 * @return bool|TabInterface
	 */
	public function getTab($name);

	public function hasTab($name);
}
