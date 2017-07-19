<?php
namespace Setka\Editor\Admin\Options\Common;

use Setka\Editor\Admin\Options;
use Setka\Editor\Service\PathsAndUrls;
use Setka\Editor\Service\SetkaAccount;
use Symfony\Component\Finder\Finder;

class Utilities {

	/**
	 * @return \Setka\Editor\Admin\Prototypes\Options\OptionInterface[]
	 */
	public static function get_all_options() {
		$finder = new Finder();
		$files = new \hanneskod\classtools\Iterator\ClassIterator(
			$finder
				->in(PathsAndUrls::get_plugin_dir_path('source/Admin/Options'))
				->name('*Option.php')
		);
		$options = array();
		foreach( $files as $file_class => $file_info ) {
			if( class_exists( $file_class ) ) {
				$options[] = $file_class;
			}
		}

		return $options;
	}

	/**
	 * Check if any of our options presented in DB.
	 * This is a helper method for plugin Activation.
	 *
	 * @see \Setka\Editor\Service\Activation::is_activated_first_time()
	 *
	 * @return bool true if any of options founded in DB, false if no options saved in DB.
	 */
	public static function is_options_exists_in_db() {
		$options = self::get_all_options();

		foreach( $options as $option ) {
			/**
			 * @var $option \Setka\Editor\Admin\Prototypes\Options\OptionInterface
			 */
			if( class_exists( $option ) ) {
				$option = new $option();
				$value = $option->getValueRaw();
				if( $value !== false ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Removes all options from DB. This is a helper method for plugin Uninstaller.
	 *
	 * @see \Setka\Editor\Service\Uninstall::run()
	 */
	public static function remove_all_options_from_db() {
		$options = self::get_all_options();

		foreach( $options as $option ) {
			$option = new $option();
			if( is_a( $option, 'Setka\Editor\Admin\Prototypes\Options\OptionInterface' ) ) {
				/**
				 * @var $option \Setka\Editor\Admin\Prototypes\Options\OptionInterface
				 */
				$option->delete();
			}
		}
	}
}
