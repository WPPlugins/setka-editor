<?php
namespace Setka\Editor\Admin\Prototypes\Pages\HelpSidebars;

interface HelpSidebarInterface {

	public function register();

	public function setContent($content);
	public function getContent();
	public function buildContent();
}
