<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Actions;

use Setka\Editor\Admin\Options\PlanFeatures\PlanFeaturesOption;
use Setka\Editor\Admin\Options;
use Setka\Editor\Admin\Service\SetkaAPI;
use Setka\Editor\Admin\Service\SetkaAPI\Errors;

class GetCompanyStatusAction extends SetkaAPI\Prototypes\ActionAbstract {

	const ENDPOINT = '\Setka\Editor\Admin\Service\SetkaAPI\Endpoints::COMPANY_STATUS';

	const METHOD = 'GET';

	public function getConstraint() {}

	public function parseResponse() {
		$response = $this->getResponse();

		// Allowed HTTP codes
		switch( $response->getStatusCode() ) {
			// 401 //
			// Token not found
			case $response::HTTP_UNAUTHORIZED:
				$this->getErrors()->add( new Errors\ServerUnauthorizedError() );
				return;

			// 200 // 403 //
			// Active or canceled subscription
			case $response::HTTP_OK:
			case $response::HTTP_FORBIDDEN:
				break;

			default:
				$this->getErrors()->add( new Errors\UnknownError() );
				return;
		}

		if( !$this->parseContent() )
			return;

		$content = $response->content;

		// Status
		if( !$content->has( 'status' ) ) {
			$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
			return;
		} else {
			$statusOption = new Options\SubscriptionStatus\Option();
			$results = $this->api->getValidator()->validate(
				$content->get( 'status' ),
				$statusOption->getConstraint()
			);
			if( count( $results ) !== 0 ) {
				$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
				return;
			}
		}

		// Payment
		if( !$content->has( 'payment_status' ) ) {
			$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
			return;
		} else {
			$paymentStatusOption = new Options\SubscriptionPaymentStatus\Option();
			$results = $this->api->getValidator()->validate(
				$content->get( 'payment_status' ),
				$paymentStatusOption->getConstraint()
			);
			if( count( $results ) !== 0 ) {
				$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
				return;
			}
		}

		// Active until
		if( $response->isOk() ) {
			if( !$content->has( 'active_until' ) ) {
				$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
				return;
			} else {
				$activeUntil = new Options\SubscriptionActiveUntil\Option();
				$results = $this->api->getValidator()
					->validate(
						$content->get( 'active_until' ),
						$activeUntil->getConstraint()
						// Example: 2016-08-25T18:05:35+03:00
					);
				if ( count( $results ) !== 0 ) {
					$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
					return;
				}
			}
		}

		// Plan Features
		if( $content->has('features') ) {
			if(!is_array($content->get('features'))) {
				$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
				return;
			}

			$planFeatures = new PlanFeaturesOption();
			$planFeatures->setValue($content->get('features'));
			if(!$planFeatures->isValid()) {
				$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
				return;
			}
		} else {
			$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
			return;
		}
	}
}
