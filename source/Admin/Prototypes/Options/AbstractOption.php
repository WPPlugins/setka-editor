<?php
namespace Setka\Editor\Admin\Prototypes\Options;

/**
 * Default class for plugin settings (options).
 */
abstract class AbstractOption implements OptionInterface, ValueNodeInterface {

	use Traits\NameTrait;

	use Traits\GroupTrait;

	use Traits\ConstraintTrait;

	use Traits\ValidatorTrait;

	use Traits\ValidateTrait;

	use Traits\ValueTrait;

	use Traits\ValueRawTrait;

	use Traits\DefaultValueTrait;

	/**
	 * @var $autoload bool Should this option autoloaded or not.
	 */
	protected $autoload = true;

	public function __construct( $name, $group ) {
		$this->setName( $name );
		$this->setGroup( $group );
	}

	abstract public function buildConstraint();

	/**
	 * @return boolean
	 */
	public function isAutoload() {
		return $this->autoload;
	}

	/**
	 * @param boolean $autoload
	 */
	public function setAutoload( $autoload ) {
		$this->autoload = (bool) $autoload;
	}

	public function enableAutoload() {
		$this->setAutoload( true );
	}

	public function disableAutoload() {
		$this->setAutoload( false );
	}

	public function delete() {
		return delete_option( $this->getName() );
	}

	public function flush() {
		if( isset( $this->value ) ) {
			try {
				$result = update_option( $this->getName(), $this->getValue(), $this->isAutoload() );
			}
			catch( \Exception $e ) {
				return false;
			}
			$this->setValue(null);
			return $result;
		}
		return true;
	}

	public function updateValue( $value, $autoload = null ) {
		$this->setValue( $value );
		if( !is_null( $autoload ) ) {
			$this->setAutoload( $autoload );
		}
		return $this->flush();
	}

	abstract public function sanitize( $instance );

	public function register() {
		register_setting(
			$this->getGroup(),
			$this->getName(),
			array( $this, 'sanitize' )
		);
	}
}
