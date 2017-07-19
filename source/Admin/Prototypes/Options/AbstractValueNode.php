<?php
namespace Setka\Editor\Admin\Prototypes\Options;

abstract class AbstractValueNode implements ValueNodeInterface {

	use Traits\NameTrait;

	use Traits\Aggregate\ValueRawTrait;

	use Traits\DefaultValueTrait;

	use Traits\ConstraintTrait;

	use Traits\ValidatorTrait;

	use Traits\ValidateTrait;

	use Traits\ParentTrait;

	use Traits\Aggregate\ValueTrait;

	abstract public function sanitize( $instance );
}
