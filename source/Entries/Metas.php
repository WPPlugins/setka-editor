<?php
namespace Setka\Editor\Entries;

use Setka\Editor\Entries\Meta;
use Setka\Editor\Admin\Options;

class Metas {

	public static $instances = array();

	/**
	 * Setup meta stuff. By now setup only the meta sanitizers.
	 *
	 * Invoked from \Setka\Editor\Plugin::run().
	 *
	 * @see \Setka\Editor\Plugin::run()
	 *
	 * @since 0.0.2
	 */
	public static function run() {
		self::setup_metas();
		self::register_metas();
	}

	public static function setup_metas() {
		$metas = self::get_metas();

		if( empty( $metas ) || !is_array( $metas ) )
			return;

		foreach( $metas as $meta ) {
			$meta_instance = new $meta();
			self::$instances[$meta_instance->getName()] = $meta_instance;
		}
	}

	public static function register_metas() {

		if( empty( self::$instances ) )
			return;

		foreach( self::$instances as $meta ) {
			$meta->register();
		}
	}

	/**
	 * Returns registered meta objects names.
	 *
	 * @since 0.0.2
	 *
	 * @return array Registered (required) meta objects.
	 */
	public static function get_metas() {
		return array(
			'\Setka\Editor\Entries\Meta\PostLayoutMeta',
			'\Setka\Editor\Entries\Meta\PostThemeMeta',
			'\Setka\Editor\Entries\Meta\TypeKitIDMeta',
			'\Setka\Editor\Entries\Meta\UseEditorMeta'
		);
	}
}
