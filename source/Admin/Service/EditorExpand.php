<?php
namespace Setka\Editor\Admin\Service;

use Setka\Editor\Entries;

class EditorExpand {

	/**
	 * Fix WordPress editor-expand logic on auto init Setka Editor.
	 * By default WordPress check `wp-editor-expand` class and if it exists
	 * then WP runs the adjust() in loop (about 6 times) method which is setup
	 * some styles for DIVs.
	 *
	 * Setka Editor does't need this stuffs and we disable this as we can.
	 *
	 * Checkout more info in wp-admin/js/editor-expand.js from 704 line (init section).
	 *
	 * @see \Setka\Editor\Admin\Admin::run()
	 *
	 * @since 1.0.4
	 *
	 * @return bool False if we don't need editorExpand stuff, default value otherwise.
	 */
	public static function wp_editor_expand( $expand ) {
		if( Editor::enable_editor() && Editor::is_auto_init_enabled() ) {
			return false;
		}
		return $expand;
	}
}
