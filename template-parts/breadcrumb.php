<?php
/**
 * Breadcrumb Navigation (Google Blog Style).
 * Supports single (post with category) and archive context.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$breadcrumb_parts = array();
$context         = get_query_var( 'breadcrumb_context', 'single' );

if ( $context === 'archive' ) {
	$breadcrumb_parts[] = array(
		'name' => __( 'Home', 'jagawarta' ),
		'url'  => home_url( '/' ),
	);
	$obj = get_queried_object();
	if ( is_category() && $obj ) {
		if ( $obj->parent ) {
			$parent = get_category( $obj->parent );
			if ( $parent && ! is_wp_error( $parent ) ) {
				$breadcrumb_parts[] = array( 'name' => $parent->name, 'url' => get_category_link( $parent->term_id ) );
			}
		}
		$breadcrumb_parts[] = array( 'name' => $obj->name, 'url' => '' );
	} elseif ( is_tag() && $obj ) {
		$breadcrumb_parts[] = array( 'name' => $obj->name, 'url' => '' );
	} elseif ( is_author() ) {
		$breadcrumb_parts[] = array( 'name' => get_the_author(), 'url' => '' );
	} elseif ( is_date() ) {
		$breadcrumb_parts[] = array( 'name' => get_the_archive_title(), 'url' => '' );
	} else {
		$breadcrumb_parts[] = array( 'name' => get_the_archive_title(), 'url' => '' );
	}
} else {
	$post_id    = get_the_ID();
	$categories = get_the_category( $post_id );
	if ( empty( $categories ) ) {
		return;
	}
	$primary_cat = $categories[0];
	$breadcrumb_parts[] = array(
		'name' => __( 'Home', 'jagawarta' ),
		'url'  => home_url( '/' ),
	);
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
}

if ( empty( $breadcrumb_parts ) ) {
	return;
}
?>
<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'jagawarta' ); ?>" class="<?php echo $context === 'archive' ? 'w-full' : 'layout-article-inner'; ?> flex items-center gap-spacing-2 mb-spacing-2">
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
		<?php if ( ! empty( $part['url'] ) ) : ?>
			<a href="<?php echo esc_url( $part['url'] ); ?>" class="inline-flex items-center px-spacing-3 py-spacing-1 text-label-medium font-medium uppercase tracking-wide text-primary hover:bg-surface-high rounded-lg transition-all duration-short ease-standard no-underline" aria-label="<?php echo esc_attr( $part['name'] ); ?>">
				<?php if ( $part['name'] === __( 'Home', 'jagawarta' ) ) : ?>
					<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
					</svg>
				<?php else : ?>
					<?php echo esc_html( $part['name'] ); ?>
				<?php endif; ?>
			</a>
		<?php else : ?>
			<span class="inline-flex items-center px-spacing-3 py-spacing-1 text-label-medium font-medium uppercase tracking-wide text-on-surface-variant">
				<?php echo esc_html( $part['name'] ); ?>
			</span>
		<?php endif; ?>
	<?php endforeach; ?>
</nav>
