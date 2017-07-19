<?php
namespace Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\NumberOfEmployees\Ranges;

use Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\NumberOfEmployees\EmployeesRange;
use Setka\Editor\Plugin;

class EmployeesRange1001_5000 extends EmployeesRange {

	public function __construct() {
		$this->setTitle(__('1,001–5,000 employees', Plugin::NAME));
		$this->setValue('1,001–5,000 employees');
	}
}
