<?php
/**
 * @package unitone-bbpress-integrator
 * @author inc2734
 * @license GPL-2.0+
 */

/**
 * Remove | from the subscribe link.
 *
 * @param string $html HTML.
 * @return string
 */
function unitone_bbpress_integrator_remove_subscribe_link_border( $html ) {
	return str_replace( '&nbsp;|&nbsp;', '', $html );
}
add_filter( 'bbp_get_topic_subscribe_link', 'unitone_bbpress_integrator_remove_subscribe_link_border' );
add_filter( 'bbp_get_user_subscribe_link', 'unitone_bbpress_integrator_remove_subscribe_link_border' );
