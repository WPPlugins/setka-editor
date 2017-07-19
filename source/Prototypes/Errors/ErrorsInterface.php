<?php
namespace Setka\Editor\Prototypes\Errors;

interface ErrorsInterface extends \IteratorAggregate, \Countable {

	public function all();

	public function allAsArray( $code = true, $message = true, $messageHTML = true, $data = true );

	public function add( ErrorInterface $error );

	public function get( $key );

	public function has( $key );

	public function remove( $key );

	public function hasErrors();
}
