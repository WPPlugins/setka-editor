<?php
namespace Setka\Editor\Admin\Pages;

use Setka\Editor\Plugin;

class Post {

	/**
	 * Fired on load-post.php and load-post-new.php actions.
	 *
	 * @since 0.0.2
	 */
	public static function load_page() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ), 1100 );
	}

	/**
	 * Enqueue editor scripts & styles + initializer.
	 *
	 * @since 0.0.2
	 */
	public static function admin_enqueue_scripts() {
		// Grid Editor CSS & JS
		if( \Setka\Editor\Admin\Service\Editor::enable_editor() ) {

			// Single function enqueue editor styles & scripts
			\Setka\Editor\Service\Editor::enqueue_editor_script_styles();

			// Editor Initializer for /wp-admin/ pages
			wp_enqueue_script( Plugin::NAME . '-wp-admin-editor-adapter-initializer' );

			//wp_enqueue_style(  Plugin::NAME . '-wp-admin-editor-wp-styles' );

			wp_enqueue_style('wp-pointer');
		}
	}
}
