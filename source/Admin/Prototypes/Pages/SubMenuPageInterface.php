<?php
namespace Setka\Editor\Admin\Prototypes\Pages;

interface SubMenuPageInterface extends PageInterface {

	public function setParentSlug($slug);
	public function getParentSlug();
}
