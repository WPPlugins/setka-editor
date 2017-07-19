<?php
namespace Setka\Editor\Admin\Pages\Settings\SetkaEditor\Account;

class SignIn {

	protected $token;

	protected $nonce;

	/**
	 * @return mixed
	 */
	public function getToken() {
		return $this->token;
	}

	/**
	 * @param mixed $token
	 */
	public function setToken( $token ) {
		$this->token = $token;
	}

	/**
	 * @return mixed
	 */
	public function getNonce() {
		return $this->nonce;
	}

	/**
	 * @param mixed $nonce
	 */
	public function setNonce( $nonce ) {
		$this->nonce = $nonce;
	}
}
