<?php
namespace Setka\Editor\Admin\Service;

use Setka\Editor\Admin\User\Capabilities\UseEditorCapability;

class Kses {

	/**
	 * Setka Editor requires additional data-attributes and tags in HTML markup for posts.
	 * We just add it to current WordPress list.
	 *
	 * @param $allowedPostTags array The list of html tags and their attributes.
	 * @param $context string The name of context.
	 *
	 * @return array Array with required tags and attributes for Setka Editor.
	 */
	public static function allowedHTML($allowedPostTags, $context) {
		if(
			current_user_can(UseEditorCapability::NAME)
			&&
			$context == 'post'
		) {
			$allowedPostTags = self::addRequiredTagsAndAttributes($allowedPostTags, $context);
		}
		return $allowedPostTags;
	}

	/**
	 * Setka Editor requires additional data-attributes and tags in HTML markup for posts.
	 * We just add it to current WordPress list.
	 *
	 * @param $allowedPostTags array The list of html tags and their attributes.
	 * @param $context string The name of context.
	 *
	 * @return array Array with required tags and attributes for Setka Editor.
	 */
	public static function addRequiredTagsAndAttributes($allowedPostTags, $context) {
		$tags =& TagsAndAttributes::$postTags;
		$attributes =& TagsAndAttributes::$attributes;

		foreach($tags as $tag) {
			if(isset($allowedPostTags[$tag])) {
				$allowedPostTags[$tag] = array_merge($allowedPostTags[$tag], $attributes);
			} else {
				$allowedPostTags[$tag] = $attributes;
			}
		}

		return $allowedPostTags;
	}
}
