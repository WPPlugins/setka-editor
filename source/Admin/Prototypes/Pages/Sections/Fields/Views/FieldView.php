<?php
namespace Setka\Editor\Admin\Prototypes\Pages\Sections\Fields\Views;

use Setka\Editor\Admin\Prototypes\Pages\Sections\Fields\FieldInterface;
use Setka\Editor\Admin\Service\HTMLUtils;

abstract class FieldView implements FieldViewInterface {

	/**
	 * @var \Setka\Editor\Admin\Prototypes\Pages\Sections\Fields\Field
	 */
	protected $field;

	public function __construct( $field ) {
		$this->setField( $field );
	}

	public function getField() {
		return $this->field;
	}

	public function setField( FieldInterface $field ) {
		$this->field = $field;
	}

	public function render() {
		$this->field();
	}

	abstract public function field();

	public function getName() {
		return HTMLUtils::convert_array_to_attr( $this->getField()->getNamesHierarchyBasedOnOptions() );
	}

	public function getNameForValue( $value ) {
		$paths = $this->getField()->getNamesHierarchyBasedOnOptions();
		$paths[] = (string)$value;
		return HTMLUtils::convert_array_to_attr( $paths );
	}
}
