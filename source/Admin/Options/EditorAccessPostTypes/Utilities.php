<?php
namespace Setka\Editor\Admin\Options\EditorAccessPostTypes;

class Utilities {

	public static function is_editor_enabled_for_post_type( $post_type ) {
		if( is_string( $post_type ) && !empty( $post_type ) ) {
			try {
				$option = new Option();

				return in_array(
					$post_type,
					$option->getValue()
				);
			}
			catch( \Exception $e ) {
				return false;
			}
		}
		return false;
	}
}
