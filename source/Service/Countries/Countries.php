<?php
namespace Setka\Editor\Service\Countries;

class Countries {

	/**
     * Extract the country code from WP locale en_US format.
     *
	 * @param string $locale WordPress locale.
	 *
	 * @return string The country code from locale.
	 */
    public static function getCountryFromWPLocale($locale = '') {
        $position = strpos($locale, '_');
        if($position) {
            $country = substr($locale, ++$position);
            if($country) {
                return $country;
            }
        }
        return $locale;
    }
}
