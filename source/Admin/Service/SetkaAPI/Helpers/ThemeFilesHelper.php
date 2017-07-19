<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Helpers;

use Setka\Editor\Admin\Service\SetkaAPI;
use Setka\Editor\Admin\Service\SetkaAPI\Errors;
use Symfony\Component\Validator\Constraints;

class ThemeFilesHelper extends SetkaAPI\Prototypes\HelperAbstract {

	public function buildResponseConstraints() {

		return array(
			new Constraints\NotBlank(),
			new Constraints\Collection(array(
				'fields' => array(
					'id' => array(
						new Constraints\NotBlank(),
						new Constraints\Type(array(
							'type' => 'numeric'
						))
					),
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
			))
		);
	}

	public function parseResponse() {
		$response = $this->getResponse();
		// Not exists
		if( !$response->content->has('theme_files') ) {
			$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
			return;
		}

		// Not array
		if( !is_array( $response->content->get('theme_files') ) ) {
			$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
			return;
		}

		$validator = $this->api->getValidator();
		$constraints = $this->getResponseConstraints();

		// Check each file
		$css = $json = false;
		foreach( $response->content->get('theme_files') as $file ) {

			if(!is_array($file)) {
				$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
				return;
			}

			$results = $validator->validate( $file, $constraints );
			if( count( $results ) !== 0 ) {
				$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
				return;
			}
			switch( $file['filetype'] ) {
				case 'css':
					$css = true;
					break;
				case 'json':
					$json = true;
					break;
				default:
					break;
			}
		}

		// Fine. Required CSS & JS presented in API response
		if( $css && $json )
			return;

		$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
	}
}
