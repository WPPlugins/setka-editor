<?php
namespace Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\NumberOfEmployees\Ranges;

use Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\NumberOfEmployees\EmployeesRange;
use Setka\Editor\Plugin;

class EmployeesRange6_20 extends EmployeesRange {

	public function __construct() {
		$this->setTitle(__('6–20 employees', Plugin::NAME));
		$this->setValue('6−20 employees');
	}
}
