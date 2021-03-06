<?php
namespace Setka\Editor\API\V1\Errors\Helpers\ContentEditorFiles;

use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\Errors\Error;

class ContentEditorVersionError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_api_helpers_content_editor_version_attribute_error' );
		$this->setMessage( __( 'The request from Setka server has missed or not valid data[content_editor_version] attribute.', Plugin::NAME ) );
	}
}
