<?php
/**
 * Plugin name: unitone bbPress integrator
 * Version: 1.1.0
 * Tested up to: 6.9
 * Requires at least: 6.8
 * Requires PHP: 7.4
 * Requires unitone: 24.9.0
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
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/i18n.php';
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/updater.php';

		$theme = wp_get_theme( get_template() );
		if ( 'unitone' !== $theme->template ) {
			add_action(
				'admin_notices',
				function () {
					?>
					<div class="notice notice-warning is-dismissible">
						<p>
							<?php esc_html_e( '[unitone bbPress integrator] Needs unitone theme.', 'unitone-bbpress-integrator' ); ?>
						</p>
					</div>
					<?php
				}
			);
			return;
		}

		$data = get_file_data(
			__FILE__,
			array(
				'RequiresUnitone' => 'Requires unitone',
			)
		);

		if (
			isset( $data['RequiresUnitone'] ) &&
			version_compare( $theme->get( 'Version' ), $data['RequiresUnitone'], '<' )
		) {
			add_action(
				'admin_notices',
				function () use ( $data ) {
					?>
					<div class="notice notice-warning is-dismissible">
						<p>
							<?php
							echo esc_html(
								sprintf(
									// translators: %1$s: version.
									__(
										'[unitone bbPress Integrator] Needs unitone theme %1$s or more.',
										'unitone-bbpress-integrator'
									),
									'v' . $data['RequiresUnitone']
								)
							);
							?>
						</p>
					</div>
					<?php
				}
			);
			return;
		}

		if ( ! class_exists( 'bbPress' ) ) {
			add_action(
				'admin_notices',
				function () {
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
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/blocks.php';
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/breadcrumbs.php';
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/content.php';
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/document-title.php';
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/like.php';
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/notice.php';
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/subscribe-button.php';
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/templates.php';
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/topic-close-link.php';
		require UNITONE_BBPRESS_INTEGRATOR_PATH . '/inc/tropic-extra-form.php';
	}
}

require_once __DIR__ . '/vendor/autoload.php';
new \UnitoneBbpressIntegrator\Bootstrap();
