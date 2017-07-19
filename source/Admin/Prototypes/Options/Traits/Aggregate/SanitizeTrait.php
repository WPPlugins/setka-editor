<?php
namespace Setka\Editor\Admin\Prototypes\Options\Traits\Aggregate;

trait SanitizeTrait {

	public function sanitize( $instance ) {
		$nodes = $this->getNodes();
		$sanitized_instance = array();

		if( !empty( $nodes ) ) {
			foreach( $nodes as $node ) {
				$value_to_sanitize = null;
				if( isset( $instance[$node->getName()] ) ) {
					$value_to_sanitize = $instance[$node->getName()];
				}
				$sanitized_instance[$node->getName()] = $node->sanitize( $value_to_sanitize );
			}
		}

		return $sanitized_instance;
	}
}
