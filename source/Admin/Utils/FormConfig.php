<?php
namespace Setka\Editor\Admin\Utils;

use Symfony\Component\Form\FormInterface;

class FormConfig {

	public static function getConfig(FormInterface $form) {
		$result = array();

		foreach($form as $item) {
			/**
			 * @var $item FormInterface
			 */
			// Nested Form instances

			$current = array();
			if(!empty($item->all())) {
				$current['fragments'] = self::getConfig($item);
			}
			$current['config'] = array(
				'type' => $item->getConfig()->getType()->getBlockPrefix(),
			);
			$current['options'] = $item->getConfig()->getOptions();
			$current['name'] = $item->getConfig()->getName();
			$result[] = $current;
			unset($current);
		}
		unset($item);

		return $result;
	}
}
