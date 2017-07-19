<?php
namespace Setka\Editor\Admin\Prototypes\Options;

interface ValueNodeInterface extends NodeInterface {

	public function getDefaultValue();
	public function setDefaultValue( $defaultValue );
}
