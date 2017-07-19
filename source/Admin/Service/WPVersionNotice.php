<?php
namespace Setka\Editor\Admin\Service;

use Setka\Editor\Plugin;

class WPVersionNotice {

	public static function run() {
		add_action('admin_init', array(self::class, 'init'));
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
	    global $wp_version;
		?>
		<div id="setka-editor-notice-wp-min-version" class="notice setka-editor-notice notice-error setka-editor-notice-error">
            <p class="notice-title setka-editor-notice-title"><?php _e('Setka Editor plugin error', Plugin::NAME); ?></p>
            <p><?php _e('Your WordPress version is obsolete. Please update your WordPress and then activate plugin again.', Plugin::NAME); ?></p>
            <p><?php printf(
                    /* translators: %1$s - current WordPress version in X.Y.Z format. */
                    __('Your current WordPress version: <b>%1$s</b>', Plugin::NAME),
                    esc_html($wp_version)
                );
                echo '<br>';
	            printf(
	                /* translators: %1$s - required WordPress version in X.Y.Z format. */
		            __('Minimal version for Setka Editor plugin: <b>%1$s</b>', Plugin::NAME),
		            esc_html(Plugin::WP_VERSION_MIN)
	            );
            ?></p>
            <p><a href="https://editor.setka.io/support" target="_blank"><?php _e('Contact Setka Editor Support', Plugin::NAME); ?></a></p>
        </div>
		<?php
	}
}
