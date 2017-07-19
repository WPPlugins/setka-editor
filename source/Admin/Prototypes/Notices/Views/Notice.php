<?php
namespace Setka\Editor\Admin\Prototypes\Notices\Views;

use Setka\Editor\Admin\Prototypes;

class Notice {

	/**
	 * @var \Setka\Editor\Admin\Prototypes\Notices\NoticeInterface
	 */
	private $model;

	public function render( Prototypes\Notices\NoticeInterface $notice ) {
		$this->model = $notice;
		return sprintf(
			'<div %s>%s</div>',
			$this->prepare_attributes(),
			$this->model->getContent()
		);
	}

	private function prepare_attributes() {
		$attributes = $this->model->getAllAttributes();

		if( !empty( $attributes ) ) {

			$atts = array();
			foreach ( $attributes as $key => $value ) {
				if ( is_array($value) ) {
					$value = implode(' ', array_map('esc_attr', $value));
				} else {
					$value = esc_attr($value);
				}
				$atts[] = sprintf('%s="%s"', esc_attr($key), $value);
			}
			return implode(' ', $atts);
		}
		return '';
	}
}
