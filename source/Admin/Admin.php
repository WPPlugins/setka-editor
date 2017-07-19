<?php
namespace Setka\Editor\Admin;

use Setka\Editor\Service\FileSystemCache;

class Admin {

	/**
	 * Runs on WordPress /wp-admin/ pages.
	 *
	 * Called from \Setka\Editor\Plugin::run().
	 *
	 * @since 0.0.1
	 *
	 * @see \Setka\Editor\Plugin::run()
	 */
	public static function run() {

		add_action('admin_init', array(FileSystemCache::class, 'run'));

		// Save content from POST request
		add_action( 'save_post', array( '\Setka\Editor\Admin\Service\SavePost', 'save_post' ), 10, 3 );

		// Plugin settings
		//\Setka\Editor\Admin\Settings\Loader::init();
		Pages\Settings\Loader::init();

		add_action( 'admin_enqueue_scripts', array( '\Setka\Editor\Service\ScriptStyles', 'register' ) );
		add_action( 'admin_enqueue_scripts', array( '\Setka\Editor\Service\ScriptStyles', 'register_theme_resources' ), 1000 );
		// Prepare translations for the scripts
		add_action( 'admin_enqueue_scripts', array( '\Setka\Editor\Admin\Service\Js\Translations', 'prepare_translations' ) );
		// Register CSS & JS files only for /wp-admin/ pages
		add_action( 'admin_enqueue_scripts', array( '\Setka\Editor\Admin\Service\ScriptStyles', 'register' ) );

		// Enqueue editor CSS & JS
		add_action( 'admin_enqueue_scripts', array( '\Setka\Editor\Admin\Pages\All', 'admin_enqueue_scripts' ), 1100 );

		add_action( 'load-post.php', array( '\Setka\Editor\Admin\Pages\Post', 'load_page' ) );
		add_action( 'load-post-new.php', array( '\Setka\Editor\Admin\Pages\Post', 'load_page' ) );

		// Action links on /wp-admin/plugins.php
		add_filter( 'plugin_action_links_' . plugin_basename( $GLOBALS['WPSetkaEditorPlugin']->get_path() ), array( '\Setka\Editor\Admin\Pages\Plugins', 'add_action_links' ) );

		// WordPress post auto save
		add_filter( 'heartbeat_received', array( '\Setka\Editor\Admin\Service\SavePost', 'heartbeat_received' ), 10, 2 );

		// Admin Notices
		add_action( 'admin_notices', array( 'Setka\Editor\Admin\Notices\Loader', 'register' ) );
		add_action( 'admin_notices', array( 'Setka\Editor\Admin\Notices\Loader', 'render' ) );

		/**
		 * Catch Setka API requests (webhooks).
		 */
		add_action( 'admin_init', array( '\Setka\Editor\Admin\Service\Webhooks', 'run' ) );

		// AJAX requests
		add_action( 'admin_init', array( '\Setka\Editor\Admin\AJAX\Loader', 'run' ) );

		add_filter('wp_kses_allowed_html', array('Setka\Editor\Admin\Service\Kses', 'allowedHTML'), 10, 2);

		//add_action( 'wp_editor_expand', array( '\Setka\Editor\Admin\Service\EditorExpand', 'wp_editor_expand' ), 10, 1 );
	}
}
