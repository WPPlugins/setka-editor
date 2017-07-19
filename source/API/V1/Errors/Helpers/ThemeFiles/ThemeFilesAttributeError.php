<?php
namespace Setka\Editor\API\V1\Errors\Helpers\ThemeFiles;

use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\Errors\Error;

class ThemeFilesAttributeError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_api_helpers_theme_files_attribute_error' );
		$this->setMessage( __( 'The request from Setka server has missed or not valid data[theme_files] attribute.', Plugin::NAME ) );
	}
}
