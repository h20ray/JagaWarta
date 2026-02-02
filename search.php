<?php
/**
 * Search results.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>

<main id="main" class="site-main layout-content layout-section flex flex-col gap-10">
	<header class="pb-4 border-b border-outline-variant mb-2">
		<h1 class="text-headline-medium font-sans text-on-surface">
			<?php
			printf(
				/* translators: %s: search query */
				esc_html__( 'Search: %s', 'jagawarta' ),
				'<span>' . get_search_query() . '</span>'
			);
			?>
		</h1>
	</header>
	<?php if ( have_posts() ) : ?>
		<ul class="grid gap-6 list-none m-0 p-0 sm:grid-cols-2 lg:grid-cols-3">
			<?php
			while ( have_posts() ) {
				the_post();
				?>
				<li class="flex"><?php get_template_part( 'template-parts/post-card' ); ?></li>
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
