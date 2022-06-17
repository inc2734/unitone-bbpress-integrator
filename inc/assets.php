<?php
/**
 * @package unitone-bbpress-integrator
 * @author inc2734
 * @license GPL-2.0+
 */

/**
 * Enqueue assets
 */
add_action(
	'wp_enqueue_scripts',
	function() {
		wp_dequeue_style( 'bbp-default' );

		wp_enqueue_style(
			'unitone/bbpress-integrator',
			UNITONE_BBPRESS_INTEGRATOR_URL . '/dist/css/app.css',
			array(),
			filemtime( UNITONE_BBPRESS_INTEGRATOR_PATH . '/dist/css/app.css' )
		);
	}
);
