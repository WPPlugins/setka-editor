<?php
namespace Setka\Editor\Admin\Service;

use Setka\Editor\API;
use Setka\Editor\Prototypes\Errors\Errors;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class Webhooks {

	public static $actions = array(
		'/webhook/setka-editor/v1/resources/update',
		'/webhook/setka-editor/v1/company_status/update',
		'/webhook/setka-editor/v1/token/check'
	);

	/**
	 * The entry point of Webhooks. All requests to /wp-admin/admin-post.php
	 * All system works with HTTP Foundation objects from Symfony
	 * which is pretty cool.
	 */
	public static function run() {
		foreach( self::$actions as $action ) {
			add_action( 'admin_post_nopriv_' . $action, array( __CLASS__, 'handle_request' ) );
		}
	}

	public static function handle_request() {
		// Create request object
		$request = Request::createFromGlobals();

		// Create response object
		$response = new JsonResponse();
		$responseData = new ParameterBag();
		$responseErrors = new Errors();

		// API doing their stuff
		$api = new API\V1\API( $request, $response, $responseData, $responseErrors );
		try {
			$api->handleRequest();
		}
		catch( API\V1\Exceptions\APIActionDoesntExists $exception ) {
			/**
			 * If action not founded this means what API has newer version then this plugin.
			 * So probably user need to update the plugin.
			 */
			return;
		}
		catch( \Exception $exception ) {
			// Other errors
		}

		//$api->__destruct();
		//unset( $api, $request );

		// Send response
		$responseData->set('errors', $api->getResponseErrors()->allAsArray(true, true, false));
		$response->setData($responseData->all());
		$response->send();
	}
}
