<?php
namespace Setka\Editor\Admin\Service;

class TagsAndAttributes {

	/**
	 * @var array List of required HTML tags for Setka Editor.
	 */
	public static $postTags = array(
		'p',
		'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
		'a',
		'b',
		'strong',
		'i',
		'em',
		'u',
		'del',
		'sup',
		'div',
		'span',
		'figure',
		'figcaption',
		'code',
		'img',
		'hr',
		'style',
	);

	/**
	 * @var array List of HTML tag attributes for Setka Editor.
	 */
	public static $attributes = array(
		'data-ce-tag'           => true,
		'data-col-width'        => true,
		'data-ui-id'            => true,
		'data-editor-version'   => true,
		'data-reset-type'       => true,
		'data-layout'           => true,
		'data-anim-type'        => true,
		'data-anim-name'        => true,
		'data-anim-hash'        => true,
		'data-anim'             => true,
		'data-anim-direction'   => true,
		'data-anim-zoom'        => true,
		'data-anim-shift'       => true,
		'data-anim-rotation'    => true,
		'data-anim-opacity'     => true,
		'data-anim-duration'    => true,
		'data-anim-delay'       => true,
		'data-anim-trigger'     => true,
		'data-anim-loop'        => true,
		'data-embed-mode'       => true,
		'data-embed-link'       => true,
		'data-embed-responsive' => true,
		'style'                 => true,
	);

	public static function getPostTags() {
		return self::$postTags;
	}

	public static function getAttributes() {
		return self::$attributes;
	}
}
