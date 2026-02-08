<?php
/**
 * Bento medium card (home top split). Expects $args['post_id'].
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$post_id   = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$permalink = get_permalink( $post_id );
$title     = get_the_title( $post_id );
$excerpt   = wp_strip_all_tags( get_the_excerpt( $post_id ) );
$date_iso  = get_the_date( DATE_W3C, $post_id );
$date_human = function_exists( 'jagawarta_format_date' ) ? jagawarta_format_date( $post_id ) : get_the_date( '', $post_id );

$category = get_the_category( $post_id );
$cat      = $category ? $category[0] : null;

$read_time = function_exists( 'jagawarta_read_time_label' )
	? jagawarta_read_time_label( $post_id )
	: '';
?>
<article class="jw-card jw-card--bento">
	<a href="<?php echo esc_url( $permalink ); ?>" class="flex h-full w-full focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-md">
		<div class="jw-bento-media jw-media-wrap">
			<?php
			jagawarta_the_post_display_image( $post_id, array(
				'lcp'   => false,
				'class' => 'h-full w-full object-cover transition-transform duration-medium ease-emphasized group-hover:scale-105',
			) );
			?>
			<?php if ( $cat ) : ?>
				<div class="jw-chip-overlay">
					<?php jagawarta_the_category_chip( $cat, array( 'size' => 'small', 'show_link' => false ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="jw-bento-content">
			<div class="flex flex-wrap items-center gap-x-spacing-2 gap-y-spacing-1 text-label-small text-on-surface-variant">
				<time datetime="<?php echo esc_attr( $date_iso ); ?>">
					<?php echo esc_html( $date_human ); ?>
				</time>
				<?php if ( $read_time ) : ?>
					<span aria-hidden="true">·</span>
					<span><?php echo esc_html( $read_time ); ?></span>
				<?php endif; ?>
			</div>

			<h3 class="mt-spacing-2 text-title-medium text-on-surface leading-snug line-clamp-3 group-hover:text-primary transition-colors duration-short">
				<?php echo esc_html( $title ); ?>
			</h3>

			<?php if ( $excerpt ) : ?>
				<p class="mt-spacing-2 text-body-small text-on-surface-variant line-clamp-2">
					<?php echo esc_html( $excerpt ); ?>
				</p>
			<?php endif; ?>
		</div>
	</a>
</article>
