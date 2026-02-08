<?php
/**
 * Simple list-style post item (no thumbnail).
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id   = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$index     = isset( $args['index'] ) ? (int) $args['index'] : 1;
$date_iso  = get_the_date( DATE_W3C, $post_id );
$date_hr   = function_exists( 'jagawarta_format_date' ) ? jagawarta_format_date( $post_id ) : get_the_date( '', $post_id );
$author_id = (int) get_post_field( 'post_author', $post_id );
$author    = $author_id ? get_the_author_meta( 'display_name', $author_id ) : '';
$category  = get_the_category( $post_id );
$cat       = $category ? $category[0] : null;
?>
<li class="jw-post-list-item">
	<span class="jw-post-rank" aria-hidden="true"><?php echo esc_html( $index ); ?></span>
	<div class="jw-post-list-body">
		<a href="<?php the_permalink(); ?>" class="jw-post-list-title">
			<?php the_title(); ?>
		</a>
		<div class="jw-post-list-meta">
			<?php if ( $cat ) : ?>
				<span class="jw-post-list-meta-item"><?php echo esc_html( $cat->name ); ?></span>
				<span class="jw-post-list-sep" aria-hidden="true">·</span>
			<?php endif; ?>
			<?php if ( $author ) : ?>
				<span class="jw-post-list-meta-item"><?php echo esc_html( $author ); ?></span>
				<span class="jw-post-list-sep" aria-hidden="true">·</span>
			<?php endif; ?>
			<time datetime="<?php echo esc_attr( $date_iso ); ?>" class="jw-post-list-meta-item"><?php echo esc_html( $date_hr ); ?></time>
		</div>
	</div>
</li>
