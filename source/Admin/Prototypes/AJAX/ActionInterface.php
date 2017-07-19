<?php
namespace Setka\Editor\Admin\Prototypes\AJAX;

/**
 * Represent single AJAX action with custom unique name
 * fired on <code>wp_ajax_*</code> and(-or) <code>wp_ajax_nopriv_*</code> actions.
 */
interface ActionInterface {

	public function isEnabledForLoggedIn();
	public function setEnabledForLoggedIn( $enabledForLoggedIn );

	public function isEnabledForNotLoggedIn();
	public function setEnabledForNotLoggedIn( $enabledForNotLoggedIn );

	public function setName( $name );
	public function getName();

	public function getErrors();
	public function setErrors( \Setka\Editor\Prototypes\Errors\ErrorsInterface $errors );
	public function hasErrors();

	public function handleRequest();

	public function send();
}
