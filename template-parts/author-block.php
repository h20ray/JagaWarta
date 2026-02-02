<?php
/**
 * Author Block (Google Blog Style).
 * Enhanced author presentation with avatar and bio.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id    = get_the_ID();
$author_id  = (int) get_post_field( 'post_author', $post_id );
$author     = get_the_author_meta( 'display_name', $author_id );
$author_url = get_author_posts_url( $author_id );
$avatar     = get_avatar_url( $author_id, array( 'size' => 96 ) );
$bio        = get_the_author_meta( 'description', $author_id );
?>
<div class="author-block py-spacing-8 border-t border-outline-variant">
	<div class="flex items-start gap-spacing-4">
		<?php if ( $avatar ) : ?>
			<a href="<?php echo esc_url( $author_url ); ?>" class="flex-shrink-0">
				<img 
					src="<?php echo esc_url( $avatar ); ?>" 
					alt="<?php echo esc_attr( $author ); ?>" 
					class="w-12 h-12 rounded-full bg-surface-container shadow-elevation-1"
					loading="lazy"
				>
			</a>
		<?php endif; ?>
		
		<div class="flex-1 min-w-0">
			<div class="text-title-medium font-bold text-on-surface mb-1">
				<a href="<?php echo esc_url( $author_url ); ?>" class="hover:text-primary transition-colors duration-short">
					<?php echo esc_html( $author ); ?>
				</a>
			</div>
			
			<?php if ( ! empty( $bio ) ) : ?>
				<div class="text-body-medium text-on-surface-variant">
					<?php echo esc_html( $bio ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
