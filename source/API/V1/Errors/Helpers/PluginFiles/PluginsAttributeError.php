<?php
namespace Setka\Editor\API\V1\Errors\Helpers\PluginFiles;

use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\Errors\Error;

class PluginsAttributeError extends Error {

	public function __construct() {
		$this->setCode( Plugin::_NAME_ . '_api_helpers_plugin_files_attribute_error' );
		$this->setMessage( __( 'The request from Setka server has missed or not valid data[plugins] attribute.', Plugin::NAME ) );
	}
}
