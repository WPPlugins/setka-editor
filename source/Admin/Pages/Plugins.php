<?php
namespace Setka\Editor\Admin\Pages;

use Setka\Editor\Plugin;
use Setka\Editor\Admin\Pages\Settings;
use Setka\Editor\Admin\Options;
use Setka\Editor\Service\SetkaAccount\Account;

class Plugins {

	/**
	 * Adds plugin action links (along with Deactivate | Edit | Delete).
	 *
	 * @param $links array Default links setted up by WordPress.
	 *
	 * @return array Default links + our custom links.
	 */
	public static function add_action_links( array $links ) {
		if(!isset(Settings\Loader::$pages[Plugin::NAME]))
			return $links;

		$page = Settings\Loader::$pages[Plugin::NAME];

		if( Account::is_logged_in() ) {
			$additional = array(
				'settings' => sprintf(
						'<a href="%1$s">%2$s</a>',
						esc_url( $page->getURL() ),
						esc_html( __( 'Settings', Plugin::NAME ) )
				)
			);

			$links = $additional + $links;
		}
		else {
			$additional = array(
				'start' => sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url( $page->getURL() ),
					esc_html( __( 'Start', Plugin::NAME ) )
				)
			);

			$links = $additional + $links;
		}

		return $links;
	}
}
