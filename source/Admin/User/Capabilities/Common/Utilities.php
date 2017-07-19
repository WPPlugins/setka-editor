<?php
namespace Setka\Editor\Admin\User\Capabilities\Common;

use Setka\Editor\Service\PathsAndUrls;
use Symfony\Component\Finder\Finder;

class Utilities {

	public static function get_all_capabilities() {
		$finder = new Finder();
		$files = new \hanneskod\classtools\Iterator\ClassIterator(
			$finder
				->in(PathsAndUrls::get_plugin_dir_path('source/Admin/User/Capabilities'))
				->name('*Capability.php')
		);
		$capabilities = array();
		foreach( $files as $file_class => $file_info ) {
			if( class_exists( $file_class ) ) {
				$capabilities[] = $file_class;
			}
		}

		return $capabilities;
	}

	/**
	 * Try to remove our plugin specific capabilities from all User Roles.
	 * Used in plugin Uninstaller class to freeing up the site from plugin data.
	 */
	public static function remove_all_capabilities() {
		$roles = get_editable_roles();
		$capabilities = self::get_all_capabilities();

		if( !empty( $roles ) ) {
			foreach( $roles as $role_key => $role_value ) {
				$role = get_role( $role_key );
				foreach( $capabilities as $cap ) {
					$role->remove_cap( $cap::NAME );
				}
			}
		}
	}
}
