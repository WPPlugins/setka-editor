<?php
namespace Setka\Editor\Service;

use Setka\Editor\Admin\Options;
use Setka\Editor\Admin\Transients;
use Setka\Editor\Admin\User\Capabilities;

class Uninstall {

	/**
	 * Main uninstaller function.
	 *
	 * @since 0.0.2
	 */
	public static function run() {
		/**
		 * Checklist:
		 * 1. Remove plugin settings (many options in DB).
		 * 2. Remove transients.
		 * 3. Remove plugin specific capabilities from all User Roles.
		 * 4. Remove scheduled cron tasks.
		 */

		// Delete Options
		\Setka\Editor\Admin\Options\Common\Utilities::remove_all_options_from_db();

		// Delete Transients
		\Setka\Editor\Admin\Transients\Common\Utilities::remove_all_transients_from_db();

		// Delete User Capabilities
		\Setka\Editor\Admin\User\Capabilities\Common\Utilities::remove_all_capabilities();

		// Delete Cron Tasks
		\Setka\Editor\Admin\Cron\Tasks\Common\Utilities::remove_all_cron_tasks_from_db();
	}
}
