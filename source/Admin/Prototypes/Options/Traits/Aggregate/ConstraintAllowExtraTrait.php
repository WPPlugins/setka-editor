<?php
namespace Setka\Editor\Admin\Prototypes\Options\Traits\Aggregate;

use Symfony\Component\Validator\Constraints;

trait ConstraintAllowExtraTrait {

	public function buildConstraint() {
		$nodes = $this->getNodes();

		if( !empty( $nodes ) ) {
			$childConstraints = array();
			foreach( $nodes as $key => $value ) {
				$childConstraints[$value->getName()] = $value->getConstraint();
			}
			return new Constraints\Collection( array(
				'fields' => $childConstraints,
				'allowExtraFields' => true
			) );
		}
		return new Constraints\Collection();
	}
}
