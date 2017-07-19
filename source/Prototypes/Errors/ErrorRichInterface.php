<?php
namespace Setka\Editor\Prototypes\Errors;

interface ErrorRichInterface {

	public function getMessageHTML();
	public function setMessageHTML( $message );
}
