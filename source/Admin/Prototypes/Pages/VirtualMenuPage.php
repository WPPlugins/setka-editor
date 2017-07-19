<?php
namespace Setka\Editor\Admin\Prototypes\Pages;

class VirtualMenuPage extends MenuPage implements VirtualMenuPageInterface {

	/**
	 * @var MenuPageInterface Page instance which is used by this virtual page.
	 */
	protected $virtualPage;

	/**
	 * @return MenuPageInterface
	 */
	public function getVirtualPage() {
		return $this->virtualPage;
	}

	/**
	 * @param MenuPageInterface $virtualPage
	 */
	public function setVirtualPage(MenuPageInterface $virtualPage) {
		$this->virtualPage = $virtualPage;
	}

	public function render() {
		$this->getVirtualPage()->render();
	}

	public function lateConstruct() {
		$this->getVirtualPage()->lateConstruct();
	}

	public function buildTabs() {
		return $this->getVirtualPage()->buildTabs();
	}
}
