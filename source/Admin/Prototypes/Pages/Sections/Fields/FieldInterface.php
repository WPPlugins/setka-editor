<?php
namespace Setka\Editor\Admin\Prototypes\Pages\Sections\Fields;

use Setka\Editor\Admin\Prototypes\Options;

interface FieldInterface {

	public function getParent();
	public function setParent( $parent );

	public function getName();
	public function setName( $name );

	public function getTitle();
	public function setTitle( $title );

	public function getView();
	public function setView( Views\FieldViewInterface $view );

	public function render();

	public function register();

	public function getValue();

	public function getNamesHierarchy();
	public function getNamesHierarchyBasedOnOptions();


	// Each field can based on their own custom Option
	public function getOption();
	public function setOption( Options\OptionInterface $option );
}
