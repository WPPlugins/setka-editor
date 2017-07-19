<?php
namespace Setka\Editor\Service;

use Setka\Editor\Plugin;

class Condition {

	/**
	 * You can manually register and manage Type Kits. Just pass false to this filter
	 * and Setka Editor plugin will not register and enqueue Type Kits on site pages.
	 *
	 * @return bool <code>true</code> (default) if plugin need manage Type Kits JS and enqueue them.
	 * <code>false</code> if current theme automatically enqueue required Type Kits.
	 */
	public static function should_plugin_manage_type_kits() {
		return (bool) apply_filters( Plugin::_NAME_ . '_should_plugin_manage_type_kits', true );
	}
}
