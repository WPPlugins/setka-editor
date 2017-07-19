<?php
namespace Setka\Editor\Admin\Cron\Tasks\SetkaPostCreated;

use Setka\Editor\Admin\Prototypes\Cron;
use Setka\Editor\Admin\Service\SetkaAPI;
use Setka\Editor\Admin\Options;
use Setka\Editor\Plugin;
use Setka\Editor\Service\SetkaAccount\Account;

class Task extends Cron\AbstractTask {

	public function __construct() {
		// Run ASAP
		$this->immediately();
		$this->setOnce(true);
		$this->setHook(Plugin::_NAME_ . '_cron_setka_post_created');
	}

	public function execute() {

		// Check for token (fast check)
		if( !Account::is_logged_in() ) {
			return;
		}

		// Check token again (more secure and slower check)
		$token = new Options\Token\Option();
		if( !$token->isValid() ) {
			return;
		}

		$api = new SetkaAPI\API();
		$api->setAuthCredits( new SetkaAPI\AuthCredits( $token->getValue() ) );
		$action = new SetkaAPI\Actions\UpdateStatusAction();
		$action->setRequestDetails(array(
			'body' => array(
				'status' => 'post_saved'
			)
		));
		$api->request( $action );

		// Delete setting if request was unsuccessful (the action have errors).
		// We make this in order to \Setka\Editor\Admin\Service\SavePost::proceeding()
		// could try add this cron task again.
		if( $action->getErrors()->hasErrors() ) {
			$setka_post_created = new Options\SetkaPostCreated\Option();
			$setka_post_created->delete();
		}
	}
}
