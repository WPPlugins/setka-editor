<?php
namespace Setka\Editor\Service;

use Setka\Editor\Admin\Options\PlanFeatures\PlanFeaturesOption;
use Setka\Editor\Admin\Options\WhiteLabel\Utilities;
use Setka\Editor\Entries\Meta;

class WhiteLabel {

	public static function addLabel($content) {
		if(Utilities::is_white_label_enabled()) {

			$use_editor_meta = new Meta\UseEditorMeta();
			$use_editor_meta->setPostId( get_the_ID() );
			if( $use_editor_meta->getValue() === '1' ) {
				$whiteLabel = new PlanFeaturesOption();
				$content .= $whiteLabel->getNode('white_label_html')->getValue();
			}
		}
		return $content;
	}
}
