<?php
namespace Setka\Editor\Admin\Prototypes\Pages\Sections\Fields\Views;

use Setka\Editor\Admin\Prototypes\Pages\Sections\Fields;

interface FieldViewInterface {

	public function getField();
	public function setField( Fields\FieldInterface $field );

	public function render();

	public function field();

	public function getName();

	/**
	 * This method used for checkboxes for creating unique name(id) attr which also used
	 * inside for-attr in <label>.
	 *
	 * @return string The name HTML attr.
	 */
	public function getNameForValue( $value );
}
