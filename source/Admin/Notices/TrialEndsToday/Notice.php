<?php
namespace Setka\Editor\Admin\Notices\TrialEndsToday;

use Setka\Editor\Admin;
use Setka\Editor\Plugin;
use Setka\Editor\Service\SetkaAccount\Account;

class Notice extends Admin\Prototypes\Notices\Notice {

	public function __construct() {
		parent::__construct( Plugin::NAME, 'trial_ends_today' );
	}

	public function lateConstruct() {
		parent::lateConstruct();

		$this->setContent( '<p>' . __( 'Your Setka Editor trial ends today. Tomorrow you will be charged for the next month. <a href="https://editor.setka.io/app/" target="_blank">More information</a>', Plugin::NAME ) . '</p>' );
		$this->setAttribute( 'class', 'notice setka-editor-notice notice-warning setka-editor-notice-warning' );
	}

	public function isRelevant() {

		// Ask parent class (some notices can be dismissed)
		if( !parent::isRelevant() ) {
			return false;
		}

		if( !current_user_can( 'manage_options' ) ) {
			return false;
		}

		if( !Account::is_trial_ends_in_one_day() ) {
			return false;
		}

		$current_screen = get_current_screen();
		if( in_array( $current_screen->parent_base, array( 'edit' ) ) ) {
			if( $current_screen->base === 'post' ) {
				return true;
			}
		}
		elseif( $current_screen->parent_base == Plugin::NAME ) {
			return true;
		}

		return false;
	}
}