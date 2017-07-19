<?php
namespace Setka\Editor\Admin\Prototypes\Transients;

/**
 * TransientInterface is similar to OptionInterface but iteracts with transients.
 */
interface TransientInterface {

	public function getName();
	public function setName( $name );

	public function getExpiration();
	public function setExpiration( $expiration );

	public function delete();

	public function setValue( $value );
	public function getValue();

	public function updateValue( $value );
	public function flush();
}
