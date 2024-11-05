<?php
/**
 * @package unitone-bbpress-integrator
 * @author inc2734
 * @license GPL-2.0+
 */

/**
 * Dequeue assets
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_dequeue_style( 'bbp-default' );
	}
);

/**
 * Enqueue assets
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style(
			'unitone/bbpress-integrator',
			UNITONE_BBPRESS_INTEGRATOR_URL . '/dist/css/app.css',
			array( 'unitone' ),
			filemtime( UNITONE_BBPRESS_INTEGRATOR_PATH . '/dist/css/app.css' )
		);
	}
);

/**
 * Enqueue editor assets
 */
add_action(
	'enqueue_block_assets',
	function () {
		if ( ! is_admin() ) {
			return;
		}

		wp_enqueue_style(
			'unitone/bbpress-integrator',
			UNITONE_BBPRESS_INTEGRATOR_URL . '/dist/css/app.css',
			array( 'unitone' ),
			filemtime( UNITONE_BBPRESS_INTEGRATOR_PATH . '/dist/css/app.css' )
		);
	}
);

/**
 * Translate blocks.
 */
function unitone_bbpress_integrator_enqueue_block_editor_assets() {
	foreach ( WP_Block_Type_Registry::get_instance()->get_all_registered() as $block_type => $block ) {
		if ( 0 === strpos( $block_type, 'unitone-bbpress-integrator/' ) ) {
			$handle = str_replace( '/', '-', $block_type ) . '-editor-script';
			wp_set_script_translations( $handle, 'unitone-bbpress-integrator', UNITONE_BBPRESS_INTEGRATOR_PATH . '/languages' );
		}
	}
}
add_action( 'enqueue_block_editor_assets', 'unitone_bbpress_integrator_enqueue_block_editor_assets' );
