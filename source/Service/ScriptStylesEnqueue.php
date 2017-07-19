<?php
namespace Setka\Editor\Service;

use Setka\Editor\Plugin;
use Setka\Editor\Service\SetkaAccount\Account;
use Setka\Editor\Entries\Meta;

class ScriptStylesEnqueue {

	/**
	 * Enqueue resources if they required for this page.
	 *
	 * Function fired on wp_enqueue_scripts action.
	 *
	 * @see \Setka\Editor\Plugin::run()
	 */
	public static function wp_enqueue_scripts() {
		if( Account::is_theme_resources_available() && self::is_resources_required() ) {
			self::enqueue_resources_script_styles();

			// Enqueue Type Kit on singular pages
			if( is_singular() ) {
				$type_kit_meta = new Meta\TypeKitIDMeta();
				$type_kit_meta->setPostId( get_the_ID() );
				wp_enqueue_script( Plugin::NAME . '-type-kit-' . $type_kit_meta->getValue() );
				wp_enqueue_script( Plugin::NAME . '-type-kit-runner' );
			}
		}
	}

	/**
	 * Check if some posts (page or any other custom post types) in the loop created with Setka Editor.
	 *
	 * Additionally registering Type Kits if post theme using this type of fonts.
	 *
	 * @see \Setka\Editor\Entries\Meta\UseEditorMeta
	 *
	 * @return bool Returns true if at least one post created with Setka Editor.
	 * False if all posts in the loop created with default WordPress editors.
	 */
	public static function is_resources_required() {
		$required = false;
		$type_kit = Condition::should_plugin_manage_type_kits();
		if( $type_kit ) {
			$type_kit_ids = array();
		}

		if( have_posts() ) {

			$use_editor_meta = new Meta\UseEditorMeta();
			$type_kit_meta = new Meta\TypeKitIDMeta();

			while( have_posts() ) {
				the_post();
				$use_editor_meta->setPostId( get_the_ID() );
				if( $use_editor_meta->getValue() === '1' ) {
					// Post created in Setka Editor
					$required = true;

					if( $type_kit ) {
						// Check for Type Kit
						$type_kit_meta->setPostId( get_the_ID() );
						$type_kit_id = $type_kit_meta->getValue();
						if( $type_kit_id && !isset( $type_kit_ids[$type_kit_id] ) ) {
							$type_kit_ids[$type_kit_id] = true;
						}
					} else {
						break;
					}
				}
			}
			unset( $use_editor_meta, $type_kit_meta, $type_kit_id );
			rewind_posts();

			if( !empty( $type_kit_ids ) ) {
				ScriptStyles::register_type_kits( $type_kit_ids );
				ScriptStyles::register_type_kit_runner();
			}
		}

		return $required;
	}

	/**
	 * Enqueue required styles (css files) for posts created with Setka Editor
	 * on non admin site area.
	 */
	public static function enqueue_resources_script_styles() {
		wp_enqueue_script( Plugin::NAME . '-theme-plugins' );
		wp_enqueue_style( Plugin::NAME . '-theme-resources' );
	}
}
