<?php
namespace Setka\Editor\Admin\Service;

use Setka\Editor\Admin\User\Capabilities;
use Setka\Editor\Admin\Options;
use Setka\Editor\Service\SetkaAccount\Account;
use Setka\Editor\Entries;

class Editor {

	/**
	 * Adds the editor on desired pages on wp-admin pages.
	 *
	 * Right now we need an editor only on post (and pages) edit pages.
	 *
	 * @since 0.0.1
	 *
	 * @param $path string Current page path.
	 */
	/*public static function enqueue_editor_script_styles( $path ) {
		// Enqueue editor stuff only on required pages.
		if( self::enable_editor() ) {
			\Setka\Editor\Service\Editor::enqueue_editor_script_styles();
		}
	}*/

	/**
	 * Checks if we need add Grid Editor to page.
	 *
	 * @since 0.0.1
	 *
	 * @return bool Enable editor or not.
	 */
	public static function enable_editor() {

		// If current user can't use Editor
		if( !current_user_can( Capabilities\UseEditorCapability::NAME ) ) {
			return false;
		}

		// If resources not available
		if( !Account::is_editor_resources_available() ) {
		//if( ! Options\Common\Utilities::is_editor_resources_available() ) {
			return false;
		}

		// If Editor enabled for this post_type
		$current_screen = get_current_screen();
		if(
			Options\EditorAccessPostTypes\Utilities::is_editor_enabled_for_post_type( $current_screen->post_type )
			&&
			$current_screen->base === 'post'
		) {
			return true;
		}

		return false;
	}


	/**
	 * Checks if Setka Editor need to be automatically launched.
	 *
	 * @since 1.0.4
	 *
	 * @return bool True if Setka Editor need to be automatically launched after page loaded.
	 */
	public static function is_auto_init_enabled() {

		// we need auto init in few cases

		// by GET variable
		if( isset( $_GET['setka-editor-auto-init'] ) ) {
			return true;
		}

		// by post meta (post created in Setka Editor)
		$use_editor_meta = new Entries\Meta\UseEditorMeta();
		$use_editor_meta->setPostId( get_the_ID() );
		if( $use_editor_meta->getValue() === '1' ) {
			return true;
		}

		// by default no
		return false;
	}
}
