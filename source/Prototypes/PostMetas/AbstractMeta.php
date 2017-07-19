<?php
namespace Setka\Editor\Prototypes\PostMetas;

use Symfony\Component\Validator\Validation;
use \Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractMeta implements MetaInterface {

	protected $postId = 0;

	protected $name;

	protected $visible = false;

	protected $constraint;

	protected $validator;

	protected $value;

	protected $defaultValue;

	protected $allowedPostTypes;

	public function getPostId() {
		return $this->postId;
	}

	public function setPostId( $postId ) {
		$this->postId = (int)$postId;
	}

	public function getName() {
		if( !$this->getVisible() ) {
			return '_' . $this->name;
		}
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	public function getVisible() {
		return $this->visible;
	}

	public function setVisible( $visible ) {
		$this->visible = (bool)$visible;
	}

	public function getConstraint() {
		if( ! $this->constraint ) {
			$this->setConstraint( $this->buildConstraint() );
		}
		return $this->constraint;
	}

	public function setConstraint( $constraint ) {
		$this->constraint = $constraint;
	}

	abstract public function buildConstraint();

	public function getValidator() {
		if( !$this->validator ) {
			$this->setValidator( Validation::createValidator() );
		}
		return $this->validator;
	}

	public function setValidator( ValidatorInterface $validator ) {
		$this->validator = $validator;
	}

	public function buildValidator() {
		return Validation::createValidator();
	}

	public function validate() {
		return $this->getValidator()->validate( $this->getValue(), $this->getConstraint() );
	}

	public function isValid() {
		$errors = $this->validate();
		if( count( $errors ) === 0 ) {
			return true;
		}
		return false;
	}

	public function getValueRaw() {
		return get_post_meta( $this->getPostId(), $this->getName(), true );
	}

	public function getValue() {
		if( isset( $this->value ) ) {
			return $this->value;
		}

		$raw = $this->getValueRaw();

		if( $raw ) {
			return $raw;
		}
		return $this->getDefaultValue();
	}

	public function setValue( $value ) {
		$this->value = $value;
		return $this;
	}

	public function updateValue( $value ) {
		$this->setValue( $value );
		return $this->flush();
	}

	public function flush() {
		if( isset( $this->value ) ) {
			$result = update_post_meta( $this->getPostId(), $this->getName(), $this->getValue() );
			// if updated successfully -> delete local value
			if( $result ) {
				$this->setValue(null);
			}
			return $result;
		}
		return true;
	}

	public function getDefaultValue() {
		return $this->defaultValue;
	}

	public function setDefaultValue( $defaultValue ) {
		$this->defaultValue = $defaultValue;
	}

	public function delete() {
		return delete_post_meta( $this->getPostId(), $this->getName() );
	}

	public function getAllowedPostTypes() {
		return $this->allowedPostTypes;
	}

	public function setAllowedPostTyes( array $postTypes ) {
		$this->allowedPostTypes = $postTypes;
	}

	/**
	 * Register Meta for post types.
	 *
	 * WordPress 4.6 introduced the new way of registering meta.
	 * We still use the old way for compatibility with old versions of WP.
	 *
	 * The downsides of this solution: we can't setup some extra stuff
	 * used in new WP versions such as 'show_in_rest', 'description'...
	 *
	 * The positive aspects: support old WP vers., not using SemVer Parse
	 * for checking version :)
	 *
	 * In future if some of users will have issues with this we can start using
	 * https://github.com/composer/semver to parse WP version and use desired
	 * register_meta method.
	 */
	public function register() {
		if( empty( $this->allowedPostTypes ) || ! is_array( $this->allowedPostTypes ) )
			return;

		// < WP 4.6 (lower)
		foreach( $this->allowedPostTypes as $postType ) {
			register_meta(
				$postType,
				$this->getName(),
				array( $this, 'sanitize' )
			);
		}

		// <= WP 4.6 (equal and greater)
		// read the method doc above
	}

	abstract public function sanitize( $value );
}
