<?php
/**
 * @package unitone-bbpress-integrator
 * @author inc2734
 * @license GPL-2.0+
 */

/**
 * Using block templates.
 */
add_filter(
	'bbp_template_include',
	function () {
		if (
			bbp_is_forum_archive() ||
			bbp_is_topic_archive() ||
			bbp_is_search() ||
			bbp_is_single_user_edit() ||
			bbp_is_single_user()
		) {
			add_filter(
				'the_content',
				function () {
					global $post;

					return $post->post_content;
				}
			);
		}

		return ABSPATH . WPINC . '/template-canvas.php';
	}
);

/**
 * Add patterns.
 *
 * The slug should be `unitone-bbpress-integrator/*` though,
 * However, due to historical reasons, it should be `unitone/*`.
 */
add_action(
	'init',
	function () {
		$patterns = array(
			array(
				'title'    => __( 'Main Area (One Column) for bbPress', 'unitone-bbpress-integrator' ),
				'slug'     => 'unitone/template/bbpress/main/one-column',
				'inserter' => false,
				'path'     => UNITONE_BBPRESS_INTEGRATOR_PATH . '/patterns/template/main/one-column.php',
			),
			array(
				'title'    => __( 'Main Area (One Column / Page Header (Image)) for bbPress', 'unitone-bbpress-integrator' ),
				'slug'     => 'unitone/template/bbpress/main/one-column-page-header-image',
				'inserter' => false,
				'path'     => UNITONE_BBPRESS_INTEGRATOR_PATH . '/patterns/template/main/one-column-page-header-image.php',
			),
			array(
				'title'    => __( 'Main Area (Right Sidebar / Page Header (Image)) for bbPress', 'unitone-bbpress-integrator' ),
				'slug'     => 'unitone/template/bbpress/main/right-sidebar-page-header-image',
				'inserter' => false,
				'path'     => UNITONE_BBPRESS_INTEGRATOR_PATH . '/patterns/template/main/right-sidebar-page-header-image.php',
			),
			array(
				'title'    => __( 'Main Area (Right Sidebar) for bbPress', 'unitone-bbpress-integrator' ),
				'slug'     => 'unitone/template/bbpress/main/right-sidebar',
				'inserter' => false,
				'path'     => UNITONE_BBPRESS_INTEGRATOR_PATH . '/patterns/template/main/right-sidebar.php',
			),
			array(
				'title'    => __( 'Page Header (Image) for bbPress', 'unitone-bbpress-integrator' ),
				'slug'     => 'unitone/template/bbpress/page-header/image',
				'inserter' => false,
				'path'     => UNITONE_BBPRESS_INTEGRATOR_PATH . '/patterns/template/page-header/image.php',
			),
			array(
				'title'    => __( 'Page Header for bbPress', 'unitone-bbpress-integrator' ),
				'slug'     => 'unitone/template/bbpress/page-header/default',
				'inserter' => false,
				'path'     => UNITONE_BBPRESS_INTEGRATOR_PATH . '/patterns/template/page-header/default.php',
			),

			array(
				'title'         => __( 'bbPress: Left Header / Page Header (Image)', 'unitone-bbpress-integrator' ),
				'slug'          => 'unitone/template/bbpress/left-header-page-header-image',
				'categories'    => array( 'unitone-templates' ),
				'templateTypes' => array( 'bbpress' ),
				'inserter'      => false,
				'path'          => UNITONE_BBPRESS_INTEGRATOR_PATH . '/patterns/template/left-header-page-header-image.php',
			),
			array(
				'title'         => __( 'bbPress: Left Header', 'unitone-bbpress-integrator' ),
				'slug'          => 'unitone/template/bbpress/left-header',
				'categories'    => array( 'unitone-templates' ),
				'templateTypes' => array( 'bbpress' ),
				'inserter'      => false,
				'path'          => UNITONE_BBPRESS_INTEGRATOR_PATH . '/patterns/template/left-header.php',
			),
			array(
				'title'         => __( 'bbPress: Left Header (Thin) / Page Header (Image)', 'unitone-bbpress-integrator' ),
				'slug'          => 'unitone/template/bbpress/left-header-thin-page-header-image',
				'categories'    => array( 'unitone-templates' ),
				'templateTypes' => array( 'bbpress' ),
				'inserter'      => false,
				'path'          => UNITONE_BBPRESS_INTEGRATOR_PATH . '/patterns/template/left-header-thin-page-header-image.php',
			),
			array(
				'title'         => __( 'bbPress: Left Header (Thin)', 'unitone-bbpress-integrator' ),
				'slug'          => 'unitone/template/bbpress/left-header-thin',
				'categories'    => array( 'unitone-templates' ),
				'templateTypes' => array( 'bbpress' ),
				'inserter'      => false,
				'path'          => UNITONE_BBPRESS_INTEGRATOR_PATH . '/patterns/template/left-header-thin.php',
			),
			array(
				'title'         => __( 'bbPress: One Column / Page Header (Image)', 'unitone-bbpress-integrator' ),
				'slug'          => 'unitone/template/bbpress/one-column-page-header-image',
				'categories'    => array( 'unitone-templates' ),
				'templateTypes' => array( 'bbpress' ),
				'inserter'      => false,
				'path'          => UNITONE_BBPRESS_INTEGRATOR_PATH . '/patterns/template/one-column-page-header-image.php',
			),
			array(
				'title'         => __( 'bbPress: One Column', 'unitone-bbpress-integrator' ),
				'slug'          => 'unitone/template/bbpress/one-column',
				'categories'    => array( 'unitone-templates' ),
				'templateTypes' => array( 'bbpress' ),
				'inserter'      => false,
				'path'          => UNITONE_BBPRESS_INTEGRATOR_PATH . '/patterns/template/one-column.php',
			),
			array(
				'title'         => __( 'bbPress: Right Sidebar / Page Header (Image)', 'unitone-bbpress-integrator' ),
				'slug'          => 'unitone/template/bbpress/right-sidebar-page-header-image',
				'categories'    => array( 'unitone-templates' ),
				'templateTypes' => array( 'bbpress' ),
				'inserter'      => false,
				'path'          => UNITONE_BBPRESS_INTEGRATOR_PATH . '/patterns/template/right-sidebar-page-header-image.php',
			),
			array(
				'title'         => __( 'bbPress: Right Sidebar', 'unitone-bbpress-integrator' ),
				'slug'          => 'unitone/template/bbpress/right-sidebar',
				'categories'    => array( 'unitone-templates' ),
				'templateTypes' => array( 'bbpress' ),
				'inserter'      => false,
				'path'          => UNITONE_BBPRESS_INTEGRATOR_PATH . '/patterns/template/right-sidebar.php',
			),
		);

		foreach ( $patterns as $pattern ) {
			ob_start();
			include $pattern['path'];
			$pattern['content'] = ob_get_clean();
			unset( $pattern['path'] );
			register_block_pattern( $pattern['slug'], $pattern );
		}
	},
	9
);

/**
 * Add templates.
 *
 * The slug should be `unitone-bbpress-integrator//*` though,
 * However, due to historical reasons, it should be `unitone//*`.
 */
add_action(
	'init',
	function () {
		$block_templates = array(
			array(
				'slug'    => 'unitone//bbpress',
				'title'   => __( 'for bbPress', 'unitone-bbpress-integrator' ),
				'content' => file_get_contents( UNITONE_BBPRESS_INTEGRATOR_PATH . '/templates/bbpress.html' ),
			),
		);

		foreach ( $block_templates as $block_template ) {
			$slug = $block_template['slug'];
			unset( $block_template['slug'] );

			register_block_template(
				$slug,
				$block_template
			);
		}
	}
);

/**
 * Using templates/page-bbpress.html.
 *
 * When this callback is disabled,
 * - example.com/forums/users ... Used templates/index.html
 * - example.com/forums/            ... Used templates/archive.html
 * - example.com/topics/            ... Used templates/archive.html
 */
add_filter(
	'pre_get_block_templates',
	function ( $query_result ) {
		if ( is_bbpress() ) {
			return array(
				get_block_template( 'unitone//bbpress' ),
			);
		}
		return $query_result;
	}
);
