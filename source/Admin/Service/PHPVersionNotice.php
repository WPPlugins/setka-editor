<?php
namespace Setka\Editor\Admin\Service;

use Setka\Editor\Plugin;

class PHPVersionNotice {

	public static function run() {
	    add_action('admin_init', array(__CLASS__, 'init'));
	}

	public static function init() {
		if(
            current_user_can('update_core') ||
            current_user_can('install_plugins') ||
            current_user_can('activate_plugins')
        ) {
            self::addActions();
		}
    }

	public static function addActions() {
		add_action('admin_enqueue_scripts', array('Setka\Editor\Admin\Service\ScriptStyles', 'register'));
		add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueueAssets'), 11);
		add_action('admin_notices', array(__CLASS__, 'renderNotice'));
    }

	public static function enqueueAssets() {
		wp_enqueue_style(Plugin::NAME . '-wp-admin-main');
	}

	public static function renderNotice() {
	    ?>
		<div id="setka-editor-notice-php-min-version" class="notice setka-editor-notice notice-error setka-editor-notice-error">
            <p class="notice-title setka-editor-notice-title"><?php _e('Setka Editor plugin error', Plugin::NAME); ?></p>
            <p><?php _e('Oh, no! Seems you have an old PHP version that is not compatible with Setka Editor plugin. Please update your PHP plugin by following these easy instructions and then try activating the plugin again.', Plugin::NAME); ?></p>
            <p><?php printf(
                    /* translators: %1$s - current PHP version in X.Y.Z format. */
					__('Your current PHP version: <b>%1$s</b>.', Plugin::NAME),
					esc_html(phpversion())
				);
                echo '<br>';
                printf(
                    /* translators: %1$s - required PHP version in X.Y.Z format. */
					__('Minimal PHP version required for Setka Editor plugin: <b>%1$s</b>.', Plugin::NAME),
					esc_html(Plugin::PHP_VERSION_MIN)
				);
                echo '<br>';
                printf(
	                /* translators: %1$s - link to WordPress.org requirements page in native language. For example, for russian: https://ru.wordpress.org/about/requirements/ (please note ru. before wordpress.org). */
					__('<a href="%1$s" target="_blank">WordPress highly recommends</a> using PHP 7 or greater version.', Plugin::NAME),
					esc_url(__('https://wordpress.org/about/requirements/', Plugin::NAME))
				); ?></p>
            <p><a href="https://editor.setka.io/support" target="_blank"><?php _e('Contact Setka Editor Support', Plugin::NAME); ?></a></p>
		</div>
		<?php
	}
}
