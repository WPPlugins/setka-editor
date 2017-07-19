<?php
namespace Setka\Editor\Service;

use Setka\Editor\Admin\Options;
use Setka\Editor\Plugin;

class ScriptStyles {

	/**
	 * This function register most of CSS and JS files for plugin. It's just registered, not enqueued,
	 * so we (or someone else) can enqueue this files only by need. Fired (attached) to `wp_enqueue_scripts` action
	 * in \Setka\Editor\Plugin::run().
	 *
	 * @since 0.0.1
	 *
	 * @see \Setka\Editor\Plugin::run()
	 */
	public static function register() {
		$prefix = Plugin::NAME . '-';
		$url = PathsAndUrls::get_plugin_url();
		$version = Plugin::VERSION;

		// Yeah! We support SCRIPT_DEBUG mode provided by WordPress core.
		if( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG == true ) {
			$debug = true;
		}
		else {
			$debug = false;
		}

		// Font Awesome
		wp_register_style(
			$prefix . 'font-awesome',
			$url . 'assets/js/setka-editor/lib/fa/css/font-awesome.css',
			array(),
			$version
		);

		// URI JS
		wp_register_script(
			'uri-js',
			$url . 'assets/js/uri-js/' . ( $debug ? 'URI.js' : 'URI.min.js' ),
			array(),
			'1.18.1',
			true
		);

		// Setka Editor JS
		$option = new Options\EditorJS\Option();
		wp_register_script(
			$prefix . 'editor',
			$option->getValue(),
			array(),
			$version,
			true
		);

		// Setka Editor CSS
		$option = new Options\EditorCSS\Option();
		wp_register_style(
			$prefix . 'editor',
			$option->getValue(),
			array( $prefix . 'font-awesome' ),
			$version
		);
	}

	public static function register_theme_resources() {
		$prefix = Plugin::NAME . '-';
		$version = Plugin::VERSION;

		// Theme CSS
		$option = new Options\ThemeResourceCSS\Option();
		wp_register_style(
			$prefix . 'theme-resources',
			$option->getValue(),
			array(),
			$version
		);

		// Theme Plugins JS
		$option = new Options\ThemePluginsJS\Option();
		wp_register_script(
			$prefix . 'theme-plugins',
			$option->getValue(),
			array( 'jquery' ),
			$version,
			true
		);
	}

	public static function register_type_kits(array $ids) {
		$prefix = Plugin::NAME . '-';
		$version = Plugin::VERSION;

		foreach( $ids as $id_key => $id_value ) {
			$id_key = esc_attr( $id_key );
			wp_register_script(
				$prefix . 'type-kit-' . $id_key,
				'//use.typekit.net/' . $id_key .  '.js',
				array(),
				$version,
				true
			);
		}
	}

	public static function register_type_kit_runner() {
		$prefix = Plugin::NAME . '-';
		$version = Plugin::VERSION;

		wp_register_script(
			$prefix . 'type-kit-runner',
			PathsAndUrls::get_plugin_url( 'assets/js/type-kit-runner/type-kit-runner.min.js' ),
			array(),
			$version,
			true
		);
	}
}
