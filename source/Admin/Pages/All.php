<?php
namespace Setka\Editor\Admin\Pages;

use Setka\Editor\Plugin;

class All {

	/**
	 * Enqueue scripts & styles for all /wp-admin/ pages.
	 *
	 * @since 0.0.2
	 *
	 * @see \Setka\Editor\Admin\Admin::run()
	 */
	public static function admin_enqueue_scripts() {
		// Styles for all pages.
		wp_enqueue_style( Plugin::NAME . '-wp-admin-main' );
	}
}
