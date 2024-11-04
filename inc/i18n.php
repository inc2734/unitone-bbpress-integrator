<?php
/**
 * @package unitone-bbpress-integrator
 * @author inc2734
 * @license GPL-2.0+
 */

/**
 * Load textdomain.
 */
function unitone_bbpress_integrator_load_textdomain() {
	load_plugin_textdomain( 'unitone-bbpress-integrator', false, basename( UNITONE_BBPRESS_INTEGRATOR_PATH ) . '/languages' );
}
add_action( 'init', 'unitone_bbpress_integrator_load_textdomain', 1 );
