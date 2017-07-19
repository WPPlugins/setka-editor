<?php

namespace Setka\Editor\Admin\Cron\Tasks\SyncAccount;

use Setka\Editor\Admin\Options;
use Setka\Editor\Admin\Prototypes\Cron\AbstractTask;
use Setka\Editor\Plugin;
use Setka\Editor\Service\SetkaAccount\Account;
use Setka\Editor\Service\SetkaAccount\SignIn;

class SyncAccountTask extends AbstractTask {

	public function __construct() {
		$this->setOnce(true);
		$this->setHook(Plugin::_NAME_ . '_cron_sync_account');
	}

	public function execute() {
		// Check for token (fast check)
		if(!Account::is_logged_in()) {
			return;
		}

		$token = new Options\Token\Option();

		if(!$token->isValid()) {
			return;
		}

		SignIn::sign_in_by_token($token->getValue(), false);
	}
}
