<?php
/**
 * Single page.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>

<main id="main" class="site-main layout-content max-w-3xl layout-section">
	<?php
	while ( have_posts() ) {
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="mb-6">
				<h1 class="text-headline-large font-sans text-on-surface"><?php the_title(); ?></h1>
			</header>
			<div class="prose prose-article max-w-none max-w-[65ch]">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	}
	?>
</main>

<?php
get_footer();
