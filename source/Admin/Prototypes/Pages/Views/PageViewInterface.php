<?php
namespace Setka\Editor\Admin\Prototypes\Pages\Views;

use Setka\Editor\Admin\Prototypes;

interface PageViewInterface {

	public function render( Prototypes\Pages\PageInterface $element );
}
