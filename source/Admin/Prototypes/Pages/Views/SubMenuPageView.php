<?php
namespace Setka\Editor\Admin\Prototypes\Pages\Views;

use Setka\Editor\Admin\Prototypes\Pages\PageInterface;

class SubMenuPageView implements SubMenuPageViewInterface {

	public function render( PageInterface $element ) {
		$this->page( $element );
	}

	protected function page( PageInterface $element ) {
		?><div class="wrap">
		<h2><?php echo $element->getPageTitle(); ?></h2>
		<form action="options.php" method="post">
			<?php
			settings_fields( $element->getOptionGroup() );
			do_settings_sections( $element->getMenuSlug() );
			submit_button();
			?>
		</form>
		</div><?php
	}
}
