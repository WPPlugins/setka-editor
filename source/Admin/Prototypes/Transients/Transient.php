<?php
namespace Setka\Editor\Admin\Prototypes\Transients;

class Transient implements TransientInterface {

	/**
	 * @var string Must be 172 characters or fewer in length.
	 */
	protected $name;

	protected $value;

	/**
	 * @var int Time until expiration in seconds. Default 0 (no expiration).
	 */
	protected $expiration = 0;

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name ) {
		$name = (string)$name;
		if( defined('WP_DEBUG') && WP_DEBUG === true && strlen( $name ) > 45 ) {
			throw new \InvalidArgumentException( 'Name should be 45 characters or less in length.' );
		}
		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function getExpiration() {
		return $this->expiration;
	}

	/**
	 * @param int $expiration
	 */
	public function setExpiration( $expiration ) {
		$this->expiration = (int)$expiration;
	}

	public function delete() {
		return delete_transient( $this->getName() );
	}

	public function setValue( $value ) {
		$this->value = $value;
	}

	public function getValue() {
		if( isset( $this->value ) ) {
			return $this->value;
		}
		return get_transient( $this->getName() );
	}

	public function updateValue( $value ) {
		$this->setValue( $value );
		return $this->flush();
	}

	public function flush() {
		$result = set_transient( $this->getName(), $this->getValue(), $this->getExpiration() );
		if( $result ) {
			// If update was successful, then delete the local value
			$this->setValue(null);
		}
		return $result;
	}
}
