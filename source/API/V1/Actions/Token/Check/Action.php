<?php
namespace Setka\Editor\API\V1\Actions\Token\Check;

use Setka\Editor\API\V1\Prototypes\AbstractAction;
use Setka\Editor\API\V1\Helpers;
use Setka\Editor\Plugin;
use Setka\Editor\API\V1;
use Setka\Editor\API\V1\Errors;
use Setka\Editor\Admin\Options\EditorVersion\Option as EditorVersion;

class Action extends AbstractAction {

	public function __construct(V1\API $api) {
		parent::__construct($api);
		$this->setEndpoint('token/check');
	}

	public function handleRequest() {
		$request = $this->getRequest();
		$response = $this->getResponse();
		$responseData = $this->getResponseData();
		$api = $this->getApi();

		// Only POST requests allowed in this action.
		if( $request->getMethod() !== $request::METHOD_POST ) {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\HttpMethodError() );
			return;
		}

		// Validate token
		$token_helper = new Helpers\Auth\Helper( $this->getApi() );
		$token_helper->handleRequest();
		unset( $token_helper );
		// Token not valid
		if( !$response->isOk() ) {
			return;
		}

		$responseData->set('status', 'The license key is valid.');
		$responseData->set('plugin_version', Plugin::VERSION);
		$editorVersion = new EditorVersion();
		$responseData->set('content_editor_version', $editorVersion->getValue());
	}

	public function getConstraint() {}
}
