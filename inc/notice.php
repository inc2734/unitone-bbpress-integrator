<?php
/**
 * @package unitone-bbpress-integrator
 * @author inc2734
 * @license GPL-2.0+
 */

add_action( 'wp_insert_post', 'unitone_bbpress_integrator_send_new_topic', 10, 3 );
add_action( 'wpg_connect', 'unitone_bbpress_integrator_send_new_user', 10, 3 );

/**
 * Send mail when adding new topic.
 *
 * @param  int     $post_id The post ID.
 * @param  WP_Post $post    The post object.
 * @param  boolean $update  Updated or not.
 * @return int
 */
function unitone_bbpress_integrator_send_new_topic( $post_id, $post, $update ) {
	if ( $update ) {
		return;
	}

	if ( 'topic' !== get_post_type( $post_id ) ) {
		return;
	}

	$post = get_post( $post_id );
	if ( empty( $post->ID ) ) {
		return;
	}

	$body = sprintf(
		/* translators: 1: Topic title */
		__( 'Title: %1$s', 'unitone-bbpress-integrator' ),
		get_the_title( $post_id )
	);
	$body .= "\n";
	$body .= sprintf(
		/* translators: 1: User name */
		__( 'Owner: %1$s', 'unitone-bbpress-integrator' ),
		get_the_author_meta( 'display_name', $post->post_author )
	);
	$body .= "\n\n";
	$body .= wp_strip_all_tags( $post->post_content );
	$body .= "\n\n";
	$body .= get_permalink( $post_id );

	$subject = sprintf(
		/* translators: 1: Site name, 2: Topic title */
		__( '【%1$s】Added new topic - %2$s', 'unitone-bbpress-integrator' ),
		get_bloginfo( 'name' ),
		get_the_title( $post_id )
	);

	wp_mail(
		get_bloginfo( 'admin_email' ),
		$subject,
		$body
	);
}

/**
 * Send mail when adding new user.
 *
 * @param int    $user_id      The user ID.
 * @param mixed  $data         Data.
 * @param string $service_name Service name.
 */
function unitone_bbpress_integrator_send_new_user( $user_id, $data, $service_name ) {
	$first_name   = get_the_author_meta( 'first_name', $user_id );
	$last_name    = get_the_author_meta( 'last_name', $user_id );
	$nickname     = get_the_author_meta( 'nickname', $user_id );
	$display_name = get_the_author_meta( 'display_name', $user_id );

	$name = $first_name . ' ' . $last_name;
	if ( ! $name ) {
		$name = $nickname;
	}
	if ( ! $name ) {
		$name = $display_name;
	}

	$body = sprintf(
		/* translators: 1: User name */
		__( 'Name: %1$s', 'unitone-bbpress-integrator' ),
		$name
	);
	$body .= "\n";
	$body .= sprintf(
		/* translators: 1: User email */
		__( 'E-mail: %1$s', 'unitone-bbpress-integrator' ),
		get_the_author_meta( 'user_email', $user_id )
	);
	$body .= "\n\n";
	$body .= admin_url( 'users.php' );

	$subject = sprintf(
		/* translators: 1: Site name, 2: oAuth service name */
		__( '【%1$s】Added new user using %2$s.', 'unitone-bbpress-integrator' ),
		get_bloginfo( 'name' ),
		$service_name
	);

	wp_mail(
		get_bloginfo( 'admin_email' ),
		$subject,
		$body
	);
}
