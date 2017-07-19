<?php
namespace Setka\Editor\Admin\Service\SetkaAPI;

class AuthCredits {

	/**
	 * @var string The token to auth with.
	 */
	private $token;

	public function __construct( $token ) {
		$this->setToken( $token );
	}

	public function getToken() {
		return $this->token;
	}

	public function setToken( $token ) {
		$this->token = $token;
	}

	public function getCreditsAsArray() {
		return array(
			'token' => $this->getToken()
		);
	}
}
