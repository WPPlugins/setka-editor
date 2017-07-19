<?php
namespace Setka\Editor\API\V1\Actions\Resources\Update;

use Setka\Editor\Admin\Options;
use Setka\Editor\API\V1\Prototypes\AbstractAction;
use Setka\Editor\API\V1\Helpers;
use Setka\Editor\API\V1\Errors;
use Setka\Editor\API\V1;

/**
 * @api {post} /wp-admin/admin-post.php?action=/webhook/setka-editor/v1/resources/update/ Update
 * @apiName PostResourcesUpdate
 * @apiGroup Resources
 *
 * @apiDescription Theme files (`theme_files`) and Content editor files (`content_editor_files`) container can contain
 * multiple files stored as object (or array with integer indexes) with multiple cells (single files objects).
 * The order of files is not matter.
 *
 * WordPress check that at least one file with `css`-filetype and one `json`-filetype must exists
 * in `theme_files` list (bunch).
 *
 * At least one file with `css`-filetype and one with `js`-filetype must exists in `content_editor_files` list.
 *
 * While saving we use first available file in list to save as css-js-json file.
 *
 * @apiParam {String} action="/webhook/setka-editor/v1/resources/update/" Action endpoint.
 *
 * @apiUse AuthHelper
 * @apiUse ThemeFilesHelper
 * @apiUse ContentEditorFilesHelper
 * @apiUse ThemePluginsHelper
 *
 * @apiParamExample {json} All the files (editor + plugins + theme)
 * {
 *      "action": "/webhook/setka-editor/v1/resources/update/",
 *      "token": "R9rEUHQVbG6nRIvpfiGzs6SuxxqbpLTZ",
 *      "data": {
 *          "content_editor_files": {
 *              0: {
 *                  "id": 1,
 *                  "url": "https://ceditor-dev.setka.io/editor.min.css",
 *                  "filetype": "css"
 *              },
 *              1: {
 *                  "id": 2,
 *                  "url": "https://ceditor-dev.setka.io/editor.min.js",
 *                  "filetype": "js"
 *              }
 *          },
 *          "theme_files": {
 *              0: {
 *                  "id": 1,
 *                  "url": "https://ceditor-dev.setka.io/clients/RANDOM_STRING_HERE/css/185_setka_1_23.min.css",
 *                  "filetype": "css"
 *              },
 *              1: {
 *                  "id": 2,
 *                  "url": "https://ceditor-dev.setka.io/clients/RANDOM_STRING_HERE/json/185_setka_1_23.json",
 *                  "filetype": "json"
 *              },
 *              2: {
 *                  "id": 3,
 *                  "url": "https://example.com/image.svg",
 *                  "filetype": "svg"
 *              }
 *          },
 *          "plugins": {
 *              0: {
 *                  "url": "https://ceditor-dev.setka.io/plugins.min.js",
 *                  "filetype": "js"
 *              }
 *          }
 *      }
 * }
 *
 * @apiSuccess (Action) 200 If all data successfully saved. Be aware that **this status code also returned
 * by WordPress if Setka Editor plugin not installed or deactivated**.
 *
 * @apiError (Action) 400 If request not contain `data` and `token` fields.
 */
class Action extends AbstractAction {

	public function __construct(V1\API $api) {
		parent::__construct($api);
		$this->setEndpoint('resources/update');
	}

	/**
	 * {@inheritdoc}
	 */
	public function handleRequest() {
		$request = $this->getRequest();
		$response = $this->getResponse();
		$api = $this->getApi();

		// Only POST requests allowed in this action.
		if( $request->getMethod() !== $request::METHOD_POST ) {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\HttpMethodError() );
			return;
		}

		// We need a [data] and token key here
		if( !$request->request->has('data') ) {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\MissedDataAttributeError() );
			return;
		}

		if(!is_array($request->request->get('data'))) {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError(new Errors\RequestDataError());
			return;
		}

		// Validate token
		$token_helper = new Helpers\Auth\Helper( $this->getApi() );
		$token_helper->handleRequest();
		$token_helper->__destruct();
		unset( $token_helper );
		// Token not valid
		if( !$response->isOk() ) {
			return;
		}

		// Theme files
		$theme_files_helper = new Helpers\ThemeFilesHelper( $this->getApi() );
		$theme_files_helper->handleRequest();
		$theme_files_helper->__destruct();
		unset( $theme_files_helper );

		// Theme files not valid
		if( !$response->isOk() ) {
			return;
		}

		// Theme Plugins
		$theme_plugins_helper = new Helpers\ThemePluginsHelper( $this->getApi() );
		$theme_plugins_helper->handleRequest();
		$theme_plugins_helper->__destruct();
		unset( $theme_plugins_helper );

		// Theme plugins not valid
		if( !$response->isOk() ) {
			return;
		}

		// Editor files
		$editor_files_helper = new Helpers\ContentEditorFilesHelper( $this->getApi() );
		$editor_files_helper->handleRequest();
		$editor_files_helper->__destruct();
		unset( $editor_files_helper );

		if( !$response->isOk() ) {
			return;
		}

		// Update theme files
		foreach( $request->request->get('data')->get('theme_files') as $file ) {
			switch( $file['filetype'] ) {
				case 'css':
					$_option = new Options\ThemeResourceCSS\Option();
					$_option->updateValue( $file['url'] );
					break;
				case 'json':
					$_option = new Options\ThemeResourceJS\Option();
					$_option->updateValue( $file['url'] );
					break;
			}
			unset( $_option );
		}

		// Update editor files
		foreach( $request->request->get('data')->get('content_editor_files') as $file ) {
			switch( $file['filetype'] ) {
				case 'css':
					$_option = new Options\EditorCSS\Option();
					$_option->updateValue( $file['url'] );
					break;
				case 'js':
					$_option = new Options\EditorJS\Option();
					$_option->updateValue( $file['url'] );
					break;
			}
			unset( $_option );
		}

		// Update editor version
		$editorVersion = new Options\EditorVersion\Option();
		$editorVersion->updateValue( $request->request->get('data')->get('content_editor_version') );
		unset( $editorVersion );

		// Update theme plugin files
		$_option = new Options\ThemePluginsJS\Option();
		$plugins = $request->request->get('data')->get('plugins');
		$_option->updateValue( $plugins[0]['url'] );
		unset( $_option, $plugins );

		$response->setStatusCode( $response::HTTP_OK );
	}

	public function getConstraint() {}
}
