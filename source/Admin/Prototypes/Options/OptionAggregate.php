<?php
namespace Setka\Editor\Admin\Prototypes\Options;

use Setka\Editor\Admin\Prototypes\Options\Traits;

class OptionAggregate extends AbstractOption implements AggregateNodeInterface {

	use Traits\ParentTrait;

	use Traits\Aggregate\ConstraintAllowExtraTrait;

	use Traits\Aggregate\NodesTrait;

	use Traits\Aggregate\SanitizeTrait;

	use Traits\Aggregate\DefaultValueTrait;

	use Traits\Aggregate\ValueTrait;
}
