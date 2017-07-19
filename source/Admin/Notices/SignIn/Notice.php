<?php
namespace Setka\Editor\Admin\Notices\SignIn;

use Setka\Editor\Admin;
use Setka\Editor\Plugin;
use Setka\Editor\Service\SetkaAccount\Account;

class Notice extends Admin\Prototypes\Notices\SuccessNotice {

	public function __construct(){
		parent::__construct( Plugin::NAME, 'sign_in' );
	}

	public function lateConstruct() {
		parent::lateConstruct();

		if(isset(Admin\Pages\Settings\Loader::$pages[Plugin::NAME])) {
			$signUpURL = Admin\Pages\Settings\Loader::$pages[Plugin::NAME]->getURL();
		} else {
			$signUpURL = '';
		}

		$signInURL = add_query_arg('account-type', 'sign-in', $signUpURL);

		$content = sprintf(
			/* translators: Notice message in notice showed after plugin activation. %1$s - plugin settings page where you can create a new account and license key. %2$s - plugin settings page where you can enter your license key. */
			__('To start working with <b>Setka Editor</b> <a href="%1$s">register</a> or enter your existing <a href="%2$s">license key</a>.', Plugin::NAME),

			esc_attr($signUpURL),
			esc_attr($signInURL)
		);

		$content = '<p>' . $content . '</p>';
		$this->setContent($content);
		$this->addClass(Plugin::NAME . '-notice-sign-in');
	}

	public function isRelevant() {
		// If token already entered and saved
		if( Account::is_logged_in() ) {
			return false;
		}

		if( !current_user_can( 'manage_options' ) ) {
			return false;
		}

		$screen = get_current_screen();
		if( in_array( $screen->id, array(

		    // WordPress Welcome page
		    'about',

			// plugin settings
			'toplevel_page_setka-editor',

			// update page
			'update-core',

			// themes & source files editors
			'themes', 'theme-editor', 'plugin-editor', 'plugin-install',

			// tools
			'import', 'export', 'tools',

			// create new user
			'user',
		) ) ) {
			return false;
		}

		return parent::isRelevant();
	}
}
