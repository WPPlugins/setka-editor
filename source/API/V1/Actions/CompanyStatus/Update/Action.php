<?php
namespace Setka\Editor\API\V1\Actions\CompanyStatus\Update;

use Setka\Editor\Admin\Cron\Tasks\SyncAccount\SyncAccountTask;
use Setka\Editor\Admin\Options;
use Setka\Editor\API\V1\Prototypes\AbstractAction;
use Setka\Editor\API\V1\Helpers;
use Setka\Editor\API\V1\Errors;
use Setka\Editor\API\V1;
use Symfony\Component\HttpFoundation\ParameterBag;

class Action extends AbstractAction {

	public function __construct(V1\API $api) {
		parent::__construct($api);
		$this->setEndpoint('company_status/update');
	}

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

		// Convert data from array to ParameterBag if not
		if( is_array( $request->request->get('data') ) ) {
			$request->request->set(
				'data',
				new ParameterBag( $request->request->get('data') )
			);
		}

		if( !is_a( $request->request->get('data'), ParameterBag::class ) ) {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\RequestDataError() );
			return;
		}

		// Validate token
		$token_action = new Helpers\Auth\Helper( $this->getApi() );
		$token_action->handleRequest();
		// Token not valid
		if( !$response->isOk() ) {
			return;
		}

		/**
		 * @var $data ParameterBag
		 */
		$data = $request->request->get('data');

		// Validate status
		if( $data->has('status') ) {
			$subscriptionStatusOption = new Options\SubscriptionStatus\Option();
			$subscriptionStatusOption->setValue($data->get('status'));

			if( !$subscriptionStatusOption->isValid() ) {
				$response->setStatusCode( $response::HTTP_BAD_REQUEST );
				$api->addError( new Errors\CompanyStatus\StatusAttributeError() );
				return;
			}
		} else {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\CompanyStatus\StatusAttributeError() );
			return;
		}

		// Validate payment_status
		if( $data->has('payment_status') ) {
			$paymentStatusOption = new Options\SubscriptionPaymentStatus\Option();
			$paymentStatusOption->setValue($data->get('payment_status'));

			if( !$paymentStatusOption->isValid() ) {
				$response->setStatusCode( $response::HTTP_BAD_REQUEST );
				$api->addError( new Errors\CompanyStatus\PaymentStatusAttributeError() );
				return;
			}
		} else {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\CompanyStatus\PaymentStatusAttributeError() );
			return;
		}

		// Validate active_until
		$activeUntilOption = new Options\SubscriptionActiveUntil\Option();
		if( $data->has('active_until') ) {
			$activeUntilOption->setValue($data->get('active_until'));

			if( !$activeUntilOption->isValid() ) {
				$response->setStatusCode( $response::HTTP_BAD_REQUEST );
				$api->addError( new Errors\CompanyStatus\ActiveUntilAttributeError() );
				return;
			}
		}

		// Validate Plan Features
		if($data->has('features')) {
			$features = $data->get('features');

			if(!is_array($features)) {
				$response->setStatusCode($response::HTTP_BAD_REQUEST);
				$api->addError(new Errors\CompanyStatus\PlanFeaturesError());
				return;
			}

			// Fix for white label flag
			if(isset($features['white_label']) && !is_bool($features['white_label'])) {
				if($features['white_label'] == 'false') {
					$features['white_label'] = false;
				}
				elseif($features['white_label'] == 'true') {
					$features['white_label'] = true;
				}
			}

			// Fix for white label markup
			if(isset($features['white_label_html']) && is_string($features['white_label_html'])) {
				$features['white_label_html'] = stripcslashes($features['white_label_html']);
			}

			// Replace value in object
			$data->set('features', $features);
			unset($features);

			$planFeatures = new Options\PlanFeatures\PlanFeaturesOption();
			$planFeatures->setValue($data->get('features'));
			if(!$planFeatures->isValid()) {
				$response->setStatusCode( $response::HTTP_BAD_REQUEST );
				$api->addError( new Errors\CompanyStatus\PlanFeaturesError() );
				return;
			}
		} else {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\CompanyStatus\PlanFeaturesError() );
			return;
		}


		// All data can be updated.
		$subscriptionStatusOption->updateValue( $data->get('status') );
		$paymentStatusOption->updateValue( $data->get('payment_status') );

		// Remove all previously tasks
		$syncAccountTask = new SyncAccountTask();
		$syncAccountTask->unRegisterHook();

		if( $data->has('active_until') ) {
			$activeUntilOption->updateValue( $data->get('active_until') );
			$datetime = \DateTime::createFromFormat(\DateTime::ISO8601, $activeUntilOption->getValue());
			if($datetime) {
				$syncAccountTask->setTimestamp($datetime->getTimestamp());
				$syncAccountTask->register();
			}
		} else {
			$activeUntilOption->delete();
		}
		$planFeatures->flush();

		$response->setStatusCode( $response::HTTP_OK );
	}

	public function getConstraint() {}
}
