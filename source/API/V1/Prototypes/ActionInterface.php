<?php
namespace Setka\Editor\API\V1\Prototypes;

use Setka\Editor\API\V1;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ActionInterface {

	public function __construct( V1\API $api );

	public function getApi();
	public function setApi( V1\API $api );

	public function getEndpoint();
	public function setEndpoint( $endpoint );

	public function getRequest();

	public function getResponse();

	/**
	 * Handle request.
	 */
	public function handleRequest();

	public function getConstraint();
}
