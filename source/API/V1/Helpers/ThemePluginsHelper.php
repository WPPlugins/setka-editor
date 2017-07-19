<?php
namespace Setka\Editor\API\V1\Helpers;

use Setka\Editor\API\V1\Prototypes\AbstractHelper;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\ParameterBag;
use Setka\Editor\API\V1\Errors;

/**
 * @apiDefine ThemePluginsHelper
 *
 * @apiParam (Data) {Object} data Container with all the data.
 * @apiParam (Data\ThemePluginsHelper) {Object} data.plugins Container with the theme files.
 * @apiParam (Data\ThemePluginsHelper) {Object=0,1,2,3,...} data.plugins.0 Single theme file instance. There can any index here.
 * @apiParam (Data\ThemePluginsHelper) {String} data.plugins.0.url The url of the file. Which WordPress can using.
 * Must looks like url with http(s):// protocol.
 * @apiParam (Data\ThemePluginsHelper) {String='js'} data.plugins.0.filetype The file type.
 * Have set of allowed values.
 *
 * @apiError (Data\ThemePluginsHelper) 400 If `[data][plugins]` is not presented in request or if it is not array. Also this
 * status code returned if one of the file in set not valid.
 */
class ThemePluginsHelper extends AbstractHelper {

	public function handleRequest() {
		$request  = $this->getRequest();
		$response = $this->getResponse();
		$api = $this->getApi();

		// Data not exists
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

		$data = $request->request->get('data');

		// Not exists
		if( !$data->has('plugins') ) {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\Helpers\PluginFiles\PluginsAttributeError() );
			return;
		}

		// Not array
		$plugins = $data->get('plugins');
		if( !is_array( $plugins ) ) {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\Helpers\PluginFiles\PluginsAttributeError() );
			return;
		}

		// Invalid format
		if( !isset( $plugins[0] ) ) {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\Helpers\PluginFiles\PluginsAttributeError() );
			return;
		}

		if(!is_array($plugins[0])) {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\Helpers\PluginFiles\PluginsAttributeError() );
			return;
		}

		$validator = $this->getApi()->getValidator();
		$constraints = $this->getConstraint();

		$results = $validator->validate( $plugins[0], $constraints );
		if( count( $results ) !== 0 ) {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\Helpers\PluginFiles\PluginsAttributeError() );
			return;
		}
	}

	public function getConstraint() {
		return new Constraints\Collection(array(
			'fields' => array(
				'url' => array(
					new Constraints\NotBlank(),
					new Constraints\Url()
				),
				'filetype' => array(
					new Constraints\NotBlank(),
					new Constraints\IdenticalTo(array(
						'value' => 'js'
					))
				)
			),
			'allowExtraFields' => true
		));
	}
}
