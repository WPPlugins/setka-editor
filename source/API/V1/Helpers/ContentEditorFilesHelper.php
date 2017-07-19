<?php
namespace Setka\Editor\API\V1\Helpers;

use Setka\Editor\API\V1\Prototypes\AbstractHelper;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\ParameterBag;
use Setka\Editor\Admin\Options\EditorVersion\Option as EditorVersion;
use Setka\Editor\API\V1\Errors;

/**
 * @apiDefine ContentEditorFilesHelper
 *
 * @apiParam (Data) {Object} data Container with all the data.
 * @apiParam (Data\ContentEditorFilesHelper) {Object} data.content_editor_files Container with the content editor files.
 * @apiParam (Data\ContentEditorFilesHelper) {Object=0,1,2,3,...} data.content_editor_files.0 Single file instance.
 * There can any index here.
 * @apiParam (Data\ContentEditorFilesHelper) {Numeric} data.content_editor_files.0.id The numeric id of this file.
 * Can be string or integer. Should be numeric.
 * @apiParam (Data\ContentEditorFilesHelper) {String} data.content_editor_files.0.url The url of the file. Which WordPress can using.
 * Must looks like url with http(s):// protocol.
 * @apiParam (Data\ContentEditorFilesHelper) {String='css','js'} data.content_editor_files.0.filetype The file type.
 * Have set of allowed values.
 *
 * @apiError (Data\ContentEditorFilesHelper) 400 If `[data][content_editor_files]` is not presented in request or if it is not array.
 * Also this status code returned if one of the file in set not valid.
 */
class ContentEditorFilesHelper extends AbstractHelper {

	public function handleRequest() {
		$request = $this->getRequest();
		$response = $this->getResponse();
		$api = $this->getApi();

		// Not exists
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
		if( !$data->has('content_editor_files') ) {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\Helpers\ContentEditorFiles\ContentEditorFilesError() );
			return;
		}

		// Check version
		if( !$data->has('content_editor_version') ) {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\Helpers\ContentEditorFiles\ContentEditorVersionError() );
			return;
		}

		$editorVersion = new EditorVersion();
		$editorVersion->setValue( $data->get('content_editor_version') );
		if( !$editorVersion->isValid() ) {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\Helpers\ContentEditorFiles\ContentEditorVersionError() );
			return;
		}
		unset( $editorVersion );

		// Not array
		$files = $data->get('content_editor_files');
		if( !is_array( $files ) ) {
			$response->setStatusCode( $response::HTTP_BAD_REQUEST );
			$api->addError( new Errors\Helpers\ContentEditorFiles\ContentEditorFilesError() );
			return;
		}

		$validator = $this->getApi()->getValidator();
		$constraints = $this->getConstraint();
		$css = $js = false;

		foreach( $files as $file ) {
			if(!is_array($file)) {
				$response->setStatusCode( $response::HTTP_BAD_REQUEST );
				$api->addError( new Errors\Helpers\ContentEditorFiles\ContentEditorFilesError() );
				return;
			}
			$results = $validator->validate( $file, $constraints );
			if( count( $results ) !== 0 ) {
				$response->setStatusCode( $response::HTTP_BAD_REQUEST );
				$api->addError( new Errors\Helpers\ContentEditorFiles\ContentEditorFilesError() );
				return;
			}
			switch( $file['filetype'] ) {
				case 'css':
					$css = true;
					break;
				case 'js':
					$js = true;
					break;
			}
		}

		if( $css && $js )
			return;

		$response->setStatusCode( $response::HTTP_BAD_REQUEST );
		$api->addError( new Errors\Helpers\ContentEditorFiles\ContentEditorFilesError() );
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
						'choices' => array( 'css', 'js' ),
						'strict' => true
					))
				)
			),
			'allowExtraFields' => true
		));
	}
}
