<?php
namespace Setka\Editor\Admin\AJAX;

class Loader {

	/**
	 * @var \Setka\Editor\Admin\Prototypes\AJAX\ActionInterface[]
	 */
	public static $actions = array(
		'Setka\Editor\Admin\AJAX\SaveSetkaEditorSnippet\Action'
	);

	public static $instances = array();

	public static function run() {
		if( empty( self::$actions ) )
			return;

		foreach( self::$actions as $action ) {
			/**
			 * @var $instance \Setka\Editor\Admin\Prototypes\AJAX\ActionInterface
			 */
			$instance = new $action();
			self::$instances[$instance->getName()] = $instance;

			if( $instance->isEnabledForLoggedIn() ) {
				add_action( 'wp_ajax_' . $instance->getName(), array( $instance, 'handleRequest' ) );
			}
			if( $instance->isEnabledForNotLoggedIn() ) {
				add_action( 'wp_ajax_nopriv_' . $instance->getName(), array( $instance, 'handleRequest' ) );
			}
		}
	}
}
