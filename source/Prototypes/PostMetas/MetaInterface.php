<?php
namespace Setka\Editor\Prototypes\PostMetas;

interface MetaInterface {

	public function setPostId( $id );
	public function getPostId();

	public function setName( $name );
	public function getName();

	public function setVisible( $visible );
	public function getVisible();

	public function getConstraint();
	public function setConstraint( $constraints );
	public function buildConstraint();

	public function getValidator();
	public function setValidator( \Symfony\Component\Validator\Validator\ValidatorInterface $validator );

	public function validate();
	public function isValid();

	public function getValueRaw();

	public function getValue();
	public function setValue( $value );

	public function updateValue( $value );
	public function flush();

	public function getDefaultValue();
	public function setDefaultValue( $defaultValue );

	public function delete();

	public function getAllowedPostTypes();
	public function setAllowedPostTyes( array $postTypes );

	public function register();

	public function sanitize( $value );
}
