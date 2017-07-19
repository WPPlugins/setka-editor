<?php
namespace Setka\Editor\Admin\Service;

use Setka\Editor\Plugin;
use Setka\Editor\Service\PathsAndUrls;

class ScriptStyles {

	/**
	 * Register CSS & JS files wich used only on wp-admin pages.
	 *
	 * @since 0.0.1
	 *
	 * @see \Setka\Editor\Admin\Admin::run()
	 */
	public static function register() {
		$prefix = Plugin::NAME . '-wp-admin-';
		$url = PathsAndUrls::get_plugin_url();
		$version = Plugin::VERSION;

		// Yeah! We support SCRIPT_DEBUG mode provided by WordPress core.
		if( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG == true ) {
			$debug = true;
		}
		else {
			$debug = false;
		}

		// All styles in single file by now
		$file = $debug ? 'assets/css/admin/main-debug.css' : 'assets/css/admin/main.min.css';
		wp_register_style(
			$prefix . 'main',
			$url . $file,
			array(),
			$version
		);

		$file = $debug ? 'assets/js/admin/editor-adapter/editor-adapter.js' : 'assets/js/admin/editor-adapter/editor-adapter.min.js';
		wp_register_script(
			$prefix . 'editor-adapter',
			$url . $file,
			array( 'jquery', 'backbone', Plugin::NAME . '-editor', 'wp-pointer' ),
			$version,
			true
		);
		wp_localize_script(
			$prefix . 'editor-adapter',
			'setkaEditorAdapterL10n',
			\Setka\Editor\Admin\Service\Js\Translations::get_translations( $prefix . 'editor-adapter' )
		);

		$file = $debug ? 'assets/js/admin/editor-adapter-initializer/editor-adapter-initializer.js' : 'assets/js/admin/editor-adapter-initializer/editor-adapter-initializer.min.js';
		wp_register_script(
			$prefix . 'editor-adapter-initializer',
			$url . $file,
			array( $prefix . 'editor-adapter', 'uri-js' ),
			$version,
			true
		);

		$file = $debug ? 'assets/js/admin/setting-pages/setting-pages.js' : 'assets/js/admin/setting-pages/setting-pages.min.js';
		wp_register_script(
			$prefix . 'setting-pages',
			$url . $file,
			array( 'jquery', 'backbone' ),
			$version,
			true
		);

		$file = 'assets/js/admin/setting-pages-initializer/setting-pages-initializer';
		$file .= $debug ? '.js' : '.min.js';

		wp_register_script(
			$prefix . 'setting-pages-initializer',
			$url . $file,
			array( $prefix . 'setting-pages' ),
			$version,
			true
		);
	}
}
