<?php
namespace Setka\Editor\Service;

use Setka\Editor\Plugin;

class Editor {

	/**
	 * Just one function to enqueue all required scripts and styles for our Editor
	 * in the proper order.
	 *
	 * @since 0.0.1
	 */
	public static function enqueue_editor_script_styles() {
		wp_enqueue_script( Plugin::NAME . '-editor' );
		wp_enqueue_style( Plugin::NAME . '-editor' );

		wp_enqueue_style( Plugin::NAME . '-theme-resources' );
	}
}
