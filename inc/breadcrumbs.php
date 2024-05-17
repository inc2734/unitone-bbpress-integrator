<?php
/**
 * @package unitone-bbpress-integrator
 * @author inc2734
 * @license GPL-2.0+
 */

/**
 * Remove default breadcrumbs.
 */
add_filter( 'bbp_get_breadcrumb', '__return_null' );

/**
 * Breadcrumbs
 */
add_filter(
	'unitone_breadcrumbs',
	function ( $breadcrumbs ) {
		if ( ! is_bbpress() ) {
			return $breadcrumbs;
		}

		/**
		 * Update breadcrumbs for single topic.
		 *
		 * @param array $breadcrumbs Array of breadcrumbs item.
		 * @return array
		 */
		function unitone_bbpress_integrator_beradcrumbs_single_topic( $breadcrumbs ) {
			$adding_items = array();

			foreach ( $breadcrumbs as $key => $item ) {
				if ( isset( $item['link'] ) && bbp_get_topics_url() === $item['link'] ) {
					$adding_items[] = array(
						'title' => bbp_get_forum_archive_title(),
						'link'  => bbp_get_forums_url(),
					);

					$ancestors = array_reverse( (array) get_post_ancestors( get_the_ID() ) );
					foreach ( $ancestors as $post_id ) {
						$adding_items[] = array(
							'title' => bbp_get_forum_title( $post_id ),
							'link'  => bbp_get_forum_permalink( $post_id ),
						);
					}

					unset( $breadcrumbs[ $key ] );
					array_splice( $breadcrumbs, $key, 0, $adding_items );
				}
			}

			return $breadcrumbs;
		}

		/**
		 * Update breadcrumbs for topic edit.
		 *
		 * @param array $breadcrumbs Array of breadcrumbs item.
		 * @return array
		 */
		function unitone_bbpress_integrator_breadcrumbs_topic_edit( $breadcrumbs ) {
			$breadcrumbs   = unitone_bbpress_integrator_beradcrumbs_single_topic( $breadcrumbs );
			$breadcrumbs[] = array(
				'title' => __( 'Edit this topic', 'unitone-bbpress-integrator' ),
				'link'  => bbp_get_topic_edit_url( get_the_ID() ),
			);

			return $breadcrumbs;
		}

		/**
		 * Update breadcrumbs for search.
		 *
		 * @param array $breadcrumbs Array of breadcrumbs item.
		 * @return array
		 */
		function unitone_bbpress_integrator_breadcrumbs_search( $breadcrumbs ) {
			$breadcrumbs[] = array(
				'title' => bbp_get_forum_archive_title(),
				'link'  => bbp_get_forums_url(),
			);

			$breadcrumbs[] = array(
				'title' => sprintf(
					/* translators: 1: Search terms */
					__( 'Search results of "%1$s"', 'unitone-bbpress-integrator' ),
					bbp_get_search_terms()
				),
				'link'  => '',
			);

			return $breadcrumbs;
		}

		/**
		 * Update breadcrumbs for single user.
		 *
		 * @param array $breadcrumbs Array of breadcrumbs item.
		 * @return array
		 */
		function unitone_bbpress_integrator_beradcrumbs_single_user( $breadcrumbs ) {
			$breadcrumbs[] = array(
				'title' => bbp_get_forum_archive_title(),
				'link'  => bbp_get_forums_url(),
			);

			$breadcrumbs[] = array(
				'title' => get_userdata( bbp_get_user_id() )->display_name,
				'link'  => '',
			);

			return $breadcrumbs;
		}

		/**
		 * Update breadcrumbs for topic tag.
		 *
		 * @param array $breadcrumbs Array of breadcrumbs item.
		 * @return array
		 */
		function unitone_bbpress_integrator_breadcrumbs_topic_tag( $breadcrumbs ) {
			$adding_items = array(
				array(
					'title' => bbp_get_forum_archive_title(),
					'link'  => bbp_get_forums_url(),
				),
			);

			array_splice( $breadcrumbs, -1, 0, $adding_items );

			return $breadcrumbs;
		}

		/**
		 * Update breadcrumbs for topic archive.
		 *
		 * @param array $breadcrumbs Array of breadcrumbs item.
		 * @return array
		 */
		function unitone_bbpress_integrator_beradcrumbs_topic_archive( $breadcrumbs ) {
			$adding_items = array(
				array(
					'title' => bbp_get_forum_archive_title(),
					'link'  => bbp_get_forums_url(),
				),
			);

			array_splice( $breadcrumbs, -1, 0, $adding_items );

			return $breadcrumbs;
		}

		/**
		 * Update breadcrumbs for single reply.
		 *
		 * @param array $breadcrumbs Array of breadcrumbs item.
		 * @return array
		 */
		function unitone_bbpress_integrator_beradcrumbs_single_reply( $breadcrumbs ) {
			array_splice( $breadcrumbs, -1, 1 );

			$breadcrumbs[] = array(
				'title' => bbp_get_forum_archive_title(),
				'link'  => bbp_get_forums_url(),
			);

			$ancestors = array_reverse( (array) get_post_ancestors( get_the_ID() ) );
			foreach ( $ancestors as $post_id ) {
				$breadcrumbs[] = array(
					'title' => bbp_get_forum_title( $post_id ),
					'link'  => bbp_get_forum_permalink( $post_id ),
				);
				break;
			}

			$breadcrumbs[] = array(
				'title' => bbp_get_topic_title(),
				'link'  => bbp_get_topic_permalink(),
			);

			$breadcrumbs[] = array(
				'title' => bbp_get_reply_title(),
				'link'  => '',
			);

			return $breadcrumbs;
		}

		/**
		 * Update breadcrumbs for reply edit.
		 *
		 * @param array $breadcrumbs Array of breadcrumbs item.
		 * @return array
		 */
		function unitone_bbpress_integrator_beradcrumbs_reply_edit( $breadcrumbs ) {
			$breadcrumbs = unitone_bbpress_integrator_beradcrumbs_single_reply( $breadcrumbs );
			return $breadcrumbs;
		}

		/**
		 * Update breadcrumbs for no-replies.
		 *
		 * @param array $breadcrumbs Array of breadcrumbs item.
		 * @return array
		 */
		function unitone_bbpress_integrator_beradcrumbs_no_replies( $breadcrumbs ) {
			$breadcrumbs[] = array(
				'title' => bbp_get_forum_archive_title(),
				'link'  => bbp_get_forums_url(),
			);

			$breadcrumbs[] = array(
				'title' => __( 'Topics with no replies', 'unitone-bbpress-integrator' ),
				'link'  => bbp_get_view_url( 'no-replies' ),
			);

			return $breadcrumbs;
		}

		/**
		 * Update breadcrumbs for popular.
		 *
		 * @param array $breadcrumbs Array of breadcrumbs item.
		 * @return array
		 */
		function unitone_bbpress_integrator_beradcrumbs_popular( $breadcrumbs ) {
			$breadcrumbs[] = array(
				'title' => bbp_get_forum_archive_title(),
				'link'  => bbp_get_forums_url(),
			);

			$breadcrumbs[] = array(
				'title' => __( 'Popular Topics', 'unitone-bbpress-integrator' ),
				'link'  => bbp_get_view_url( 'popular' ),
			);

			return $breadcrumbs;
		}

		if ( bbp_is_single_topic() ) {
			$breadcrumbs = unitone_bbpress_integrator_beradcrumbs_single_topic( $breadcrumbs );
		} elseif ( bbp_is_topic_edit() ) {
			$breadcrumbs = unitone_bbpress_integrator_breadcrumbs_topic_edit( $breadcrumbs );
		} elseif ( bbp_is_search() ) {
			$breadcrumbs = unitone_bbpress_integrator_breadcrumbs_search( $breadcrumbs );
		} elseif ( bbp_is_single_user() ) {
			$breadcrumbs = unitone_bbpress_integrator_beradcrumbs_single_user( $breadcrumbs );
		} elseif ( bbp_is_topic_tag() || ( get_query_var( 'bbp_topic_tag' ) && ! bbp_is_topic_tag_edit() ) ) {
			$breadcrumbs = unitone_bbpress_integrator_breadcrumbs_topic_tag( $breadcrumbs );
		} elseif ( bbp_is_topic_tag_edit() ) {
			$breadcrumbs = unitone_bbpress_integrator_breadcrumbs_topic_tag( $breadcrumbs );
		} elseif ( bbp_is_topic_archive() ) {
			$breadcrumbs = unitone_bbpress_integrator_beradcrumbs_topic_archive( $breadcrumbs );
		} elseif ( bbp_is_reply() ) {
			$breadcrumbs = unitone_bbpress_integrator_beradcrumbs_single_reply( $breadcrumbs );
		} elseif ( bbp_is_reply_edit() ) {
			$breadcrumbs = unitone_bbpress_integrator_beradcrumbs_reply_edit( $breadcrumbs );
		} elseif ( 'no-replies' === bbp_get_view_id() ) {
			$breadcrumbs = unitone_bbpress_integrator_beradcrumbs_no_replies( $breadcrumbs );
		} elseif ( 'popular' === bbp_get_view_id() ) {
			$breadcrumbs = unitone_bbpress_integrator_beradcrumbs_popular( $breadcrumbs );
		}

		return $breadcrumbs;
	}
);
