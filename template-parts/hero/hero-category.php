<?php
/**
 * Hero template for category/archive pages.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id  = get_the_ID();
$category = get_the_category( $post_id );
$cat      = $category ? $category[0] : null;
?>
<section class="group w-full">
	<a href="<?php the_permalink(); ?>" class="flex flex-col md:grid md:grid-cols-12 md:gap-spacing-6 items-stretch group">
		<div class="md:col-span-8 w-full relative overflow-hidden rounded-lg bg-surface-low aspect-hero-category order-1 md:order-1 shadow-elevation-2 transition-shadow duration-long ease-standard hover:shadow-elevation-3">
			<div class="absolute inset-0">
				<?php
				jagawarta_the_post_display_image( $post_id, array(
					'lcp'   => true,
					'class' => 'absolute inset-0 w-full h-full object-cover transition-transform duration-long ease-standard group-hover:scale-105',
				) );
				?>
			</div>
		</div>

		<div class="md:col-span-4 flex flex-col relative mt-spacing-6 md:mt-0 order-2 md:order-2 min-h-0">
			<div class="relative flex flex-col flex-grow justify-center px-spacing-3 pt-spacing-4 pb-spacing-10">
				<?php if ( $cat ) : ?>
					<div class="mb-spacing-4">
						<span class="text-label-large font-medium uppercase tracking-wide text-on-surface-variant">
							<?php echo esc_html( $cat->name ); ?>
						</span>
					</div>
				<?php endif; ?>

				<h2 class="mb-spacing-4 text-headline-medium md:text-headline-large font-bold leading-tight text-on-surface">
					<span class="underline decoration-2 decoration-transparent underline-offset-4 transition-[decoration-color,transform] duration-long ease-standard group-hover:decoration-on-surface">
						<?php the_title(); ?>
					</span>
				</h2>

				<div class="text-body-large text-on-surface-variant line-clamp-3 leading-relaxed">
					<?php
					$excerpt = get_the_excerpt();
					$excerpt = wp_strip_all_tags( $excerpt );
					$limit   = strpos( $excerpt, '.' );
					if ( false !== $limit ) {
						$excerpt = substr( $excerpt, 0, $limit + 1 );
					}
					echo esc_html( $excerpt );
					?>
				</div>

				<div aria-hidden="true" class="absolute bottom-spacing-10 right-spacing-10 flex text-on-surface transition-all duration-short ease-in group-hover:right-spacing-5 group-hover:text-primary">
					<?php jagawarta_svg_arrow_right(); ?>
				</div>
			</div>
		</div>
	</a>
</section>
