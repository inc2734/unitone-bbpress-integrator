<?php
/**
 * @see https://github.com/bbpress/bbPress/blob/trunk/src/includes/common/widgets.php
 */
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'unitone-bbpress-integrator-topics' ) ); ?>>
	<?php
	// How do we want to order our results?
	switch ( $attributes['orderBy'] ) {

		// Order by most recent replies
		case 'freshness':
			$topics_query = array(
				// What and how
				'post_type'              => bbp_get_topic_post_type(),
				'post_status'            => bbp_get_public_topic_statuses(),
				'post_parent'            => $attributes['parentForum'],
				'posts_per_page'         => (int) $attributes['maxShown'],
				'meta_query'             => array(
					array(
						'key'  => '_bbp_last_active_time',
						'type' => 'DATETIME',
					),
				),

				// Ordering
				'orderby'                => 'meta_value',
				'order'                  => 'DESC',

				// Performance
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
			);
			break;

		// Order by total number of replies
		case 'popular':
			$topics_query = array(
				// What and how
				'post_type'              => bbp_get_topic_post_type(),
				'post_status'            => bbp_get_public_topic_statuses(),
				'post_parent'            => $attributes['parentForum'],
				'posts_per_page'         => (int) $attributes['maxShown'],
				'meta_query'             => array(
					array(
						'key'  => '_bbp_reply_count',
						'type' => 'NUMERIC',
					),
				),

				// Ordering
				'orderby'                => 'meta_value_num',
				'order'                  => 'DESC',

				// Performance
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
			);
			break;

		// Order by which topic was created most recently
		case 'newness':
		default:
			$topics_query = array(
				// What and how
				'post_type'              => bbp_get_topic_post_type(),
				'post_status'            => bbp_get_public_topic_statuses(),
				'post_parent'            => $attributes['parentForum'],
				'posts_per_page'         => (int) $attributes['maxShown'],

				// Ordering
				'orderby'                => 'date',
				'order'                  => 'DESC',

				// Performance
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
			);
			break;
	}

	// Note: private and hidden forums will be excluded via the
	// bbp_pre_get_posts_normalize_forum_visibility action and function.
	$widget_query = new WP_Query( $topics_query );
	?>

	<div data-unitone-layout="stack -gap:-2">
		<?php if ( ! empty( $attributes['title'] ) ) : ?>
			<h3><?php echo esc_html( $attributes['title'] ); ?></h3>
		<?php endif; ?>

		<?php if ( $widget_query->have_posts() ) : ?>
			<ul class="bbp-topics-widget <?php echo esc_attr( $attributes['orderBy'] ); ?>">

				<?php
				while ( $widget_query->have_posts() ) :
					$widget_query->the_post();
					$topic_id    = bbp_get_topic_id( $widget_query->post->ID );
					$author_link = '';

					// Maybe get the topic author
					if ( ! empty( $attributes['showUser'] ) ) :
						$author_link = bbp_get_topic_author_link(
							array(
								'post_id' => $topic_id,
								'type'    => 'both',
								'size'    => 14,
							)
						);
					endif;
					?>

					<li>
						<a class="bbp-forum-title" href="<?php bbp_topic_permalink( $topic_id ); ?>"><?php bbp_topic_title( $topic_id ); ?></a>

						<?php if ( ! empty( $author_link ) ) : ?>

							<?php
							printf(
								// translators: %1$s: Author name
								esc_html_x( 'by %1$s', 'widgets', 'unitone-bbpress-integrator' ),
								'<span class="topic-author">' . $author_link . '</span>'
							);
							?>

						<?php endif; ?>

						<?php if ( ! empty( $attributes['showDate'] ) ) : ?>

							<div><?php bbp_topic_last_active_time( $topic_id ); ?></div>

						<?php endif; ?>

					</li>

				<?php endwhile; ?>
			</ul>

			<?php
			// Reset the $post global
			wp_reset_postdata();
			?>
		<?php endif; ?>
	</div>
</div>
