<?php
namespace Setka\Editor\Admin\Cron\Tasks\Common;

use Setka\Editor\Service\PathsAndUrls;
use Symfony\Component\Finder\Finder;

class Utilities {

	/**
	 * @return \Setka\Editor\Admin\Prototypes\Cron\TaskInterface[]
	 */
	public static function get_all_cron_tasks() {
		$finder = new Finder();
		$files = new \hanneskod\classtools\Iterator\ClassIterator(
			$finder
				->in(PathsAndUrls::get_plugin_dir_path('source/Admin/Cron/Tasks'))
				->name('*Task.php')
		);
		$tasks = array();
		foreach( $files as $file_class => $file_info ) {
			if( class_exists( $file_class ) ) {
				$tasks[] = $file_class;
			}
		}

		return $tasks;
	}

	/**
	 * Removes all options from DB. This is a helper method for plugin Uninstaller.
	 *
	 * @see \Setka\Editor\Service\Uninstall::run()
	 */
	public static function remove_all_cron_tasks_from_db() {
		$tasks = self::get_all_cron_tasks();

		foreach( $tasks as $task ) {
			$task_instance = new $task();
			if( is_a( $task_instance, 'Setka\Editor\Admin\Prototypes\Cron\TaskInterface' ) ) {
				/**
				 * @var $task_instance \Setka\Editor\Admin\Prototypes\Cron\TaskInterface
				 */
				$task_instance->unRegisterHook();
			}
		}
	}
}
