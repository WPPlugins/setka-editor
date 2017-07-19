<?php
namespace Setka\Editor\Admin\Prototypes\Pages\Sections;

interface SectionInterface {

	public function getParent();
	public function setParent( $parent );

	public function getName();
	public function setName( $name );

	public function getNodes();
	public function setNodes( $nodes );

	public function getNode( $name );
	public function setNode( Fields\Field $field );

	public function render();

	public function register();

	public function getValue();

	public function getNamesHierarchy();
}
