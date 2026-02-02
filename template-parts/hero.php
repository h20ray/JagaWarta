<?php
/**
 * Home hero — single featured post. LCP-friendly, no slider.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$featured_id = isset( $args['featured_id'] ) ? (int) $args['featured_id'] : 0;

if ( ! $featured_id ) {
	$q = new WP_Query( array(
		'post_type'      => 'post',
		'posts_per_page' => 1,
		'no_found_rows'  => true,
	) );
	$featured_id = $q->have_posts() ? (int) $q->posts[0]->ID : 0;
	wp_reset_postdata();
}

if ( ! $featured_id ) {
	return;
}

$permalink  = get_permalink( $featured_id );
$title      = get_the_title( $featured_id );
$date_iso   = get_the_date( DATE_W3C, $featured_id );
$date_hr    = get_the_date( '', $featured_id );
$category   = get_the_category( $featured_id );
$cat        = $category ? $category[0] : null;
?>
<section class="bg-surface" aria-label="<?php esc_attr_e( 'Featured', 'jagawarta' ); ?>">
	<div class="mx-auto max-w-screen-lg px-4 py-6">
		<article class="rounded-md bg-surface-high ring-1 ring-outline-variant">
			<a href="<?php echo esc_url( $permalink ); ?>" class="block focus:outline-none">
				<?php
				$hero_img = jagawarta_get_post_display_image( $featured_id );
				if ( ! empty( $hero_img['url'] ) ) :
					?>
					<div class="overflow-hidden rounded-t-md">
						<?php jagawarta_the_post_display_image( $featured_id, array( 'lcp' => true, 'class' => 'object-cover' ) ); ?>
					</div>
				<?php endif; ?>

				<div class="p-5">
					<div class="flex items-center gap-2">
						<?php if ( $cat ) : ?>
							<span class="inline-flex items-center rounded-sm bg-secondary-container px-2 py-1 text-[0.75rem] leading-5 text-on-secondary-container">
								<?php echo esc_html( $cat->name ); ?>
							</span>
						<?php endif; ?>
						<time datetime="<?php echo esc_attr( $date_iso ); ?>" class="text-[0.75rem] leading-5 text-on-surface-variant">
							<?php echo esc_html( $date_hr ); ?>
						</time>
					</div>

					<h2 class="mt-3 text-[1.75rem] leading-tight text-on-surface">
						<?php echo esc_html( $title ); ?>
					</h2>

					<p class="mt-3 max-w-prose text-[1rem] leading-7 text-on-surface-variant">
						<?php echo esc_html( wp_strip_all_tags( get_the_excerpt( $featured_id ) ) ); ?>
					</p>
				</div>
			</a>
		</article>
	</div>
</section>
