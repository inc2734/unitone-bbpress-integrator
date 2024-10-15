<?php
/**
 * @package unitone-bbpress-integrator
 * @author inc2734
 * @license GPL-2.0+
 */

/**
 * Register meta data.
 */
add_action(
	'init',
	function () {
		register_post_meta(
			'reply',
			'unitone-bbpress-integrator-reply-likes',
			array(
				'type'         => 'integer',
				'single'       => true,
				'default'      => 0,
				'show_in_rest' => true,
			)
		);

		register_post_meta(
			'reply',
			'unitone-bbpress-integrator-reply-likes-users',
			array(
				'type'         => 'array',
				'single'       => true,
				'default'      => array(),
				'show_in_rest' => array(
					'schema' => array(
						'type'  => 'array',
						'items' => array(
							'type' => 'integer',
						),
					),
				),
			)
		);

		register_meta(
			'user',
			'unitone-bbpress-integrator-reply-likes',
			array(
				'type'         => 'integer',
				'single'       => true,
				'default'      => 0,
				'show_in_rest' => true,
			)
		);
	}
);

/**
 * Register rest routes.
 */
add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'unitone-bbpress-integrator/v1',
			'/like',
			array(
				'methods'             => 'POST',
				'callback'            => function ( $request ) {
					$params = json_decode( $request->get_body(), true );

					$reply_id  = $params['replyId'] ?? false;

					if ( $reply_id ) {
						$author_id = (int) bbp_get_reply_author_id( $reply_id );
						$user_id   = (int) wp_get_current_user()->ID;

						unitone_bbpress_integrator_countup_reply_likes( $reply_id );
						unitone_bbpress_integrator_countup_reply_likes_users( $reply_id, $user_id );
						unitone_bbpress_integrator_countup_user_likes( $author_id );
					}

					return array(
						'likes' => unitone_bbpress_integrator_get_reply_likes( $reply_id ),
						'users' => unitone_bbpress_integrator_get_reply_likes_users( $reply_id ),
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'publish_replies' );
				},
			)
		);
	}
);

/**
 * Enqueue assets.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style(
			'unitone-bbpress-integrator-like',
			UNITONE_BBPRESS_INTEGRATOR_URL . '/dist/css/like.css',
			array(),
			filemtime( UNITONE_BBPRESS_INTEGRATOR_PATH . '/dist/css/like.css' )
		);

		$asset = include UNITONE_BBPRESS_INTEGRATOR_PATH . '/dist/js/like.asset.php';
		wp_enqueue_script(
			'unitone-bbpress-integrator-like',
			UNITONE_BBPRESS_INTEGRATOR_URL . '/dist/js/like.js',
			$asset['dependencies'],
			filemtime( UNITONE_BBPRESS_INTEGRATOR_PATH . '/dist/js/like.js' ),
			array(
				'strategy' => 'defer',
			)
		);

		$current_user = wp_get_current_user();

		wp_add_inline_script(
			'unitone-bbpress-integrator-like',
			'var unitone_bbpress_integrator = ' . wp_json_encode(
				array(
					'currentUser' => array(
						'id'     => $current_user->ID,
						'avatar' => '<a href="' . esc_url( bbp_get_user_profile_url( $current_user->ID ) ) . '" title="' . esc_attr( $current_user->display_name ) . '">' . get_avatar( $current_user->ID, 96, '', $current_user->display_name ) . '</a>',
					),
					'endpoint'    => array(
						'like' => array(
							'update' => rest_url( '/unitone-bbpress-integrator/v1/like' ),
						),
					),
					'nonce'       => wp_create_nonce( 'wp_rest' ),
				)
			),
			'before'
		);
	}
);

/**
 * Display like button to after reply content.
 */
add_action(
	'bbp_theme_after_reply_content',
	function () {
		$current_user = wp_get_current_user();
		$reply_id     = bbp_get_reply_id();
		$author_id    = bbp_get_reply_author_id();
		$button_tag   = 0 < $author_id && 0 < $current_user->ID && (int) $current_user->ID !== (int) $author_id ? 'button' : 'span';
		$likes        = unitone_bbpress_integrator_get_reply_likes( $reply_id );
		$likes_users  = unitone_bbpress_integrator_get_reply_likes_users( $reply_id );
		$likes_users  = unitone_bbpress_integrator_user_ids_to_names( $likes_users );
		$icon         = unitone_bbpress_integrator_get_like_icon();
		?>
		<div class="unitone-bbpress-integrator-likes-wrapper">
			<div class="unitone-bbpress-integrator-likes-wrapper__button">
				<<?php echo esc_html( $button_tag ); ?> class="unitone-bbpress-integrator-likes unitone-bbpress-integrator-reply-likes" data-reply-id="<?php echo esc_attr( $reply_id ); ?>"  title="<?php echo esc_attr_e( 'Like this reply', 'unitone-bbpress-integrator' ); ?>">
					<span class="unitone-bbpress-integrator-likes__icon">
						<?php echo wp_kses_post( $icon ); ?>
					</span>
					<span class="unitone-bbpress-integrator-likes__count">
						<?php echo esc_html( $likes ); ?>
					</span>
				</<?php echo esc_html( $button_tag ); ?>>
			</div>
			<div class="unitone-bbpress-integrator-likes-wrapper__users">
				<div class="unitone-bbpress-integrator-likes-users">
					<span class="unitone-bbpress-integrator-likes-users__label"><?php esc_html_e( 'Who liked:', 'unitone-bbpress-integrator' ); ?></span>
					<span class="unitone-bbpress-integrator-likes-users__users">
						<?php if ( 0 === $likes || empty( $likes_users ) ) : ?>
							<span class="unitone-bbpress-integrator-likes-users__no-user-label"><?php esc_html_e( 'No user', 'unitone-bbpress-integrator' ); ?></span>
						<?php else : ?>
							<?php foreach ( $likes_users as $user_id => $name ) : ?>
								<div class="unitone-bbpress-integrator-likes-users__user" data-user-id="<?php echo esc_attr( $user_id ); ?>">
									<a href="<?php echo esc_url( bbp_get_user_profile_url( $user_id ) ); ?>" title="<?php echo esc_attr( $name ); ?>">
										<?php echo get_avatar( $user_id, 96, '', $name ); ?>
									</a>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</span>
				</div>
			</div>
		</div>
		<?php
	}
);

/**
 * Add bbp_get_reply_author_link callback.
 */
add_action(
	'bbp_theme_before_reply_author_details',
	function() {
		add_filter(
			'bbp_get_reply_author_link',
			'unitone_bbpress_integrator_add_user_likes_for_replies_user',
			10,
			2
		);
	}
);

/**
 * Remove bbp_get_reply_author_link callback.
 */
add_action(
	'bbp_theme_after_reply_author_details',
	function() {
		remove_filter(
			'bbp_get_reply_author_link',
			'unitone_bbpress_integrator_add_user_likes_for_replies_user',
			10,
			2
		);
	}
);

/**
 * Display stars for user.
 *
 * @param string $author_link The author link.
 * @param array  $r           Arra of default value.
 * @return string
 */
function unitone_bbpress_integrator_add_user_likes_for_replies_user( $author_link, $r ) {
	$reply_id  = bbp_get_reply_id( $r['post_id'] );
	$author_id = bbp_get_reply_author_id( $reply_id );

	if ( bbp_is_reply_anonymous( $reply_id ) ) {
		return $author_link;
	}

	$icon       = unitone_bbpress_integrator_get_like_icon();
	$user_likes = unitone_bbpress_integrator_get_user_likes( $author_id );

	ob_start();
	?>
	<div class="unitone-bbpress-integrator-likes-wrapper">
		<div class="unitone-bbpress-integrator-likes-wrapper__button">
			<div class="unitone-bbpress-integrator-likes">
				<span class="unitone-bbpress-integrator-likes__icon"><?php echo wp_kses_post( $icon ); ?></span>
				<span class="unitone-bbpress-integrator-likes__count"><?php echo wp_kses_post( $user_likes ); ?></span>
			</div>
		</div>
	</div>
	<?php
	$user_stars = ob_get_clean();

	return $author_link . $user_stars;
}

/**
 * Display likes to profile page.
 */
add_action(
	'bbp_template_after_user_profile',
	function() {
		?>
		<p>
			<?php
			$user  = bbpress()->displayed_user;
			$likes = unitone_bbpress_integrator_get_user_likes( $user->ID );
			?>
			<?php esc_html_e( 'Total likes (Replies)', 'unitone-bbpress-integrator' ); ?>: <?php echo esc_html( $likes ); ?>
		</p>
		<?php
	}
);

/**
 * Return likes count of the reply.
 *
 * @param int $reply_id The reply ID.
 * @return int
 */
function unitone_bbpress_integrator_get_reply_likes( $reply_id ) {
	$likes = get_post_meta( $reply_id, 'unitone-bbpress-integrator-reply-likes', true );
	return preg_match( '|^\d+$|', $likes ) ? (int) $likes : 0;
}

/**
 * Count up reply likes.
 *
 * @param int $reply_id The reply ID.
 * @return boolean
 */
function unitone_bbpress_integrator_countup_reply_likes( $reply_id ) {
	$reply_likes = unitone_bbpress_integrator_get_reply_likes( $reply_id );
	++$reply_likes;
	return update_post_meta( $reply_id, 'unitone-bbpress-integrator-reply-likes', $reply_likes );
}

/**
 * Return likes users of the reply.
 *
 * @param int $reply_id The reply ID.
 * @return array
 */
function unitone_bbpress_integrator_get_reply_likes_users( $reply_id ) {
	$users = get_post_meta( $reply_id, 'unitone-bbpress-integrator-reply-likes-users', true );
	return $users ? $users : array();
}

/**
 * Count up reply likes users.
 *
 * @param int $reply_id The reply ID.
 * @param int $user_id The user ID.
 * @return array
 */
function unitone_bbpress_integrator_countup_reply_likes_users( $reply_id, $user_id ) {
	$reply_likes_users   = unitone_bbpress_integrator_get_reply_likes_users( $reply_id );
	$reply_likes_users[] = $user_id;
	$reply_likes_users   = array_unique( $reply_likes_users );
	return update_post_meta( $reply_id, 'unitone-bbpress-integrator-reply-likes-users', $reply_likes_users );
}

/**
 * Return likes count of the user.
 *
 * @param int $author_id The reply author ID.
 * @return int
 */
function unitone_bbpress_integrator_get_user_likes( $author_id ) {
	$likes = get_user_meta( $author_id, 'unitone-bbpress-integrator-reply-likes', true );
	return preg_match( '|^\d+$|', $likes ) ? $likes : 0;
}

/**
 * Count up user likes.
 *
 * @param int $author_id The reply author ID.
 * @return int
 */
function unitone_bbpress_integrator_countup_user_likes( $author_id ) {
	$user_likes = unitone_bbpress_integrator_get_user_likes( $author_id );
	++$user_likes;
	return update_user_meta( $author_id, 'unitone-bbpress-integrator-reply-likes', $user_likes );
}

/**
 * Return user display names.
 *
 * @param array $user_ids Array of user ID.
 * @return array $names
 */
function unitone_bbpress_integrator_user_ids_to_names( $user_ids ) {
	$names = array();
	foreach ( $user_ids as $user_id ) {
		$userdata          = get_userdata( $user_id );
		$names[ $user_id ] = $userdata->display_name;
	}
	return $names;
}

/**
 * Return icon.
 *
 * @return string
 */
function unitone_bbpress_integrator_get_like_icon() {
	$icon = '&hearts;';
	return $icon;
}
