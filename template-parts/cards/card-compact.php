<?php
/**
 * Compact list card. Expects $args['post_id'] or global $post.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$post_id   = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$permalink = get_permalink( $post_id );
$title     = get_the_title( $post_id );
$date_iso  = get_the_date( DATE_W3C, $post_id );
$date_human = get_the_date( '', $post_id );

$category = get_the_category( $post_id );
$cat      = $category ? $category[0] : null;

$read_time = function_exists( 'jagawarta_read_time_label' )
	? jagawarta_read_time_label( $post_id )
	: '';
?>
<article class="group rounded-md bg-surface-low ring-1 ring-outline-variant transition duration-short ease-standard hover:bg-surface-high focus-within:bg-surface-high">
	<a href="<?php echo esc_url( $permalink ); ?>" class="flex gap-spacing-3 p-spacing-3 focus:outline-none">
		<div class="shrink-0 overflow-hidden rounded-md bg-surface-mid ring-1 ring-outline-variant jw-media-wrap">
			<?php
			$display = function_exists( 'jagawarta_get_post_display_image' ) ? jagawarta_get_post_display_image( $post_id ) : array( 'attachment_id' => 0, 'url' => '' );
			if ( ! empty( $display['url'] ) ) :
				if ( ! empty( $display['attachment_id'] ) ) :
					jagawarta_the_image(
						$display['attachment_id'],
						array(
							'lcp'   => false,
							'size'  => 'thumbnail',
							'sizes' => '112px',
							'class' => 'h-spacing-20 w-spacing-28 object-cover',
						)
					);
				else :
					?>
					<img src="<?php echo esc_url( $display['url'] ); ?>" alt="" loading="lazy" decoding="async" class="h-spacing-20 w-spacing-28 object-cover" />
					<?php
				endif;
			else :
				?>
				<div class="h-spacing-20 w-spacing-28 bg-surface-high"></div>
			<?php endif; ?>
			<?php if ( $cat ) : ?>
				<div class="jw-chip-overlay">
					<?php jagawarta_the_category_chip( $cat, array( 'size' => 'small', 'show_link' => false ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="min-w-0">
			<h3 class="mt-spacing-1 text-body-large text-on-surface line-clamp-2 group-hover:underline">
				<?php echo esc_html( $title ); ?>
			</h3>

			<div class="mt-spacing-1 flex flex-wrap items-center gap-x-spacing-2 gap-y-spacing-1 text-label-small text-on-surface-variant">
				<time datetime="<?php echo esc_attr( $date_iso ); ?>"><?php echo esc_html( $date_human ); ?></time>
				<?php if ( $read_time ) : ?>
					<span aria-hidden="true">•</span>
					<span><?php echo esc_html( $read_time ); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</a>
</article>
