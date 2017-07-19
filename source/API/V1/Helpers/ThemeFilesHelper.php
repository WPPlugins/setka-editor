<?php
namespace Setka\Editor\API\V1\Helpers;

use Setka\Editor\API\V1\Prototypes\AbstractHelper;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\ParameterBag;
use Setka\Editor\API\V1\Errors;

/**
 * @apiDefine ThemeFilesHelper
 *
 * @apiParam (Data) {Object} data Container with all the data.
 * @apiParam (Data\ThemeFilesHelper) {Object} data.theme_files Container with the theme files.
 * @apiParam (Data\ThemeFilesHelper) {Object=0,1,2,3,...} data.theme_files.0 Single theme file instance. There can any index here.
 * @apiParam (Data\ThemeFilesHelper) {Numeric} data.theme_files.0.id The numeric id of this file. Can be string or integer. Should be numeric.
 * @apiParam (Data\ThemeFilesHelper) {String} data.theme_files.0.url The url of the file. Which WordPress can using.
 * Must looks like url with http(s):// protocol.
 * @apiParam (Data\ThemeFilesHelper) {String='css','js','svg','json'} data.theme_files.0.filetype The file type.
 * Have set of allowed values.
 *
 * @apiError (Data\ThemeFilesHelper) 400 If `[data][theme_files]` is not presented in request or if it is not array. Also this
 * status code returned if one of the file in set not valid.
 */
class ThemeFilesHelper extends AbstractHelper {

	public function handleRequest() {
		$request = $this->getRequest();
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
		if( !$data->has('theme_files') ) {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\Helpers\ThemeFiles\ThemeFilesAttributeError() );
			return;
		}

		// Not array
		$theme_files = $data->get('theme_files');
		if( !is_array( $theme_files ) ) {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\Helpers\ThemeFiles\ThemeFilesAttributeError() );
			return;
		}

		$validator = $this->getApi()->getValidator();
		$constraints = $this->getConstraint();
		$css = $json = false;

		foreach( $theme_files as $file ) {
			if(!is_array($file)) {
				$response->setStatusCode( $response::HTTP_BAD_REQUEST );
				$api->addError( new Errors\Helpers\ThemeFiles\ThemeFilesAttributeError() );
				return;
			}
			$results = $validator->validate( $file, $constraints );
			if( count( $results ) !== 0 ) {
				$response->setStatusCode( $response::HTTP_BAD_REQUEST );
				$api->addError( new Errors\Helpers\ThemeFiles\ThemeFilesAttributeError() );
				return;
			}
			switch( $file['filetype'] ) {
				case 'css':
					$css = true;
					break;
				case 'json':
					$json = true;
					break;
			}
		}

		if( $css && $json )
			return;

		if( !$css ) {
			$api->addError( new Errors\Helpers\ThemeFiles\CSSError() );
		}
		if( !$json ) {
			$api->addError( new Errors\Helpers\ThemeFiles\JsonError() );
		}

		$response->setStatusCode( $response::HTTP_BAD_REQUEST );
	}

	public function getConstraint() {
		return new Constraints\Collection(array(
			'fields' => array(
				'id' => new Constraints\Type(array(
					'type' => 'numeric'
				)),
				'url' => array(
					new Constraints\NotBlank(),
					new Constraints\Url()
				),
				'filetype' => array(
					new Constraints\NotBlank(),
					new Constraints\Choice(array(
						'choices' => array( 'css', 'js', 'svg', 'json' ),
						'strict' => true
					))
				)
			),
			'allowExtraFields' => true
		));
	}
}
