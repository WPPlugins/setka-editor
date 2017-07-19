<?php
namespace Setka\Editor\Admin\Prototypes\Pages\Sections;

abstract class Section implements SectionInterface {

	protected $parent;

	protected $name;

	protected $title;

	protected $nodes;

	public function getParent() {
		return $this->parent;
	}

	public function setParent( $parent ) {
		$this->parent = $parent;
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = (string)$name;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle( $title ) {
		$this->title = (string)$title;
	}

	public function getNodes() {
		return $this->nodes;
	}

	public function setNodes( $nodes ) {
		$this->nodes = array();

		foreach( $nodes as $node ) {
			$this->setNode( $node );
		}
	}

	public function getNode( $name ) {
		$nodes = $this->getNodes();
		if( !empty( $nodes ) && isset( $nodes[$name] ) ) {
			return $nodes[$name];
		}
		return false;
	}

	public function setNode( Fields\Field $field ) {
		$this->nodes[ $field->getName() ] = $field;
	}

	abstract public function render();

	public function register() {
		add_settings_section(
			$this->getName(),
			$this->getTitle(),
			array( $this, 'render' ),
			$this->getParent()->getMenuSlug()
		);

		$nodes = $this->getNodes();
		if( !empty( $nodes ) ) {
			foreach( $nodes as $node ) {
				$node->register();
			}
		}
	}

	public function getValue() {
		$value = $this->getParent()->getValue();
		return $value[ $this->getName() ];
	}

	/**
	 * Get hierarchy names of all parents. Used for building name html attr.
	 */
	public function getNamesHierarchy() {
		$names = $this->getParent()->getNamesHierarchy();
		$names[] = $this->getName();
		return $names;
	}

	public function getMenuSlug() {
		return $this->getParent()->getMenuSlug();
	}
}
