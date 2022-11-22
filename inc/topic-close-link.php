<?php
/**
 * @package unitone-bbpress-integrator
 * @author inc2734
 * @license GPL-2.0+
 */

/**
 * Add closed icon to topic title.
 */
add_filter(
	'bbp_get_topic_title',
	function( $title, $topic_id ) {
		if ( 'closed' === get_post_status( $topic_id ) ) {
			return '<span class="unitone-bbpress-integrator-ic-check"></span>' . $title;
		}
		return $title;
	},
	10,
	2
);

/**
 * Add topic close link to before_replies_loop.
 */
add_action(
	'bbp_template_before_single_topic',
	function() {
		$close_link = unitone_bbpress_integrator_close_link();
		if ( ! $close_link ) {
			return;
		}
		?>
		<span class="unitone-bbpress-integrator-my-topic-close-link">
			<?php echo wp_kses_post( $close_link ); ?>
		</span>
		<?php
	}
);

/**
 * Add topic close link to reply_form_submit_button.
 */
add_action(
	'bbp_theme_after_reply_form_submit_button',
	function() {
		$close_link = unitone_bbpress_integrator_close_link();
		if ( ! $close_link ) {
			return;
		}
		echo wp_kses_post( $close_link );
	}
);

/**
 * Return close link html.
 *
 * @return string
 */
function unitone_bbpress_integrator_close_link() {
		$topic_id     = bbp_get_topic_id();
		$topic        = bbp_get_topic( $topic_id );
		$current_user = wp_get_current_user();

	if (
			empty( $topic->ID )
			|| ! current_user_can( 'participate', $topic->ID )
			|| (int) $current_user->ID !== (int) $topic->post_author
		) {
		return;
	}

		$args = bbp_parse_args(
			array(),
			array(
				'close_text' => __( 'Close this topic', 'unitone-bbpress-integrator' ),
				'open_text'  => __( 'Open this topic', 'unitone-bbpress-integrator' ),
			),
			'get_topic_close_link'
		);

		$display = bbp_is_topic_open( $topic->ID ) ? $args['close_text'] : $args['open_text'];
		$uri     = add_query_arg(
			array(
				'action'   => 'bbp_toggle_topic_close',
				'topic_id' => $topic->ID,
			)
		);
		$uri     = wp_nonce_url( $uri, 'close-topic_' . $topic->ID );

		return sprintf(
			'<a href="%1$s" class="button">%2$s</a>',
			esc_url( $uri ),
			esc_html( $display )
		);
}
