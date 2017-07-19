<?php
namespace Setka\Editor\Admin\Prototypes\AJAX;

/**
 * This class using in most cases for creating AJAX actions in plugin.
 */
abstract class AbstractAction extends API implements ActionInterface {

	protected $enabledForLoggedIn = true;

	protected $enabledForNotLoggedIn = true;

	protected $name;

	/**
	 * @var \Setka\Editor\Prototypes\Errors\ErrorsInterface
	 */
	protected $errors;

	public function __construct( $name ) {
		$this->setName( $name );
	}

	public function isEnabledForLoggedIn() {
		return $this->enabledForLoggedIn;
	}

	public function setEnabledForLoggedIn( $enabledForLoggedIn ) {
		$this->enabledForLoggedIn = (bool) $enabledForLoggedIn;
	}

	public function isEnabledForNotLoggedIn() {
		return $this->enabledForNotLoggedIn;
	}

	public function setEnabledForNotLoggedIn( $enabledForNotLoggedIn ) {
		$this->enabledForNotLoggedIn = (bool) $enabledForNotLoggedIn;
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	public function getErrors() {
		if( !is_a( $this->errors, '\Setka\Editor\Prototypes\Errors\ErrorsInterface' ) ) {
			$this->setErrors( new \Setka\Editor\Prototypes\Errors\Errors() );
		}
		return $this->errors;
	}

	public function setErrors( \Setka\Editor\Prototypes\Errors\ErrorsInterface $errors ) {
		$this->errors = $errors;
	}

	public function hasErrors() {
		return $this->getErrors()->hasErrors();
	}

	abstract public function handleRequest();

	public function prepareResponse() {
		if( $this->hasErrors() ) {
			$data = $this->getResponseData();
			$data['errors'] = $this->getErrors()->allAsArray();
			$this->setResponseData( $data );
		}
	}

	/**
	 * @see wp_send_json()
	 */
	public function send() {
		$this->prepareResponse();
		$this->response->setData($this->getResponseData());
		$this->response->send();

		// This part grabbed from wp_send_json()
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			wp_die();
		else
			die;
	}
}
