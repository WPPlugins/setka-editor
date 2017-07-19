<?php
namespace Setka\Editor\Admin\Service\Js\EditorAdapter;

use Setka\Editor\Admin\Options;
use Setka\Editor\Entries;

/**
 * Settings array for editor-adapter-initializer.js.
 *
 * @since 0.0.2
 *
 * Class Settings
 * @package Setka\Editor\Admin\Service\Js\EditorAdapter
 */
class Settings {

	/**
	 * Returns settings editor-adapter translations.settings.
	 *
	 * @since 0.0.2
	 *
	 * @return array Settings for editor-adapter translations.settings array field (cell).
	 */
	public static function get_settings() {
		$defaults = self::get_defaults();

		// Editor
		$use_editor_meta = new Entries\Meta\UseEditorMeta();
		$use_editor_meta->setPostId( get_the_ID() );
		if( $use_editor_meta->isValid() ) {
			$defaults['useSetkaEditor'] = $use_editor_meta->getValue();
		}

		// Layout
		$post_layout_meta = new Entries\Meta\PostLayoutMeta();
		$post_layout_meta->setPostId( get_the_ID() );
		if( $post_layout_meta->isValid() ) {
			$defaults['editorConfig']['layout'] = $post_layout_meta->getValue();
		}

		// Theme
		$post_theme_meta = new Entries\Meta\PostThemeMeta();
		$post_theme_meta->setPostId( get_the_ID() );
		if( $post_theme_meta->isValid() ) {
			$defaults['editorConfig']['theme'] = $post_theme_meta->getValue();
		}

		// TypeKit ID
		$type_kit_id = new Entries\Meta\TypeKitIDMeta();
		$type_kit_id->setPostId( get_the_ID() );
		$defaults['editorConfig']['typeKitId'] = $type_kit_id->getValue();

		// Theme JSON-data
		$theme_json = new Options\ThemeResourceJS\Option();
		$defaults['themeData'] = $theme_json->getValue();

		return $defaults;
	}

	/**
	 * Returns default settings for editor-adapter which will be overwritten by post data.
	 *
	 * @since 0.0.2
	 *
	 * @return array Default settings.
	 */
	public static function get_defaults() {
		$user = get_userdata(get_current_user_id());
		if(is_a($user, \WP_User::class)) {
			$caps = $user->get_role_caps();
		} else {
			$caps = array();
		}
		unset($user);

		$settings = array(
			'useSetkaEditor' => false,
			'editorConfig' => array(
				'medialib_image_alt_attr' => true,
				'user' => array(
					'capabilities' => $caps,
				),
			),
		);
		return $settings;
	}
}
