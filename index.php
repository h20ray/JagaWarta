<?php
/**
 * Fallback template.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main layout-content layout-section">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'template-parts/content', get_post_type() );
		}
		get_template_part( 'template-parts/pagination' );
	} else {
		get_template_part( 'template-parts/content', 'none' );
	}
	?>
</main>

<?php
get_footer();
