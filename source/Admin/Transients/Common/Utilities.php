<?php
namespace Setka\Editor\Admin\Transients\Common;

use Setka\Editor\Service\PathsAndUrls;
use Symfony\Component\Finder\Finder;

class Utilities {

	/**
	 * @return \Setka\Editor\Admin\Prototypes\Transients\TransientInterface[]
	 */
	public static function get_all_transients() {
		$finder = new Finder();
		$files = new \hanneskod\classtools\Iterator\ClassIterator(
			$finder
				->in(PathsAndUrls::get_plugin_dir_path('source/Admin/Transients'))
				->name('*Transient.php')
		);
		$transients = array();
		foreach( $files as $file_class => $file_info ) {
			if( class_exists( $file_class ) ) {
				$transients[] = $file_class;
			}
		}

		return $transients;
	}

	/**
	 * Removes all transients from DB. This is a helper method for plugin Uninstaller.
	 * Technically transients can be stored not in DB if your site using object cache.
	 *
	 * @see \Setka\Editor\Service\Uninstall::run()
	 */
	public static function remove_all_transients_from_db() {
		$transients = self::get_all_transients();

		foreach( $transients as $transient ) {
			$transient = new $transient();
			if( is_a( $transient, 'Setka\Editor\Admin\Prototypes\Transients\TransientInterface' ) ) {
				/**
				 * @var $transient \Setka\Editor\Admin\Prototypes\Transients\TransientInterface
				 */
				$transient->delete();
			}
		}
	}
}
