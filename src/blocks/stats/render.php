<?php
/**
 * @see https://github.com/bbpress/bbPress/blob/trunk/src/includes/common/widgets.php
 */
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'unitone-bbpress-integrator-stats' ) ); ?>>
	<div data-unitone-layout="stack -gap:-2">
		<?php if ( ! empty( $attributes['title'] ) ) : ?>
			<h3><?php echo esc_html( $attributes['title'] ); ?></h3>
		<?php endif; ?>

		<?php bbp_get_template_part( 'content', 'statistics' ); ?>
	</div>
</div>
