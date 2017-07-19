<?php
namespace Setka\Editor\Service;

class FileSystemCache {

	/**
	 * This function setup SETKA_EDITOR_CACHE_DIR const which using for locating cache folder.
	 *
	 * You can override this value by defining your own value in wp-config.php.
	 *
	 * We are playing a little bit dirty here. Since cache folder exists - there is no guarantee
	 * that cache/twig (for example) folder is writable but this is not very common scenario
	 * (and usually if this happens the developer know that he's doing).
	 *
	 * Also we are don't care about requiring WordPress `file.php` file with WP_Filesystem func.
	 * Because we are only loading this on wp-admin pages.
	 *
	 * Read more in docs/plugin-configuration/README.md
	 */
	public static function run() {
		if(defined('SETKA_EDITOR_CACHE_DIR'))
			return;

		if(function_exists('WP_Filesystem')) {
			WP_Filesystem();

			global $wp_filesystem;

			if(is_a($wp_filesystem, '\WP_Filesystem_Direct')) {
				/**
				 * @var $wp_filesystem \WP_Filesystem_Direct
				 */
				$pluginPath = PathsAndUrls::get_plugin_dir_path();
				if($wp_filesystem->is_writable($pluginPath)) {
					define('SETKA_EDITOR_CACHE_DIR', $pluginPath . 'cache/');
					return;
				}
			}
		}
		define('SETKA_EDITOR_CACHE_DIR', false);
	}
}
