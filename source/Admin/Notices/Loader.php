<?php
namespace Setka\Editor\Admin\Notices;

use Setka\Editor\Admin\Notices\SubscriptionBlocked\SubscriptionBlockedNotice;

class Loader {

	/**
	 * @var \Setka\Editor\Admin\Prototypes\Notices\Notice[]
	 */
	public static $notices = array();

	public static function register() {
		self::$notices[] = new AfterSignIn\Notice();
		self::$notices[] = new PaymentError\Notice();
		self::$notices[] = new SignIn\Notice();
		self::$notices[] = new SubscriptionBlockedNotice();
		// Temporary disable this notice
		//self::$notices[] = new TrialEndsToday\Notice();
		self::$notices[] = new SetkaEditorCantFindResources\Notice();
		self::$notices[] = new SetkaEditorThemeDisabled\Notice();
		//self::$notices[] = new SetkaEditorSaveSnippet\Notice();
	}

	public static function render() {
		if( !empty( self::$notices ) ) {
			foreach( self::$notices as $notice ) {
				if( $notice->isRelevant() ) {
					$notice->lateConstruct();
					$notice->render();
				}
			}
		}
	}
}
