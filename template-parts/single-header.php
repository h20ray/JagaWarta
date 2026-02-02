<?php
/**
 * Single: chip, H1, meta, featured image (LCP).
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$post_id   = get_the_ID();
$title     = get_the_title( $post_id );
$date_iso  = get_the_date( DATE_W3C, $post_id );
$date_hr   = get_the_date( '', $post_id );
$author_id = (int) get_post_field( 'post_author', $post_id );
$author    = get_the_author_meta( 'display_name', $author_id );
$author_url = get_author_posts_url( $author_id );
$category  = get_the_category( $post_id );
$cat       = $category ? $category[0] : null;
$read_time = jagawarta_read_time_label( $post_id );
?>
<header class="bg-surface">
	<div class="mx-auto max-w-prose px-4 pt-6">
		<?php if ( $cat ) : ?>
			<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
				class="inline-flex items-center rounded-sm bg-secondary-container px-2 py-1 text-[0.75rem] leading-5 text-on-secondary-container">
				<?php echo esc_html( $cat->name ); ?>
			</a>
		<?php endif; ?>

		<h1 class="mt-3 text-[2rem] leading-tight text-on-surface">
			<?php echo esc_html( $title ); ?>
		</h1>

		<div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-[0.875rem] leading-6 text-on-surface-variant">
			<span>
				<a class="text-primary underline-offset-2 hover:underline focus:underline" href="<?php echo esc_url( $author_url ); ?>">
					<?php echo esc_html( $author ); ?>
				</a>
			</span>
			<span aria-hidden="true">•</span>
			<time datetime="<?php echo esc_attr( $date_iso ); ?>">
				<?php echo esc_html( $date_hr ); ?>
			</time>
			<?php if ( $read_time ) : ?>
				<span aria-hidden="true">•</span>
				<span><?php echo esc_html( $read_time ); ?></span>
			<?php endif; ?>
		</div>
	</div>

	<?php
	$header_img = jagawarta_get_post_display_image( $post_id );
	if ( ! empty( $header_img['url'] ) ) :
		?>
		<div class="mx-auto mt-5 max-w-screen-lg px-4">
			<figure class="overflow-hidden rounded-md bg-surface-low ring-1 ring-outline-variant">
				<?php jagawarta_the_post_display_image( $post_id, array( 'lcp' => true, 'class' => 'object-cover' ) ); ?>
			</figure>
		</div>
	<?php endif; ?>
</header>
