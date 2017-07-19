<?php
namespace Setka\Editor\Admin\Prototypes\Pages\HelpTabs;

interface HelpTabInterface {

	public function register();
	public function unRegister();

	public function setId($id);
	public function getId();

	public function setTitle($title);
	public function getTitle();
	public function buildTitle();

	public function setContent($content);
	public function getContent();
	public function buildContent();
}
