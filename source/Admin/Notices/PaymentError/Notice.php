<?php
namespace Setka\Editor\Admin\Notices\PaymentError;

use Setka\Editor\Admin;
use Setka\Editor\Plugin;
use Setka\Editor\Service\SetkaAccount\Account;

class Notice extends Admin\Prototypes\Notices\Notice {

	public function __construct() {
		parent::__construct( Plugin::NAME, 'payment_error' );
	}

	public function lateConstruct() {
		parent::lateConstruct();

		$content = '<p>' . __('We could not process your monthly payment using the card on file. Please <a href="https://editor.setka.io/app/" target="_blank">edit your credit card info</a> or check your balance.', Plugin::NAME) . '</p>';
		$content.= '<p>' . __('If the payment is not completed in 13 days your Setka Editor plugin functionality will be limited to the Free Plan.', Plugin::NAME) . '</p>';

		$this->setContent($content);
		$this->setAttribute('class', 'notice setka-editor-notice notice-error setka-editor-notice-error');
	}

	public function isRelevant() {

		// Ask parent class (some notices can be dismissed)
		if( !parent::isRelevant() ) {
			return false;
		}

		if( !current_user_can( 'manage_options' ) ) {
			return false;
		}

		if( Account::is_subscription_payment_past_due() ) {
			return true;
		}

		return false;
	}
}
