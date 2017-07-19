<?php
namespace Setka\Editor\Admin\Notices\AfterSignIn;

use Setka\Editor\Admin;
use Setka\Editor\Plugin;

class Notice extends Admin\Prototypes\Notices\Notice {

	public function __construct() {
		parent::__construct( Plugin::NAME, 'after_sign_in' );

		$this->setRelevantStorage(
			new Admin\Prototypes\Notices\TransientRelevantStorage(
				new Admin\Transients\AfterSignInNotice\Transient()
			)
		);
	}

	public function lateConstruct() {
		parent::lateConstruct();

		$content = sprintf(
			/* translators: %1$s - url to post create page  */
			__( 'Congratulations! You can <a href="%1$s">create a new post with Setka Editor</a>.', Plugin::NAME ),
			esc_url( admin_url( 'post-new.php?' . Plugin::NAME .  '-auto-init' ) )
		);

		$this->setContent( '<p>' . $content . '</p>' );
		$this->setAttribute( 'class', 'notice setka-editor-notice notice-success setka-editor-notice-success' );
	}

	public function isRelevant() {
		if( !current_user_can( 'manage_options' ) ) {
			return false;
		}

		return parent::isRelevant();
	}
}
