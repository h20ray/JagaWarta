<?php
/**
 * Post Card Standard (grid)
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
$excerpt   = wp_strip_all_tags( get_the_excerpt( $post_id ) );
$date_iso  = get_the_date( DATE_W3C, $post_id );
$date_human = get_the_date( '', $post_id );

$category = get_the_category( $post_id );
$cat      = $category ? $category[0] : null;

$read_time = function_exists( 'jagawarta_read_time_label' )
	? jagawarta_read_time_label( $post_id )
	: '';
?>
<article class="group overflow-hidden rounded-md bg-surface-low ring-1 ring-outline-variant transition duration-short ease-standard hover:bg-surface-high focus-within:bg-surface-high">
	<a href="<?php echo esc_url( $permalink ); ?>" class="block h-full focus:outline-none">
		<?php
		$display = function_exists( 'jagawarta_get_post_display_image' ) ? jagawarta_get_post_display_image( $post_id ) : array( 'attachment_id' => 0, 'url' => '' );
		if ( ! empty( $display['url'] ) ) : ?>
			<div class="overflow-hidden">
				<?php
				if ( ! empty( $display['attachment_id'] ) ) :
					jagawarta_the_image(
						$display['attachment_id'],
						array(
							'lcp'   => false,
							'size'  => 'medium_large',
							'sizes' => '(max-width: 1024px) 100vw, 320px',
							'class' => 'w-full object-cover aspect-[16/9]',
						)
					);
				else :
					?>
					<img src="<?php echo esc_url( $display['url'] ); ?>" alt="" loading="lazy" decoding="async" class="w-full object-cover aspect-[16/9]" />
					<?php
				endif;
				?>
			</div>
		<?php else : ?>
			<div class="aspect-[16/9] w-full bg-surface-high"></div>
		<?php endif; ?>

		<div class="p-4">
			<div class="flex flex-wrap items-center gap-2">
				<?php if ( $cat ) : ?>
					<span class="inline-flex items-center rounded-sm bg-secondary-container px-2 py-1 text-[0.75rem] leading-5 text-on-secondary-container">
						<?php echo esc_html( $cat->name ); ?>
					</span>
				<?php endif; ?>

				<time datetime="<?php echo esc_attr( $date_iso ); ?>" class="text-[0.75rem] leading-5 text-on-surface-variant">
					<?php echo esc_html( $date_human ); ?>
				</time>

				<?php if ( $read_time ) : ?>
					<span aria-hidden="true" class="text-on-surface-variant">•</span>
					<span class="text-[0.75rem] leading-5 text-on-surface-variant">
						<?php echo esc_html( $read_time ); ?>
					</span>
				<?php endif; ?>
			</div>

			<h3 class="mt-2 text-[1.125rem] leading-6 text-on-surface line-clamp-3 group-hover:underline">
				<?php echo esc_html( $title ); ?>
			</h3>

			<?php if ( $excerpt ) : ?>
				<p class="mt-2 text-[0.875rem] leading-6 text-on-surface-variant line-clamp-2">
					<?php echo esc_html( $excerpt ); ?>
				</p>
			<?php endif; ?>
		</div>
	</a>
</article>
