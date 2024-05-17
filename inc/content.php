<?php
/**
 * @package unitone-bbpress-integrator
 * @author inc2734
 * @license GPL-2.0+
 */

add_action(
	'after_setup_theme',
	function () {
		remove_filter( 'bbp_get_reply_content', 'bbp_make_clickable', 4 );
		remove_filter( 'bbp_get_topic_content', 'bbp_make_clickable', 4 );
		remove_filter( 'bbp_get_reply_content', 'bbp_make_clickable', 40 ); // v2.6.0〜.
		remove_filter( 'bbp_get_topic_content', 'bbp_make_clickable', 40 ); // v2.6.0〜.

		add_filter( 'bbp_get_reply_content', 'unitone_bbpress_integrator_wp_oembed_blog_card_sanitize', 100 );
		add_filter( 'bbp_get_topic_content', 'unitone_bbpress_integrator_wp_oembed_blog_card_sanitize', 100 );
	}
);

/**
 * Sanitize oembed blog card HTML.
 *
 * @todo
 *
 * @param string $content The content.
 * @return string
 */
function unitone_bbpress_integrator_wp_oembed_blog_card_sanitize( $content ) {
	$content = preg_replace( '|(<div class="wp-oembed-blog-card"[^>]+?><a [^>]+>)</p>|', '$1', $content );
	$content = str_replace( '<p></a></div>', '</a></div>', $content );
	return $content;
}
