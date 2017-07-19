<?php
namespace Setka\Editor\Admin\Service;

use Setka\Editor\Admin\Options;
use Setka\Editor\Admin\Cron;
use Setka\Editor\Entries;
use Setka\Editor\Plugin;

/**
 * We save additional post meta by TWO ways:
 *
 *   1. On POST request when user click "Publish" button.
 *   2. On post auto save events. See the following files for more info:
 *      * wp-includes/js/autosave.js
 *      * wp-includes/js/heartbeat.js
 *      * wp-admin/includes/misc.php:854 (heartbeat_autosave function)
 *
 * Class SavePost
 * @package Setka\Editor\Admin\Service
 */
class SavePost {

	/**
	 * Save post meta. This method handles only POST requests.
	 *
	 * WARNING: this method don't include any checks of current_user_can()
	 * because this already happened in edit_post()
	 *
	 * @see \Setka\Editor\Admin\Admin::run()
	 * @see edit_post()
	 * @see wp_update_post()
	 * @see wp_insert_post()
	 *
	 * @since 0.0.2
	 *
	 * @param $post_id int Post ID.
	 * @param $post object WordPress Post object
	 * @param $update bool Update or create new post.
	 */
	public static function save_post( $post_id, $post, $update ) {

		// Nonce already validated in wp-admin/post.php

		// Stop on autosave (see heartbeat_received() in this class for autosavings)
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Prevent quick edit from clearing custom fields
		if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		// Our settings presented in request?
		if( !isset( $_POST[Plugin::NAME . '-settings'] ) ) {
			return;
		}

		// Parse settings from JSON object
		// WordPress use addslashes() in wp_magic_quotes()
		$settings = stripcslashes( $_POST[Plugin::NAME . '-settings'] );
		$settings = json_decode( $settings, true );

		self::proceeding( $settings, $post_id );
	}

	/**
	 * Handles default post auto saves (triggering by WordPress Heartbeat API).
	 *
	 * @see wp_ajax_heartbeat()
	 * @see heartbeat_autosave()
	 *
	 * @since 0.0.2
	 *
	 * @param $response array Which will be sent back to the client in browser.
	 * @param $data array The data comes from JavaScript (Browser).
	 *
	 * @return array Just pass $response for the next filters as is.
	 */
	public static function heartbeat_received( $response, $data ) {
		// Our settings presented in request?
		if( isset( $data[Plugin::NAME] ) ) {

			// Create a link to long named variable :) just to write less code bellow
			$settings =& $data[Plugin::NAME];

			/**
			 * @see heartbeat_autosave()
			 * @see wp_autosave()
			 */

			if( isset( $settings['postId'] ) ) {
				$settings['postId'] = (int)$settings['postId'];

				if( isset( $settings['_wpnonce'] ) ) {

					// Check nonce like in heartbeat_autosave()
					if ( false === wp_verify_nonce( $settings['_wpnonce'], 'update-post_' . $settings['postId'] ) ) {
						// Just pass $response for the next filters.
						return $response;
					}

					// Check current_user_can edit post
					$post = get_post( $settings['postId'] );
					if( $post instanceof \WP_Post && property_exists( $post, 'ID' ) ) {
						if ( current_user_can( 'edit_post', $post->ID ) ) {
							self::proceeding( $settings, $settings['postId'] );
						}
					}
				}
			}
		}
		// Just pass $response for the next filters.
		return $response;
	}

	/**
	 * Simply saves the post meta. Called from heartbeat_received() or from save_post().
	 *
	 * We need save some extra settings from our Grid Editor (layout style, theme name,
	 * the number of cols...) as post meta. Currently we save three things here:
	 *
	 *   1. Post created with Grid Editor or not (default WP editor).
	 *   2. Post layout.
	 *   3. Post theme.
	 *
	 * No sanitization post meta here because we sanitize all meta values by filters.
	 *
	 * @see \Setka\Editor\Entries\Metas::setup_metas_sanitizers()
	 *
	 * @since 0.0.2
	 *
	 * @param $new_settings array Post settings.
	 */
	public static function proceeding( $settings, $post_id ) {

		/**
		 * Possible additional checks:
		 *   1. Post Type (post, page, attachment). Currently this not validates because
		 *      post may already created with editor and now this post_type disabled but
		 *      old post need to be available with editor.
		 *
		 *   2. Current user can use grid editor. Possible issue then user can edit post
		 *      but don't have editor access.
		 *
		 * At now use only current_user_can('edit').
		 */

		if( isset( $settings['useSetkaEditor'] ) ) {

			// Editor enabled (update the metas)
			if( $settings['useSetkaEditor'] === '1' || $settings['useSetkaEditor'] === '0' ) {

				// Check for the first Setka Editor Post on this site
				if( $settings['useSetkaEditor'] === '1' ) {
					$created_in_setka_editor = new Options\SetkaPostCreated\Option();
					if( $created_in_setka_editor->getValue() != '1' ) {
						$task = new Cron\Tasks\SetkaPostCreated\Task();
						$task->register();
						$created_in_setka_editor->updateValue('1');
					}
				}

				// Post created with Grid Editor
				$use_editor_meta = new Entries\Meta\UseEditorMeta();
				$use_editor_meta->setPostId( $post_id );
				$use_editor_meta->updateValue( $settings['useSetkaEditor'] );

				// Update Post Theme name. Example: 'village-2016'
				if( isset( $settings['editorConfig']['theme'] ) ) {
					$post_theme_meta = new Entries\Meta\PostThemeMeta();
					$post_theme_meta->setPostId( $post_id );
					$post_theme_meta->updateValue( $settings['editorConfig']['theme'] );
				}

				// Update Post Layout. Example: '6' or '12'
				if( isset( $settings['editorConfig']['layout'] ) ) {
					$post_layout_meta = new Entries\Meta\PostLayoutMeta();
					$post_layout_meta->setPostId( $post_id );
					$post_layout_meta->updateValue( $settings['editorConfig']['layout'] );
				}

				// Update Type Kit ID. Example: 'ktz3nwg'
				if( isset( $settings['editorConfig']['typeKitId'] ) ) {
					$type_kit_id_meta = new Entries\Meta\TypeKitIDMeta();
					$type_kit_id_meta->setPostId( $post_id );
					$type_kit_id_meta->updateValue( $settings['editorConfig']['typeKitId'] );
				}
			}
		}
	}
}
