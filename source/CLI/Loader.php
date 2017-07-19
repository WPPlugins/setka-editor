<?php
namespace Setka\Editor\CLI;

use Setka\Editor\Plugin;

class Loader {

	public static function run() {
		if( defined('WP_CLI') && WP_CLI === true ) {
			self::registerCommands();
		}
	}

	public static function registerCommands() {
		\WP_CLI::add_command( Plugin::NAME, 'Setka\Editor\CLI\Commands\AccountCommand' );
	}
}
