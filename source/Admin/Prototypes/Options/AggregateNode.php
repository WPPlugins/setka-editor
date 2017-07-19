<?php
namespace Setka\Editor\Admin\Prototypes\Options;

use Setka\Editor\Admin\Prototypes\Options\Traits;

class AggregateNode implements AggregateNodeInterface {

	use Traits\NameTrait;

	use Traits\Aggregate\ValueRawTrait;

	use Traits\ValueTrait;

	use Traits\ConstraintTrait;

	use Traits\Aggregate\ConstraintAllowExtraTrait;

	use Traits\Aggregate\ValidatorTrait;

	use Traits\ValidateTrait;

	use Traits\Aggregate\NodesTrait;

	use Traits\ParentTrait;

	use Traits\Aggregate\SanitizeTrait;
}
