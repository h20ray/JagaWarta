<?php
/**
 * Single post.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$related_ids = jagawarta_get_related_posts( get_the_ID(), 3 );
?>

<main id="main" class="site-main">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php get_template_part( 'template-parts/single-header' ); ?>
		<?php get_template_part( 'template-parts/single-content' ); ?>
		
		<div class="mx-auto max-w-[726px] px-spacing-4">
			<?php get_template_part( 'template-parts/author-block' ); ?>
			<?php get_template_part( 'template-parts/single-footer' ); ?>
		</div>
	</article>

	<?php if ( ! empty( $related_ids ) ) : ?>
		<div class="border-t border-outline-variant mt-spacing-12 pt-spacing-10">
			<?php get_template_part( 'template-parts/related-posts', null, array( 'post_ids' => $related_ids ) ); ?>
		</div>
	<?php endif; ?>
</main>

<?php
get_footer();
