<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Actions;

use Setka\Editor\Admin\Service\SetkaAPI;
use Setka\Editor\Admin\Service\SetkaAPI\Errors;

class UpdateStatusAction extends SetkaAPI\Prototypes\ActionAbstract {

	const ENDPOINT = '\Setka\Editor\Admin\Service\SetkaAPI\Endpoints::SETUP_STATUSES';

	const METHOD = 'POST';

	public function getConstraint() {}

	public function parseResponse() {
		$response = $this->getResponse();

		// Parse content
		if( !$this->parseContent() )
			return;

		switch( $response->getStatusCode() ) {
			// 20? series // OK!
			case $response::HTTP_OK:
			case $response::HTTP_CREATED:
			case $response::HTTP_ACCEPTED:
			case $response::HTTP_NON_AUTHORITATIVE_INFORMATION:
			case $response::HTTP_NO_CONTENT:
			case $response::HTTP_RESET_CONTENT:
			case $response::HTTP_PARTIAL_CONTENT:
			case $response::HTTP_MULTI_STATUS:
			case $response::HTTP_ALREADY_REPORTED:
			case $response::HTTP_IM_USED:
				// Check for valid response content
				// for now we don't check anything because we don't use this data
				break;

			// 422 // Wrong `status` field in request.
			case $response::HTTP_UNPROCESSABLE_ENTITY:
				$this->getErrors()->add( new Errors\InvalidRequestDataError() );
				break;

			default:
				$this->getErrors()->add( new Errors\UnknownError() );
				break;
		}
	}
}
