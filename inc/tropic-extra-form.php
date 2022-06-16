<?php
/**
 * @package unitone-bbpress-integrator
 * @author inc2734
 * @license GPL-2.0+
 */

/**
 * Add wrapper for form fields in tropic extra form.
 */
add_filter(
	'render_block',
	function( $block_content, $block ) {
		if ( 'core/template-part' === $block['blockName'] && 'contents-bbpress' === $block['attrs']['slug'] ) {
			$block_content = preg_replace(
				'|(<input name="bbp_topic_subscribers".+?</label>)|ms',
				'<p>$1</p>',
				$block_content
			);
			$block_content = preg_replace(
				'|(<input name="bbp_topic_favoriters".+?</label>)|ms',
				'<p>$1</p>',
				$block_content
			);
			$block_content = preg_replace(
				'|(<input name="bbp_topic_tags".+?</label>)|ms',
				'<p>$1</p>',
				$block_content
			);
		}
		return $block_content;
	},
	10,
	2
);
