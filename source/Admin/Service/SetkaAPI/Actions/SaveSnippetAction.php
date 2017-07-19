<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Actions;

use Setka\Editor\Admin\Service\SetkaAPI;
use Setka\Editor\Admin\Service\SetkaAPI\Errors;

class SaveSnippetAction extends SetkaAPI\Prototypes\ActionAbstract {

	const ENDPOINT = '\Setka\Editor\Admin\Service\SetkaAPI\Endpoints::SAVE_SNIPPET';

	const METHOD = 'POST';

	public function getConstraint() {}

	public function parseResponse() {
		$response = $this->getResponse();

		// Parse content
		if( !$this->parseContent() )
			return;

		switch( $response->getStatusCode() ) {
			// 201 // OK!
			case $response::HTTP_CREATED:
				// Check for valid response content
				// for now we don't check anything because we don't use this data
				break;

			// 400 // Snippet name or snippet body missed
			case $response::HTTP_BAD_REQUEST:
				$this->getErrors()->add( new Errors\Snippet\InvalidRequestData() );
				break;

			// 422 // snippet too large
			case $response::HTTP_UNPROCESSABLE_ENTITY:
				$this->getErrors()->add( new Errors\Snippet\ExceedsTheLimitSymbols() );
				break;

			// 500 // Setka server technical errors
			case $response::HTTP_INTERNAL_SERVER_ERROR:
			case $response::HTTP_BAD_GATEWAY:
			case $response::HTTP_GATEWAY_TIMEOUT:
				$this->getErrors()->add( new Errors\InternalServerError() );
				break;

			default:
				$this->getErrors()->add( new Errors\Snippet\UnknownError() );
				break;
		}
	}
}
