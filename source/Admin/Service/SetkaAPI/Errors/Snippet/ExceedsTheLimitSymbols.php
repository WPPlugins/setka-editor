<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Errors\Snippet;

use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\Errors\Error;

class ExceedsTheLimitSymbols extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_setka_api_snippet_exceeds_the_limit_symbols' );
		$this->setMessage( __( 'Snippet exceeds the limit of 65535 HTML-symbols. Please select less elements and save the snippet again.', Plugin::NAME ) );
	}
}
