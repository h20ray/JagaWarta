<?php
/**
 * Loop item: Post Card (Google Blog Style).
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
<article class="group flex flex-col h-full rounded-xl bg-surface-low shadow-elevation-1 transition-all duration-medium ease-emphasized hover:bg-surface-high hover:shadow-elevation-3 hover:-translate-y-1 p-spacing-6">
	<a href="<?php the_permalink(); ?>" class="flex flex-col h-full focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-xl" aria-label="<?php the_title_attribute(); ?>">
		
		<!-- Image -->
		<div class="mb-spacing-6 overflow-hidden rounded-xl bg-surface-low aspect-[4/3]">
			<?php 
			jagawarta_the_post_display_image( $post_id, array( 
				'lcp' => false, 
				'class' => 'h-full w-full object-cover transition-transform duration-long ease-standard group-hover:scale-105' 
			) ); 
			?>
		</div>

		<!-- Content -->
		<div class="flex flex-col flex-grow">
			<!-- Category Chip -->
			<?php if ( $cat ) : ?>
				<div class="mb-spacing-3">
					<?php jagawarta_the_category_chip( $cat, array( 'size' => 'medium' ) ); ?>
				</div>
			<?php endif; ?>

			<!-- Title -->
			<h3 class="mb-spacing-4 text-headline-small leading-tight text-on-surface group-hover:text-primary transition-colors duration-short">
				<?php the_title(); ?>
			</h3>

			<!-- Excerpt (Optional) -->
			<div class="mb-spacing-6 line-clamp-3 text-body-medium text-on-surface-variant">
				<?php the_excerpt(); ?>
			</div>

			<!-- Meta -->
			<div class="mt-auto flex items-center gap-2 text-label-small text-on-surface-variant">
				<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
					<?php echo esc_html( get_the_date() ); ?>
				</time>
			</div>
		</div>
	</a>
</article>
