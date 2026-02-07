<?php
/**
 * Breadcrumb Navigation (Google Blog Style)  
 * Uses consistent pill/chip pattern from theme.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id    = get_the_ID();
$categories = get_the_category( $post_id );

// Only show breadcrumb if post has categories
if ( empty( $categories ) ) {
	return;
}

// Get primary category (first one) and parent if exists
$primary_cat = $categories[0];
$breadcrumb_parts = array();

// Always start with Home
$breadcrumb_parts[] = array(
	'name' => __( 'Home', 'jagawarta' ),
	'url'  => home_url( '/' ),
);

// Build breadcrumb hierarchy
if ( $primary_cat->parent ) {
	$parent_cat = get_category( $primary_cat->parent );
	if ( $parent_cat && ! is_wp_error( $parent_cat ) ) {
		$breadcrumb_parts[] = array(
			'name' => $parent_cat->name,
			'url'  => get_category_link( $parent_cat->term_id ),
		);
	}
}

$breadcrumb_parts[] = array(
	'name' => $primary_cat->name,
	'url'  => get_category_link( $primary_cat->term_id ),
);
?>
<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'jagawarta' ); ?>" class="layout-article-inner flex items-center gap-spacing-2 mb-spacing-4 min-h-12">
	<?php
	foreach ( $breadcrumb_parts as $index => $part ) :
		if ( $index > 0 ) :
			?>
			<svg class="breadcrumb-chevron flex-shrink-0 text-on-surface-variant" width="16" height="16" viewBox="0 0 24 24" fill="none" role="presentation" aria-hidden="true">
				<path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
			<?php
		endif;
		?>
		<a href="<?php echo esc_url( $part['url'] ); ?>" class="inline-flex items-center px-spacing-3 py-spacing-1 text-label-medium font-medium uppercase tracking-wide text-primary hover:bg-surface-high rounded-lg transition-all duration-short ease-standard no-underline">
			<?php echo esc_html( $part['name'] ); ?>
		</a>
	<?php endforeach; ?>
</nav>
