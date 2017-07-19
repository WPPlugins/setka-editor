<?php
namespace Setka\Editor\Admin\Service\Js;

use Setka\Editor\Plugin;

class Translations {

	/**
	 * @var $translations mixed Translations for the scripts.
	 */
	private static $translations;

	/**
	 * Load translations for JS files into static variable.
	 *
	 * @since 0.0.2
	 *
	 * @see \Setka\Editor\Admin\Admin::run()
	 */
	public static function prepare_translations() {
		self::$translations = array(
			Plugin::NAME . '-wp-admin-editor-adapter' => array(
				'view' => array(
					'editor' => array(
						'tabName' => _x( 'Setka Editor', 'editor tab name', Plugin::NAME ),
						'switchToDefaultEditorsConfirm' => __( 'Are you sure that you want to switch to default WordPress editor? You will lose all the formatting and design created in Setka Editor.', Plugin::NAME )
					),
					'notices' => array(
						'setkaEditorSaveSnippet' => array(
							'cantConnectToWordPress' => __( 'Your WordPress server does not respond. Please try to save snippet a little bit later', Plugin::NAME )
						)
					)
				),
				'names' => array(
					'css' => Plugin::NAME,
					'_'   => Plugin::_NAME_
				),
				'settings' => EditorAdapter\Settings::get_settings(),
				'pointers' => array(
					'disabledEditorTabs' => array(
						'target' => '#wp-content-editor-tools .wp-editor-tabs',
						'options' => array(
							'pointerClass' => 'wp-pointer setka-editor-pointer-centered-arrow',
							'content' => sprintf( '<h3>%s</h3><p>%s</p>',
								__('Why Text and Visual tabs are blocked?', Plugin::NAME),
								__('Posts created with Setka Editor may contain complex design elements that are not compatible with other post editors.', Plugin::NAME)
							),
							'position' => array('edge' => 'top', 'align' => 'middle')
						)
					)
				)
			)
		);
	}

	/**
	 * Get translations for selected script. Resulted array used in wp_localize_script().
	 *
	 * @since 0.0.2
	 *
	 * @param $name string Name of script.
	 *
	 * @return array Desired translations as array or empty array if this script don't have translations.
	 */
	public static function get_translations( $name ) {
		if( isset( self::$translations[$name] ) ) {
			return self::$translations[$name];
		}
		return array();
	}
}
