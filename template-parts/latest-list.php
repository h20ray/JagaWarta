<?php
/**
 * Latest list — receives WP_Query in args.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$query = isset( $args['query'] ) && $args['query'] instanceof WP_Query ? $args['query'] : null;
if ( ! $query || ! $query->have_posts() ) {
	return;
}
?>
<ul class="grid gap-6 list-none m-0 p-0 sm:grid-cols-2 lg:grid-cols-3">
	<?php
	while ( $query->have_posts() ) {
		$query->the_post();
		?>
		<li class="flex">
			<?php get_template_part( 'template-parts/cards/card-categories' ); ?>
		</li>
		<?php
	}
	wp_reset_postdata();
	?>
</ul>
