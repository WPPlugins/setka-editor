<?php
namespace Setka\Editor\Admin\Prototypes\Notices;

class Attributes {

	private $attributes = array();
	private $classes = array();

	public function set_attribute( $key, $value ) {
		if ( $key == 'class' && !is_array($value) ) {
			if ( !is_array($value) ) {
				$value = explode(' ', $value);
			}
			$this->set_classes($value);
			return;
		}
		if ( !is_scalar($value) ) {
			return;
		}
		$this->attributes[$key] = $value;
	}

	public function get_attribute( $key ) {
		if ( $key == 'class' ) {
			$value = implode(' ', $this->get_classes());
			return $value;
		}
		if ( isset($this->attributes[$key]) ) {
			return $this->attributes[$key];
		}
		return '';
	}

	public function get_all_attributes() {
		$attributes = $this->attributes;
		$classes = $this->get_classes();
		if ( !empty($classes) ) {
			$attributes['class'] = implode(' ', $classes);
		}
		return $attributes;
	}

	public function add_class( $class ) {
		if ( !is_scalar($class) ) {
			return false;
		}
		$this->classes[$class] = TRUE;
	}

	public function remove_class( $class ) {
		if ( !is_scalar($class) ) {
			return false;
		}
		if ( isset($this->classes[$class]) ) {
			unset($this->classes[$class]);
		}
	}

	public function set_classes( array $classes ) {
		$this->classes = array(); // clear previous values
		foreach ( $classes as $class ) {
			$this->add_class($class);
		}
	}

	public function get_classes() {
		return array_keys($this->classes);
	}
}
