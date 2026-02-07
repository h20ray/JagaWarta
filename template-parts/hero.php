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
$excerpt    = wp_strip_all_tags( get_the_excerpt( $featured_id ) );
?>
<section class="bg-surface-high" aria-label="<?php esc_attr_e( 'Featured', 'jagawarta' ); ?>">
	<div class="w-full overflow-hidden">
		<?php
		$hero_img = jagawarta_get_post_display_image( $featured_id );
		if ( ! empty( $hero_img['url'] ) ) :
			?>
			<div class="aspect-[16/9] min-h-hero-min max-h-hero-max w-full bg-surface-mid">
				<?php jagawarta_the_post_display_image( $featured_id, array( 'lcp' => true, 'class' => 'h-full w-full object-cover' ) ); ?>
			</div>
		<?php endif; ?>
		<div class="layout-content py-spacing-8 sm:py-spacing-10">
			<div class="max-w-3xl">
				<div class="flex flex-wrap items-center gap-spacing-3 mb-spacing-3">
					<?php if ( $cat ) : ?>
						<?php jagawarta_the_category_chip( $cat, array( 'size' => 'small', 'show_link' => false ) ); ?>
					<?php endif; ?>
					<time datetime="<?php echo esc_attr( $date_iso ); ?>" class="text-label-large text-on-surface-variant">
						<?php echo esc_html( $date_hr ); ?>
					</time>
				</div>
				<h2 class="text-display-small md:text-display-medium leading-tight text-on-surface">
					<a href="<?php echo esc_url( $permalink ); ?>" class="hover:text-primary focus:outline-none focus:underline decoration-2 underline-offset-4">
						<?php echo esc_html( $title ); ?>
					</a>
				</h2>
				<?php if ( $excerpt ) : ?>
					<p class="mt-spacing-3 line-clamp-2 text-body-large text-on-surface-variant max-w-prose">
						<?php echo esc_html( $excerpt ); ?>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
