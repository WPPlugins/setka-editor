<?php
namespace Setka\Editor;

use Setka\Editor\Service\Compatibility;

class Plugin {

	const NAME    = 'setka-editor';

	const _NAME_  = 'setka_editor';

	const VERSION = '1.8.2';

	const PHP_VERSION_ID_MIN = 50509; // 5.5.9

	const PHP_VERSION_MIN = '5.5.9';

	const WP_VERSION_MIN = '4.0';

	private $path;

	private $dir_path;

	/**
	 * @var bool Can plugin runned in this env?
	 */
	private $compatibility = true;

	/**
	 * Setup plugin object.
	 *
	 * @since 0.0.1
	 *
	 * @param $run_from_file string Main plugin file full path.
	 */
	public function __construct( $run_from_file ) {
		$this->set_path( $run_from_file );
		$this->set_dir_path();
	}

	/**
	 * The main plugin function which runs everything.
	 *
	 * @since 0.0.1
	 */
	public function run() {

		// Check for minimum PHP version
		if(!Compatibility::checkPHP(Plugin::PHP_VERSION_ID_MIN)) {
			// Add warning notice
			Admin\Service\PHPVersionNotice::run();
			$this->compatibility = false;
		}

		// Check for minimum WordPress version
		if(!Compatibility::checkWordPress(self::WP_VERSION_MIN)) {
			// Add warning notice
			Admin\Service\WPVersionNotice::run();
			$this->compatibility = false;
		}

		// If plugin can't be runned in this env just exit.
		if(!$this->compatibility) {
			return;
		}

		/**
		 * Activation. WordPress call this when user click "Activate" link.
		 *
		 * @since 0.0.1
		 */
		register_activation_hook( $this->get_path(), array( '\Setka\Editor\Service\Activation', 'run' ) );

		/**
		 * Uninstall. WordPress call this when user click "Delete" link.
		 *
		 * @since 0.0.2
		 */
		register_uninstall_hook( $this->get_path(), array( '\Setka\Editor\Service\Uninstall', 'run' ) );

		// Translations
		add_action( 'plugins_loaded', array( '\Setka\Editor\Service\Translations', 'load_translations' ), 99 );

		// Register post meta (WP setup sanitizers and other stuff)
		add_action( 'init', array( '\Setka\Editor\Entries\Metas', 'run' ) );

		/**
		 * Register Script & Styles
		 */
		add_action( 'wp_enqueue_scripts', array( '\Setka\Editor\Service\ScriptStyles', 'register' ) );
		add_action( 'wp_enqueue_scripts', array( '\Setka\Editor\Service\ScriptStyles', 'register_theme_resources' ), 1000 );

		/**
		 * Enqueue resources for post markup on frontend
		 */
		add_action( 'wp_enqueue_scripts', array( '\Setka\Editor\Service\ScriptStylesEnqueue', 'wp_enqueue_scripts' ), 1100 );

		/**
		 * If post created with Setka Editor when this post don't need preparation before outputting
		 * content via the_content(). For example: we don't need wpautop(), shortcode_unautop()...
		 * More info (documentation) in \Setka\Editor\Service\TheContent class.
		 *
		 * You can easily disable this stuff and manipulate this filters as you need by simply removing
		 * this two filters bellow. Don't forget what posts created with Setka Editor not should be
		 * parsed by wpautop().
		 *
		 * @see \Setka\Editor\Service\TheContent
		 */
		add_filter( 'the_content', array( '\Setka\Editor\Service\TheContent', 'check_the_content_filters' ), 1 );
		add_filter( 'the_content', array( '\Setka\Editor\Service\TheContent', 'check_the_content_filters_after' ), 999 );

		add_filter( 'the_content', array( '\Setka\Editor\Service\WhiteLabel', 'addLabel' ), 1100 );

		// Load cron tasks handlers only if cron running
		if( defined('DOING_CRON') && DOING_CRON ) {
			add_action( 'init', array( '\Setka\Editor\Admin\Cron\Loader', 'run' )) ;
			//Admin\Cron\Loader::run();
		}

		// WP CLI commands
		CLI\Loader::run();

		if ( is_admin() ) {
			/**
			 * Runs admin only stuff.
			 */
			Admin\Admin::run();
		}
	}

	/**
	 * Returns the main plugin file full path.
	 * For example: /srv/www/site.com/wp-content/plugins/setka-editor/plugin.php.
	 *
	 * @since 0.0.1
	 *
	 * @return string Plugin main file path.
	 */
	public function get_path() {
		return $this->path;
	}

	public function set_path( $path ) {
		$this->path = $path;
	}

	public function get_dir_path() {
		return $this->dir_path;
	}

	/**
	 * Dir path represents the plugin folder path, for example .../wp-content/setka-editor/.
	 *
	 * @since 0.0.2
	 */
	public function set_dir_path() {
		$this->dir_path = plugin_dir_path( $this->get_path() );
	}
}
