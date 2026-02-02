<?php
/**
 * Post Card Compact (mobile list)
 * Expects: $args['post_id'] (int)
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
	<a href="<?php echo esc_url( $permalink ); ?>" class="flex gap-3 p-3 focus:outline-none">
		<div class="shrink-0 overflow-hidden rounded-md bg-surface-mid ring-1 ring-outline-variant">
			<?php
			$display = function_exists( 'jagawarta_get_post_display_image' ) ? jagawarta_get_post_display_image( $post_id ) : array( 'attachment_id' => 0, 'url' => '' );
			if ( ! empty( $display['url'] ) ) :
				if ( ! empty( $display['attachment_id'] ) ) :
					echo wp_get_attachment_image(
						$display['attachment_id'],
						'thumbnail',
						false,
						array(
							'class'   => 'h-20 w-28 object-cover',
							'loading' => 'lazy',
							'decoding' => 'async',
						)
					);
				else :
					?>
					<img src="<?php echo esc_url( $display['url'] ); ?>" alt="" loading="lazy" decoding="async" class="h-20 w-28 object-cover" />
					<?php
				endif;
			else :
				?>
				<div class="h-20 w-28 bg-surface-high"></div>
			<?php endif; ?>
		</div>

		<div class="min-w-0">
			<div class="flex flex-wrap items-center gap-2">
				<?php if ( $cat ) : ?>
					<span class="inline-flex items-center rounded-sm bg-secondary-container px-2 py-1 text-[0.75rem] leading-5 text-on-secondary-container">
						<?php echo esc_html( $cat->name ); ?>
					</span>
				<?php endif; ?>
			</div>

			<h3 class="mt-1 text-[1rem] leading-6 text-on-surface line-clamp-2 group-hover:underline">
				<?php echo esc_html( $title ); ?>
			</h3>

			<div class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-1 text-[0.75rem] leading-5 text-on-surface-variant">
				<time datetime="<?php echo esc_attr( $date_iso ); ?>"><?php echo esc_html( $date_human ); ?></time>
				<?php if ( $read_time ) : ?>
					<span aria-hidden="true">•</span>
					<span><?php echo esc_html( $read_time ); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</a>
</article>
