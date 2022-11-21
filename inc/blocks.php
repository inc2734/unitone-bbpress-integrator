<?php
/**
 * @package unitone-bbpress-integrator
 * @author inc2734
 * @license GPL-2.0+
 */

/**
 * Register blocks.
 */
function unitone_bbpress_integrator_register_blocks() {
	register_block_type( UNITONE_BBPRESS_INTEGRATOR_PATH . '/dist/blocks/login' );
	register_block_type( UNITONE_BBPRESS_INTEGRATOR_PATH . '/dist/blocks/search' );
	register_block_type( UNITONE_BBPRESS_INTEGRATOR_PATH . '/dist/blocks/stats' );
	register_block_type( UNITONE_BBPRESS_INTEGRATOR_PATH . '/dist/blocks/topics' );
}
add_action( 'init', 'unitone_bbpress_integrator_register_blocks' );
