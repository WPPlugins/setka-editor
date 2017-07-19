<?php
namespace Setka\Editor\Admin\Prototypes\Pages\Tabs;

interface TabInterface {

	public function getName();

	public function setName($name);

	public function getTitle();

	public function setTitle($title);

	public function getUrl();

	public function setUrl($url);

	public function isActive();

	public function markActive();

	public function markUnActive();
}
