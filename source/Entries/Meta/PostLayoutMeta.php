<?php
namespace Setka\Editor\Entries\Meta;

use Setka\Editor\Admin\Options;
use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\PostMetas;
use Symfony\Component\Validator\Constraints;

class PostLayoutMeta extends PostMetas\AbstractMeta {

	public function __construct() {
		$this->setName( Plugin::_NAME_ . '_post_layout' );
		$this->setVisible( false );
		$this->setDefaultValue( '' );

		$postTypes = new Options\EditorAccessPostTypes\Option();
		$this->setAllowedPostTyes( $postTypes->getValue() );
	}

	public function buildConstraint() {
		return array(
			new Constraints\NotBlank(),
			new Constraints\Length(array(
				'min' => 2
			))
		);
	}

	public function sanitize( $value ) {
		$value = sanitize_text_field( $value );
		$this->setValue( $value );
		if( $this->isValid() ) {
			return $value;
		}
		return $this->getDefaultValue();
	}
}