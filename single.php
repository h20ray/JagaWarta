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
		
		<div class="mx-auto max-w-content-max px-spacing-4">

			<?php get_template_part( 'template-parts/single-footer' ); ?>
		</div>
	</article>

	<?php if ( ! empty( $related_ids ) ) : ?>
		<?php get_template_part( 'template-parts/related-posts', null, array( 'post_ids' => $related_ids ) ); ?>
	<?php endif; ?>
</main>

<?php
get_footer();
