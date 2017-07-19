<?php
use Setka\Editor\Plugin;
/*
Plugin Name: Setka Editor
Plugin URI: https://editor.setka.io/
Description: A WordPress plugin for beautiful content. The editor you've been waiting for to design your posts.
Author: Native Grid LLC
Author URI: https://editor.setka.io/
Version: 1.8.2
Text Domain: setka-editor
Domain Path: /languages/
Requires at least: 4.0.0
Tested up to: 4.7.5
License: GPLv2 or later
*/

/**
 * Autoloader for all classes.
 *
 * @since 0.0.1
 */
if( !class_exists( 'Setka\Editor\Plugin' ) ) {
	// If class not exists this means what a wordpress.org version running
	// and we need require our own autoloader.
	// If you using WordPress installation with composer just require
	// your own autoload.php as usual. In this case plugin don't require any
	// additional autoloaders.
	require_once 'vendor/autoload.php';
}
$GLOBALS['WPSetkaEditorPlugin'] = new Plugin( __FILE__ );
$GLOBALS['WPSetkaEditorPlugin']->run();
