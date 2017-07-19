<?php
namespace Setka\Editor\Admin\Prototypes\Notices;

interface NoticeInterface {

	public function setPrefix( $prefix );
	public function getPrefix();

	public function setName( $name );
	public function getName();

	public function setAttribute( $key, $value );
	public function getAttribute( $key );
	public function getAllAttributes();
	public function addClass( $class );
	public function removeClass( $class );
	public function setClasses( array $classes );
	public function getClasses();

	public function addDismissible();
	public function removeDismissible();

	public function getContent();
	public function setContent( $content );

	public function getView();
	public function render();

	public function handleRequest();

	public function isRelevant();

	public function dismiss();
	public function getDismissUrlArg();
	public function getDismissUrl();
	public function redirectAfterDismiss();
}
