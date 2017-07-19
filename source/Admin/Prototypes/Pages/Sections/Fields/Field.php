<?php
namespace Setka\Editor\Admin\Prototypes\Pages\Sections\Fields;

use Setka\Editor\Admin\Prototypes\Options;

abstract class Field implements FieldInterface {

	protected $parent;

	protected $name;

	protected $title;

	protected $view;

	protected $option;

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

	public function getView() {
		return $this->view;
	}

	public function setView( Views\FieldViewInterface $view ) {
		$this->view = $view;
	}

	public function render() {
		$this->getView()->render();
	}

	public function register() {
		add_settings_field(
			$this->getName(),
			$this->getTitle(),
			array( $this->getView(), 'render' ),

			$this->getMenuSlug(),

			$this->getParent()->getName(),
			array(
				'label_for' => $this->getView()->getName()
			)
		);
	}

	public function getValue() {
		$option = $this->getOption();
		// This field have their own Option
		if( $option ) {
			return $option->getValue();
		}
		// This field based on parent Option
		else {
			$value = $this->getParent()->getValue();
			return $value[$this->getName()];
		}
	}

	/**
	 * Get hierarchy names of all parents. Used for building name html attr.
	 */
	public function getNamesHierarchy() {
		$names = $this->getParent()->getNamesHierarchy();
		$names[] = $this->getName();
		return $names;
	}

	public function getNamesHierarchyBasedOnOptions() {
		$option = $this->getOption();
		// This field have their own Option
		if( $option ) {
			return array( $option->getName() );
		}
		return $this->getNamesHierarchy();
	}

	public function getOption() {
		return $this->option;
	}

	/**
	 * Each page & field can have their own Options (objects which stores the data)
	 * (sections can't use their own Options right now).
	 *
	 * @param Options\OptionInterface $option
	 */
	public function setOption( Options\OptionInterface $option ) {
		$this->option = $option;
	}

	public function getMenuSlug() {
		return $this->getParent()->getMenuSlug();
	}
}
