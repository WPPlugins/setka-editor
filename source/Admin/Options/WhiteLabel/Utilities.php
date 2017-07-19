<?php

namespace Setka\Editor\Admin\Options\WhiteLabel;

class Utilities {

	public static function is_white_label_enabled() {
		$option = new WhiteLabelOption();
		$value = $option->getValue();

		if($value == '1') {
			return true;
		}

		return false;
	}
}
