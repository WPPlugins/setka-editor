<?php
namespace Setka\Editor\Service\SetkaAccount;

use Setka\Editor\Admin\Cron\Tasks;
use Setka\Editor\Admin\Cron\Tasks\SyncAccount\SyncAccountTask;
use Setka\Editor\Admin\Options;

class SignOut {

	public static function sign_out() {

		$_option = new Options\EditorCSS\Option();
		$_option->delete();

		$_option = new Options\EditorJS\Option();
		$_option->delete();

		$_option = new Options\EditorVersion\Option();
		$_option->delete();


		$_option = new Options\SetkaPostCreated\Option();
		$_option->delete();


		$_option = new Options\SubscriptionActiveUntil\Option();
		$_option->delete();

		$_option = new Options\SubscriptionPaymentStatus\Option();
		$_option->delete();

		$_option = new Options\SubscriptionStatus\Option();
		$_option->delete();


		$_option = new Options\ThemePluginsJS\Option();
		$_option->delete();

		$_option = new Options\ThemeResourceCSS\Option();
		$_option->delete();

		$_option = new Options\ThemeResourceJS\Option();
		$_option->delete();

		$_option = new Options\Token\Option();
		$_option->delete();

		$_option = new Options\WhiteLabel\WhiteLabelOption();
		$_option->delete();

		$task = new SyncAccountTask();
		$task->unRegisterHook();

		$task = new Tasks\UserSignedUp\Task();
		$task->unRegisterHook();
	}
}
