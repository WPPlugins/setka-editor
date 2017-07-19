<?php
namespace Setka\Editor\Service;

class PathsAndUrls {

	public static function get_plugin_dir_path( $sub_path = null ) {
		$path = $GLOBALS['WPSetkaEditorPlugin']->get_dir_path();

		if( is_string( $sub_path ) && !empty( $sub_path ) ) {
			$path .= $sub_path;
		}

		return $path;
	}

	public static function get_vendor_path( $sub_path = null ) {
		$vendor_path = self::get_plugin_dir_path( 'vendor/' );

		if( is_string( $sub_path ) && !empty( $sub_path ) ) {
			$vendor_path .= $sub_path;
		}

		return $vendor_path;
	}

	public static function get_plugin_url( $sub_path = null ) {
		$url = plugin_dir_url( $GLOBALS['WPSetkaEditorPlugin']->get_path() );

		if( is_string( $sub_path ) && !empty( $sub_path ) ) {
			$url .= $sub_path;
		}

		return $url;
	}

	public static function get_plugin_basename_path( $path_to_add = null ) {
		$path = basename( dirname( $GLOBALS['WPSetkaEditorPlugin']->get_path() ) );

		if( is_string( $path_to_add ) && !empty( $path_to_add ) ) {
			$path = trailingslashit( $path );
			$path .= $path_to_add;
		}

		return $path;
	}
}
