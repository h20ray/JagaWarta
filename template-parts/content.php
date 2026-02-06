<?php
/**
 * Default post/content loop item.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'border-b border-outline-variant py-6' ); ?>>
	<?php get_template_part( 'template-parts/cards/post-card-categories' ); ?>
</article>
