<?php
namespace Setka\Editor\Admin\Pages\Settings\SetkaEditor\SignUp;

class SignUp {

	protected $accountType;

	protected $email;

	protected $firstName;

	protected $lastName;

	protected $region;

	protected $companyDomain;

	protected $companyName;

	protected $companySize;

	protected $companyDepartment;

	protected $password;

	protected $token;

	protected $termsAndConditions = false;

	protected $whiteLabel = false;

	protected $nonce;

	protected $config;

	/**
	 * @return mixed
	 */
	public function getAccountType() {
		return $this->accountType;
	}

	/**
	 * @param mixed $accountType
	 */
	public function setAccountType( $accountType ) {
		$this->accountType = $accountType;
	}

	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail( $email ) {
		$this->email = $email;
	}

	/**
	 * @return mixed
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * @param mixed $firstName
	 */
	public function setFirstName( $firstName ) {
		$this->firstName = $firstName;
	}

	/**
	 * @return mixed
	 */
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * @param mixed $lastName
	 */
	public function setLastName( $lastName ) {
		$this->lastName = $lastName;
	}

	/**
	 * @return mixed
	 */
	public function getRegion() {
		return $this->region;
	}

	/**
	 * @param mixed $region
	 */
	public function setRegion( $region ) {
		$this->region = $region;
	}

	/**
	 * @return mixed
	 */
	public function getCompanyDomain() {
		return $this->companyDomain;
	}

	/**
	 * @param mixed $companyDomain
	 */
	public function setCompanyDomain( $companyDomain ) {
		$this->companyDomain = $companyDomain;
	}

	/**
	 * @return mixed
	 */
	public function getCompanyName() {
		return $this->companyName;
	}

	/**
	 * @param mixed $companyName
	 */
	public function setCompanyName( $companyName ) {
		$this->companyName = $companyName;
	}

	/**
	 * @return mixed
	 */
	public function getCompanySize() {
		return $this->companySize;
	}

	/**
	 * @param mixed $companySize
	 */
	public function setCompanySize( $companySize ) {
		$this->companySize = $companySize;
	}

	/**
	 * @return mixed
	 */
	public function getCompanyDepartment() {
		return $this->companyDepartment;
	}

	/**
	 * @param mixed $companyDepartment
	 */
	public function setCompanyDepartment( $companyDepartment ) {
		$this->companyDepartment = $companyDepartment;
	}

	/**
	 * @return mixed
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param mixed $password
	 */
	public function setPassword( $password ) {
		$this->password = $password;
	}

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
	 * @return bool
	 */
	public function isTermsAndConditions() {
		return $this->termsAndConditions;
	}

	/**
	 * @param bool $termsAndConditions
	 */
	public function setTermsAndConditions( $termsAndConditions ) {
		$this->termsAndConditions = $termsAndConditions;
	}

	/**
	 * @return bool
	 */
	public function isWhiteLabel() {
		return $this->whiteLabel;
	}

	/**
	 * @param bool $whiteLabel
	 */
	public function setWhiteLabel( $whiteLabel ) {
		$this->whiteLabel = $whiteLabel;
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

	/**
	 * @return mixed
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * @param mixed $config
	 */
	public function setConfig( $config ) {
		$this->config = $config;
	}
}
