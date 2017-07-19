<?php
namespace Setka\Editor\Admin\Service;

class HTMLUtils {

	public static function convert_array_to_attr( array $paths, $first_as_prefix = true ) {

		$attr = '';

		if( !empty( $paths ) ) {
			$prefix = array_shift( $paths );

			if( !empty( $paths ) ) {
				$attr = implode( '][', $paths );
				$attr = '[' . $attr . ']';
			}
			$attr = $prefix . $attr;

			return esc_attr( $attr );
		}

		return $attr;
	}
}