<?php
namespace Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\NumberOfEmployees\Ranges;

use Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\NumberOfEmployees\EmployeesRange;
use Setka\Editor\Plugin;

class EmployeesRange301_1000 extends EmployeesRange {

	public function __construct() {
		$this->setTitle(__('301–1,000 employees', Plugin::NAME));
		$this->setValue('301–1,000 employees');
	}
}
