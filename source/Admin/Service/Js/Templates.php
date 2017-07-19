<?php
namespace Setka\Editor\Admin\Service\Js;

use Setka\Editor\Plugin;

class Templates {

	/**
	 * Render JavaScript templates.
	 *
	 * @since 0.0.1
	 */
	public static function render_templates() {
		?>
		<script type="text/html" id="<?php Plugin::NAME ?>-tmpl-media-frame">
			<div class="media-frame-menu"></div>
			<div class="media-frame-title"></div>
			<div class="media-frame-router"></div>
			<div class="media-frame-content"></div>
			<div class="media-frame-toolbar"></div>
			<div class="media-frame-uploader"></div>
		</script>
		<?php
	}
}
