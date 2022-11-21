<?php
/**
 * Plugin name: unitone bbPress integrator
 * Version: 0.0.3
 * Tested up to: 6.0
 * Requires at least: 6.0
 * Requires PHP: 5.6
 * Description: This plugin makes unitone beautifully display bbPress and adds some features.
 * Author: Takashi Kitajima
 * Author URI: https://2inc.org
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: unitone-bbpress-integrator
 *
 * @package unitone-bbpress-integrator
 * @author inc2734
 * @license GPL-2.0+
 */

namespace UnitoneBbpressIntegrator;

define( 'UNITONE_BBPRESS_INTEGRATOR_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'UNITONE_BBPRESS_INTEGRATOR_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

class Bootstrap {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, '_bootstrap' ) );
	}

	/**
	 * Bootstrap.
	 */
	public function _bootstrap() {
		load_plugin_textdomain( 'unitone-bbpress-integrator', false, basename( __DIR__ ) . '/languages' );

		$theme = wp_get_theme( get_template() );
		if ( 'unitone' !== $theme->template ) {
			add_action(
				'admin_notices',
				function() {
					?>
					<div class="notice notice-warning is-dismissible">
						<p>
							<?php esc_html_e( '[unitone bbPress integrator] Needs the unitone.', 'unitone-bbpress-integrator' ); ?>
						</p>
					</div>
					<?php
				}
			);
			return;
		}

		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/updater.php';

		if ( ! class_exists( 'bbPress' ) ) {
			add_action(
				'admin_notices',
				function() {
					?>
					<div class="notice notice-warning is-dismissible">
						<p>
							<?php esc_html_e( '[unitone bbPress integrator] Needs the bbPress.', 'unitone-bbpress-integrator' ); ?>
						</p>
					</div>
					<?php
				}
			);
			return;
		}

		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/assets.php';
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/breadcrumbs.php';
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/content.php';
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/document-title.php';
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/notice.php';
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/subscribe-button.php';
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/tropic-extra-form.php';
	}
}

require_once( __DIR__ . '/vendor/autoload.php' );
new \UnitoneBbpressIntegrator\Bootstrap();




add_action( 'admin_print_styles', function() {
    if ( get_current_screen()->is_block_editor() ) {
        do_action( 'admin_print_styles-widgets.php' );
    }
} );
add_action( 'admin_print_scripts', function() {
    if ( get_current_screen()->is_block_editor() ) {
        do_action( 'load-widgets.php' );
        do_action( 'widgets.php' );
        do_action( 'sidebar_admin_setup' );
        do_action( 'admin_print_scripts-widgets.php' );
    }
} );
add_action( 'admin_print_footer_scripts', function() {
    if ( get_current_screen()->is_block_editor() ) {
        do_action( 'admin_print_footer_scripts-widgets.php' );
    }
} );
add_action( 'admin_footer', function() {
    if ( get_current_screen()->is_block_editor() ) {
        do_action( 'admin_footer-widgets.php' );
    }
} );
add_action( 'enqueue_block_editor_assets', function() {
    wp_enqueue_script( 'wp-widgets' );
    wp_add_inline_script( 'wp-widgets', 'wp.widgets.registerLegacyWidgetBlock()' );
} );
