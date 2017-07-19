<?php
namespace Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\NumberOfEmployees\Ranges;

use Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\NumberOfEmployees\EmployeesRange;
use Setka\Editor\Plugin;

class EmployeesRange151_300 extends EmployeesRange {

	public function __construct() {
		$this->setTitle(__('151–300 employees', Plugin::NAME));
		$this->setValue('151–300 employees');
	}
}
