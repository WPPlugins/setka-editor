<?php
namespace Setka\Editor\Admin\Prototypes\Pages;

interface MenuPageInterface extends PageInterface {

	/**
	 * @return string
	 */
	public function getIcon();

	/**
	 * @param string|null $icon
	 * @return $this For chain calls.
	 */
	public function setIcon($icon);

	/**
	 * @return int|null
	 */
	public function getPosition();

	/**
	 * @param int|null $position
	 * @return $this For chain calls.
	 */
	public function setPosition($position);
}
