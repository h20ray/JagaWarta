<?php
/**
 * Author archive.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>

<main id="main" class="site-main mx-auto max-w-6xl px-4 py-8">
	<header class="mb-8 border-b border-outline-variant pb-4">
		<?php the_archive_title( '<h1 class="text-headline-large font-serif text-on-surface">', '</h1>' ); ?>
		<?php the_archive_description( '<p class="mt-2 text-body-medium text-on-surface-variant">', '</p>' ); ?>
	</header>
	<?php if ( have_posts() ) : ?>
		<ul class="grid gap-6 list-none m-0 p-0 sm:grid-cols-2 lg:grid-cols-3">
			<?php
			while ( have_posts() ) {
				the_post();
				?>
				<li><?php get_template_part( 'template-parts/post-card' ); ?></li>
				<?php
			}
			?>
		</ul>
		<?php get_template_part( 'template-parts/pagination' ); ?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</main>

<?php
get_footer();
