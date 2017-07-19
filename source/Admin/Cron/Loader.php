<?php
namespace Setka\Editor\Admin\Cron;

class Loader {

	private static $tasks = array(
		'Setka\Editor\Admin\Cron\Tasks\SetkaPostCreated\Task',
		'Setka\Editor\Admin\Cron\Tasks\SyncAccount\SyncAccountTask',
		'Setka\Editor\Admin\Cron\Tasks\UserSignedUp\Task',
	);

	public static $tasks_instances = array();

	public static function run() {
		foreach( self::$tasks as $task ) {
			/**
			 * @var $instance \Setka\Editor\Admin\Prototypes\Cron\Task;
			 */
			$instance = new $task();
			self::$tasks_instances[$instance->getHook()] = $instance;
			// Action which triggering by WordPress Cron
			add_action( $instance->getHook(), array($instance, 'execute') );
		}
	}
}
