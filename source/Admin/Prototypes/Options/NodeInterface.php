<?php
namespace Setka\Editor\Admin\Prototypes\Options;

interface NodeInterface {

	/**
	 * @return string A name of this node.
	 */
	public function getName();

	public function setName($name);

	public function getValueRaw();

	public function getValue();

	public function setValue( $value );

	public function getConstraint();

	public function setConstraint($constraints);

	public function buildConstraint();

	public function getValidator();

	public function validate();

	public function isValid();

	public function validateValue( $value );

	public function sanitize($instance);
}
