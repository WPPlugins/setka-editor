<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Actions;

use Setka\Editor\Admin\Service\SetkaAPI;
use Setka\Editor\Admin\Service\SetkaAPI\Errors;
use Setka\Editor\Admin\Service\SetkaAPI\Helpers;
use Setka\Editor\Plugin;
use Setka\Editor\Service\WPErrorUtils;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Validator\Constraints;

class GetCurrentThemeAction extends SetkaAPI\Prototypes\ActionAbstract {

	const ENDPOINT = '\Setka\Editor\Admin\Service\SetkaAPI\Endpoints::CURRENT_THEME';

	const METHOD = 'POST';

	public function getConstraint() {}

	public function parseResponse() {
		$response = $this->getResponse();

		switch( $response->getStatusCode() ) {
			/**
			 * // 200 //
			 * theme_files and content_editor_files must presented in response
			 */
			case $response::HTTP_OK:
				if( !$this->parseContent() )
					return;

				// Content Editor Files
				$helper = new Helpers\ContentEditorFilesHelper( $this->getApi(), $response, $this->getErrors() );
				$helper->parseResponse();
				if( $this->getErrors()->hasErrors() )
					return;
				unset( $helper );

				// Theme Files
				$helper = new Helpers\ThemeFilesHelper( $this->getApi(), $response, $this->getErrors() );
				$helper->parseResponse();
				if( $this->getErrors()->hasErrors() )
					return;
				unset( $helper );

				// Plugin Files
				$helper = new Helpers\PluginsHelper( $this->getApi(), $response, $this->getErrors() );
				$helper->parseResponse();
				if( $this->getErrors()->hasErrors() )
					return;
				unset( $helper );

				return;

			// 401 // Token not found
			case $response::HTTP_UNAUTHORIZED:
				$this->getErrors()->add( new Errors\ServerUnauthorizedError() );
				return;

			/**
			 * // 403 //
			 * This status code means what subscription is canceled or something.
			 * But in this case API also response with valid theme_files.
			 * Creating new posts functionality disabled but old posts
			 * can correctly displayed.
			 */
			case $response::HTTP_FORBIDDEN:
				if( !$this->parseContent() )
					return;

				// Errors
				$helper = new Helpers\ErrorHelper( $this->getApi(), $response, $this->getErrors() );
				$helper->parseResponse();
				if( $this->getErrors()->hasErrors() )
					return;
				unset( $helper );

				// Theme Files
				$helper = new Helpers\ThemeFilesHelper( $this->getApi(), $response, $this->getErrors() );
				$helper->parseResponse();
				if( $this->getErrors()->hasErrors() )
					return;
				unset( $helper );
				break;

			default:
				$this->getErrors()->add( new Errors\UnknownError() );
		}
	}
}
