<?php
namespace Setka\Editor\Admin\Service\SetkaAPI;

use Symfony\Component\HttpFoundation\ParameterBag;

class Utils {

	public static function convertArrayToParameterBags( array $array ) {
		$bags = array();
		foreach( $array as $key => $value ) {
			if( is_array( $value ) ) {
				$bags[$key] = new ParameterBag($value);
			}
			else {
				throw new \InvalidArgumentException('All values need to be arrays.');
			}
		}
		return $bags;
	}
}