<?php
namespace Setka\Editor\Service;

use Setka\Editor\Plugin;

class Translations {

	/**
	 * Load plugin translations. WordPress automatically download it from wordpress.org
	 * to /wp-content/languages/plugins/ folder. This function load translations
	 * for old WordPress versions. Since 4.6.0 WordPress load files from /wp-content/translations/plugins.
	 *
	 * @since 0.0.1
	 */
	public static function load_translations() {
		load_plugin_textdomain(
			Plugin::NAME,
			false,
			PathsAndUrls::get_plugin_basename_path('languages')
		);
	}
}
