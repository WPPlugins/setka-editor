<?php
namespace Setka\Editor\Prototypes\Errors;

class Errors implements ErrorsInterface {

	/**
	 * @var Error[] The list of errors
	 */
	protected $errors = array();

	public function __construct( array $errors = array() ) {
		if( !empty( $errors ) ) {
			foreach( $errors as $error ) {
				$this->add( $error );
			}
		}
	}

	public function all() {
		return $this->errors;
	}

	public function allAsArray( $code = true, $message = true, $messageHTML = true, $data = true ) {
		$errors = array();
		if( $this->hasErrors() ) {
			foreach( $this->errors as $error ) {
				$singleError = array();
				if( $code ) {
					$singleError['code'] = $error->getCode();
				}
				if( $message ) {
					$singleError['message'] = $error->getMessage();
				}
				if( $messageHTML ) {
					$singleError['messageHTML'] = $error->getMessageHTML();
				}
				if( $data ) {
					$singleError['data'] = $error->getData();
				}
				/*$errors[] = array(
					'code'         => $error->getCode(),
					'message'      => $error->getMessage(),
					'messagePlain' => $error->getMessagePlain(),
					'data'         => $error->getData()
				);*/
				$errors[] = $singleError;
			}
		}
		return $errors;
	}

	public function keys() {
		return array_keys( $this->errors );
	}

	public function replace( array $errors = array() ) {
		$this->errors = $errors;
	}

	public function add( ErrorInterface $error ) {
		$this->errors[ $error->getCode() ] = $error;
		return $this;
	}

	public function get( $key ) {
		return array_key_exists( $key, $this->errors ) ? $this->errors[$key] : false;
	}

	public function has( $key ) {
		return array_key_exists( $key, $this->errors );
	}

	public function remove( $key ) {
		unset($this->errors[$key]);
	}

	public function hasErrors() {
		if( empty( $this->errors ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Returns an iterator for errors.
	 *
	 * @return \ArrayIterator An \ArrayIterator instance
	 */
	public function getIterator() {
		return new \ArrayIterator( $this->errors );
	}

	/**
	 * Returns the number of errors.
	 *
	 * @return int The number of errors
	 */
	public function count() {
		return count( $this->errors );
	}
}
