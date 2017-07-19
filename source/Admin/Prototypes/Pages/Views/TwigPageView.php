<?php
namespace Setka\Editor\Admin\Prototypes\Pages\Views;

use Setka\Editor\Admin\Forms\SignUp;
use Setka\Editor\Admin\Prototypes;
use Setka\Editor\Admin\Pages\Settings\Loader;
use Setka\Editor\Plugin;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class TwigPageView implements PageViewInterface {

	/**
	 * @var string Twig template path.
	 */
	protected $template = 'admin/settings/common/page.html.twig';

	/**
	 * @var string[] Array with context for Twig.
	 */
	protected $context = array();

	public function render(Prototypes\Pages\PageInterface $element) {
		echo Loader::$symfony_twig->render($this->getTemplate(), $this->getContext());
	}

	/**
	 * @return string
	 */
	public function getTemplate() {
		return $this->template;
	}

	/**
	 * @param string $template
	 */
	public function setTemplate($template) {
		$this->template = $template;
	}

	/**
	 * @return array
	 */
	public function getContext() {
		return $this->context;
	}

	/**
	 * @param array $context Content for Twig
	 */
	public function setContext(array $context) {
		$this->context = $context;
	}
}
