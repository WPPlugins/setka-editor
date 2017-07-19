<?php
namespace Setka\Editor\Admin\Service\SetkaAPI\Helpers;

use Setka\Editor\Admin\Service\SetkaAPI;
use Setka\Editor\Admin\Service\SetkaAPI\Errors;
use Symfony\Component\Validator\Constraints;

class PluginsHelper extends SetkaAPI\Prototypes\HelperAbstract {

	public function buildResponseConstraints() {

		return array(
			new Constraints\NotBlank(),
			new Constraints\Collection(array(
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
			))
		);
	}

	public function parseResponse() {
		$response = $this->getResponse();
		// Not exists
		if ( ! $response->content->has( 'plugins' ) ) {
			$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
			return;
		}

		// Not array
		if( !is_array( $response->content->get('plugins') ) ) {
			$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
			return;
		}

		$plugins = $response->content->get('plugins');

		// Array must contain one element at now
		if( count( $plugins ) !== 1 ) {
			$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
			return;
		}

		if( !isset( $plugins[0] ) ) {
			$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
			return;
		}

		if(!is_array($plugins[0])) {
			$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
			return;
		}

		$validator = $this->api->getValidator();
		$constraints = $this->getResponseConstraints();

		$results = $validator->validate( $plugins[0], $constraints );
		if( count( $results ) !== 0 ) {
			$this->getErrors()->add( new Errors\ResponseBodyInvalidError() );
			return;
		}
	}
}
