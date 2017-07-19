<?php
namespace Setka\Editor\Admin\Pages\Settings;

use Setka\Editor\Admin\Service\FunctionsProxy;
use Setka\Editor\Service\PathsAndUrls;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;
use Setka\Editor\Admin\Options;
use Symfony\Component\Validator\Validation;

class Loader {

	/**
	 * @var \Setka\Editor\Admin\Prototypes\Pages\PageInterface[]
	 */
	public static $pages;

	public static $symfony_translator;

	/**
	 * @var \Twig_Environment
	 */
	public static $symfony_twig;

	/**
	 * @var FormFactoryInterface
	 */
	public static $symfony_form_factory;

	public static function init() {
		add_action('admin_init', array(self::class, 'setup_symfony_components'));

		add_action('admin_menu', array(__CLASS__, 'register_pages'));
	}

	public static function register_pages() {
		self::$pages = array();

		$rootPage = new SetkaEditor\SetkaEditorPage();
		self::$pages[$rootPage->getName()] = $rootPage;

		$page = new Settings\SettingsPage();
		$page->setParentPage($rootPage);
		self::$pages[$page->getName()] = $page;

		/*$page = new UpgradeToPremium\UpgradeToPremium();
		$page->setParentPage($rootPage);
		self::$pages[$page->getName()] = $page;*/

		/*$page = new Help\HelpPage();
		$page->setParentPage($rootPage);
		self::$pages[$page->getName()] = $page;*/

		foreach( self::$pages as $page ) {
			$page->register();
		}
	}

	public static function setup_symfony_components() {

		if(SETKA_EDITOR_CACHE_DIR) {
			$cachePathTranslations = SETKA_EDITOR_CACHE_DIR . 'translate/';
			$cachePathTwig = SETKA_EDITOR_CACHE_DIR . 'twig/';
		} else {
			$cachePathTranslations = null;
			$cachePathTwig = false;
		}

		// Symfony translator
		self::$symfony_translator = new Translator('en', null, $cachePathTranslations);
		self::$symfony_translator->addLoader('xlf', new XliffFileLoader());
		$reflClassForm = new \ReflectionClass(Form::class);
		self::$symfony_translator->addResource(
			'xlf',
			dirname( $reflClassForm->getFileName() ) . '/Resources/translations/validators.ru.xlf',
			'en',
			'validators'
		);

		// Twig
		$reflClass = new \ReflectionClass(TwigRenderer::class);
		self::$symfony_twig = new \Twig_Environment(
			new \Twig_Loader_Filesystem(array(
				PathsAndUrls::get_plugin_dir_path('twig-templates/'),
				dirname( dirname( $reflClass->getFileName() ) ) . '/Resources/views/Form',
			)),
			array(
				'cache' => $cachePathTwig,
			)
		);

		// WP translation functions
		self::$symfony_twig->addGlobal('fn', new FunctionsProxy());

		// Form Renderer Engine
		$formEngine = new TwigRendererEngine(array('form_div_layout.html.twig'), self::$symfony_twig);
		self::$symfony_twig->addRuntimeLoader(new \Twig_FactoryRuntimeLoader(array(
			TwigRenderer::class => function() use ($formEngine) {
				return new TwigRenderer($formEngine);
			}
		)));
		self::$symfony_twig->addExtension(new FormExtension());
		self::$symfony_twig->addExtension(new TranslationExtension(self::$symfony_translator));


		// Set up the Validator component
		$validator = Validation::createValidator();

		// Set up the Form component
		self::$symfony_form_factory =
			Forms::createFormFactoryBuilder()
				->addExtension(new ValidatorExtension($validator))
				->addExtension(new HttpFoundationExtension())
				->getFormFactory();
	}
}
