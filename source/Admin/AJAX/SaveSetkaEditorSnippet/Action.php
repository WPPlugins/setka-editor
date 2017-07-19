<?php
namespace Setka\Editor\Admin\AJAX\SaveSetkaEditorSnippet;

use Setka\Editor\Admin\Prototypes\AJAX\AbstractAction;
use Setka\Editor\Admin\AJAX\Errors;
use Setka\Editor\Admin\Service\SetkaAPI;
use Setka\Editor\Admin\Options\Token\Option as TokenOption;
use Setka\Editor\Admin\User\Capabilities\UseEditorCapability;
use Setka\Editor\Plugin;
use Setka\Editor\Service\SetkaAccount\Account;
use Setka\Editor\Service\WPErrorUtils;

class Action extends AbstractAction {

	public function __construct() {
		parent::__construct( Plugin::_NAME_ . '_save_setka_editor_snippet' );
		$this->setEnabledForNotLoggedIn(false);
	}

	public function handleRequest() {
		parent::lateConstruct();

		// Shortcuts for the objects
		$request = $this->getRequest();
		//$response = $this->getResponse();
		$errors = $this->getErrors();

		// Check for permissions
		if( !current_user_can( UseEditorCapability::NAME ) ) {
			$errors->add( new Errors\PermissionDeniedError() );
			$this->send();
		}

		// Data missed
		if( !$request->request->has('data') ) {
			$errors->add( new Errors\InvalidRequestDataError() );
			$this->send();
		}

		// Check for Setka account setted up
		if( !Account::is_logged_in() ) {
			$errors->add( new Errors\SetkaAccountNotSettedUpError() );
			$this->send();
		}

		// Try to make request to Setka API
		$api = new SetkaAPI\API();
		$token = new TokenOption();
		$api->setAuthCredits( new SetkaAPI\AuthCredits( $token->getValue() ) );
		// Save action
		$action = new SetkaAPI\Actions\SaveSnippetAction();
		$action->setErrors( $errors );
		$action->setRequestDetails($this->convertData($request->request->get('data')->all()));
		// Make request to setka server and validate response
		$api->request( $action );

		// answer to the browser
		$this->send();
	}

	protected function convertData( $data ) {

		$data = array(
			'body' => array(
				'snippet' => $data
			)
		);

		if( isset( $data['body']['snippet']['theme_id'] ) ) {
			$data['body']['theme_id'] = $data['body']['snippet']['theme_id'];
			unset( $data['body']['snippet']['theme_id'] );
		}

		return $data;
	}
}
