<?php
namespace Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\NumberOfEmployees\Ranges;

use Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp\NumberOfEmployees\EmployeesRange;
use Setka\Editor\Plugin;

class EmployeesRange1_5 extends EmployeesRange {

	public function __construct() {
		$this->setTitle(__('1–5 employees', Plugin::NAME));
		$this->setValue('1−5 employees');
	}
}
