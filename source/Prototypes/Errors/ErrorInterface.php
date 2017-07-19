<?php
namespace Setka\Editor\Prototypes\Errors;

/**
 * This interface represents single Error (similar to \WP_Error object).
 * It's like native Exceptions in PHP but for collecting errors
 * (but not exceptions and not throws anything).
 *
 * For example, API class get response with invalid body data
 * (broken JSON, missing parameters, etc..) this means that we can't
 * parse response. And we have prepared error template for this case (scenario)
 * with already defined text and error code. And all we need to do — add this error
 * to ErrorsInterface instance.
 */
interface ErrorInterface {

	public function getCode();
	public function setCode( $code );

	public function getMessage();
	public function setMessage( $message );

	public function getMessageHTML();
	public function setMessageHTML($message);

	public function getData();
	public function setData( $data );
}
