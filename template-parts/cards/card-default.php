<?php
/**
 * Default reusable card blueprint.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id   = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$date_iso  = get_the_date( DATE_W3C, $post_id );
$date_hr   = function_exists( 'jagawarta_format_date' ) ? jagawarta_format_date( $post_id ) : get_the_date( '', $post_id );
$author_id = (int) get_post_field( 'post_author', $post_id );
$author    = $author_id ? get_the_author_meta( 'display_name', $author_id ) : '';
$excerpt   = trim( get_the_excerpt( $post_id ) );
$has_thumb = has_post_thumbnail( $post_id );
$category  = get_the_category( $post_id );
?>
<article class="jw-card group">
	<a href="<?php the_permalink(); ?>" class="flex flex-col h-full focus:outline-none focus:ring-2 focus:ring-primary rounded-md" aria-label="<?php the_title_attribute(); ?>">
		<?php if ( $has_thumb ) : ?>
			<div class="jw-card-media jw-media-wrap">
				<?php jagawarta_the_post_display_image( $post_id, array( 'lcp' => false, 'class' => 'h-full w-full object-cover transition-transform duration-short ease-in group-hover:scale-105' ) ); ?>
				<?php if ( $category ) : ?>
					<div class="jw-chip-overlay">
						<?php jagawarta_the_category_chip( $category[0], array( 'size' => 'small', 'show_link' => false ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="jw-card-content">
			<h3 class="text-title-large font-normal leading-tight text-on-surface line-clamp-3 group-hover:text-primary transition-colors duration-short">
				<?php the_title(); ?>
			</h3>

			<?php if ( $excerpt ) : ?>
				<p class="mt-spacing-3 text-body-medium text-on-surface-variant line-clamp-3">
					<?php echo esc_html( $excerpt ); ?>
				</p>
			<?php endif; ?>

			<div class="mt-auto flex items-center justify-between gap-spacing-2 pt-spacing-4 text-label-medium text-on-surface-variant">
				<span>
					<?php if ( $author ) : ?>
						<?php echo esc_html( $author ); ?>
						<span class="mx-1" aria-hidden="true">·</span>
					<?php endif; ?>
					<time datetime="<?php echo esc_attr( $date_iso ); ?>"><?php echo esc_html( $date_hr ); ?></time>
				</span>
			</div>
		</div>
	</a>
</article>
