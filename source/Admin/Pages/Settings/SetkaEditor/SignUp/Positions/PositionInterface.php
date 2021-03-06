<?php
namespace Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\Positions;

interface PositionInterface {

	/**
	 * @return string
	 */
	public function getTitle();

	/**
	 * @param string $title
	 *
	 * @return $this For chain calls.
	 */
	public function setTitle($title);

	/**
	 * @return string
	 */
	public function getValue();

	/**
	 * @param string $value
	 *
	 * @return $this For chain calls.
	 */
	public function setValue($value);
}
