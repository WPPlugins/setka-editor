<?php
namespace Setka\Editor\Prototypes\Errors;

class Error implements ErrorInterface, ErrorRichInterface {

	/**
	 * @var string Error code.
	 */
	public $code;

	/**
	 * @var string Error message (description).
	 */
	public $message;

	public $messageHTML;

	/**
	 * @var mixed Any type of data which you can attach to error.
	 */
	public $data;

	/**
	 * Error constructor.
	 *
	 * @param string $code
	 * @param string $message
	 * @param mixed $data
	 */
	public function __construct( $code, $message = null, $data = null ) {
		$this->setCode( $code );
		$this->setMessage( $message );
		$this->setData( $data );
	}

	/**
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * @param string $code
	 */
	public function setCode( $code ) {
		$this->code = $code;
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		if( isset( $this->message ) ) {
			return $this->message;
		}
		if( isset( $this->messageHTML ) ) {
			return wp_strip_all_tags( $this->messageHTML );
		}
		return '';
	}

	/**
	 * @param string $message
	 */
	public function setMessage( $message ) {
		if( !is_null( $message ) ) {
			$this->message = $message;
		}
	}

	/**
	 * @return string The message with HTML tags.
	 */
	public function getMessageHTML() {
		if( isset( $this->messageHTML ) ) {
			return $this->messageHTML;
		}
		return $this->getMessage();
	}

	/**
	 * @param string $message
	 */
	public function setMessageHTML( $message ) {
		if( !is_null( $message ) ) {
			$this->messageHTML = $message;
		}
	}

	/**
	 * @return mixed
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * @param mixed $data
	 */
	public function setData( $data ) {
		if( !is_null( $data ) ) {
			$this->data = $data;
		}
	}
}
