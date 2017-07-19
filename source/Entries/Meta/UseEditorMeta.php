<?php
namespace Setka\Editor\Entries\Meta;

use Setka\Editor\Admin\Options;
use Setka\Editor\Plugin;
use Setka\Editor\Prototypes\PostMetas;
use Symfony\Component\Validator\Constraints;

class UseEditorMeta extends PostMetas\AbstractMeta {

	public function __construct() {
		$this->setName( Plugin::_NAME_ . '_use_editor' );
		$this->setVisible( false );
		$this->setDefaultValue( '0' );

		$postTypes = new Options\EditorAccessPostTypes\Option();
		$this->setAllowedPostTyes( $postTypes->getValue() );
	}

	public function buildConstraint() {
		return array(
			new Constraints\NotBlank(),
			new Constraints\Choice(array(
				'choices' => array( '0', '1' )
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
