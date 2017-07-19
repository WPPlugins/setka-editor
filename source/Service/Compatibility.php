<?php
namespace Setka\Editor\Service;

class Compatibility {

	/**
	 * Check if current WP has minimal required version.
	 *
	 * @param $minVersion string Version in X.Y.Z format.
	 *
	 * @return bool True if WordPress version is higher or equal to min version, false otherwise.
	 */
	public static function checkWordPress($minVersion) {
		global $wp_version;
		return self::checkForMinimalVersion($wp_version, $minVersion);
	}

	/**
	 * Check if current PHP have required version
	 *
	 * @param $minVersionID int PHP version ID.
	 * @return bool True if passed id is lower or equal to PHP_VERSION_ID, false otherwise.
	 */
	public static function checkPHP($minVersionID) {
		if(!defined('PHP_VERSION_ID')) {
			return false;
		}

		if(PHP_VERSION_ID >= $minVersionID) {
			return true;
		}

		return false;
	}

	/**
	 * Check if minimal version is equal or lower than current version.
	 *
	 * @param $currentVersion string Current version.
	 * @param $minVersion string Minimal version supported by product.
	 *
	 * @return bool True if current version is higher or equal to min version, false otherwise.
	 */
	public static function checkForMinimalVersion($currentVersion, $minVersion) {
		$result = version_compare($currentVersion, $minVersion);

		if($result >= 0) {
			return true;
		}

		return false;
	}
}
