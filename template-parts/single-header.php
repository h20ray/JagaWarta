<?php
/**
 * Single: Google Blog Style Header.
 * All spacing using design tokens, properly aligned.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$post_id    = get_the_ID();
$title      = get_the_title( $post_id );
$date_iso   = get_the_date( DATE_W3C, $post_id );
$date_hr    = jagawarta_format_date( $post_id );
$author_id  = (int) get_post_field( 'post_author', $post_id );
$author     = get_the_author_meta( 'display_name', $author_id );
$author_url = get_author_posts_url( $author_id );
$avatar     = get_avatar_url( $author_id, array( 'size' => 48 ) );
$read_time  = jagawarta_read_time_label( $post_id );
?>
<header class="pt-spacing-12 pb-spacing-10 px-spacing-4">
	<div class="mx-auto max-w-[1260px]">
		<?php get_template_part( 'template-parts/breadcrumb' ); ?>
		
		<!-- H1 Title: 1046px width, 107px left offset -->
		<h1 class="text-display-large font-normal text-on-surface mb-spacing-4 max-w-[1046px] ml-0 md:ml-[107px]" style="letter-spacing: -0.5px;">
			<?php echo esc_html( $title ); ?>
		</h1>

		<?php if ( has_excerpt() ) : ?>
			<!-- Abstract: 2-column layout with 24px padding on each side of separator = 48px gap -->
			<div class="flex flex-col md:flex-row mb-spacing-6 max-w-[1046px] ml-0 md:ml-[107px]">
				<aside class="w-full md:w-[127px] flex-shrink-0 pr-0 md:pr-spacing-6 border-r-0 md:border-r border-outline-variant">
					<div class="text-label-large font-light text-on-surface-variant leading-relaxed">
						<time datetime="<?php echo esc_attr( $date_iso ); ?>" class="block">
							<?php echo esc_html( $date_hr ); ?>
						</time>
						<?php if ( $read_time ) : ?>
							<span class="inline-flex items-center px-spacing-3 py-spacing-1 bg-primary-container text-primary rounded-full text-label-medium font-medium mt-spacing-2">
								<?php echo esc_html( $read_time ); ?>
							</span>
						<?php endif; ?>
					</div>
				</aside>
				
				<div class="flex-1 pl-0 md:pl-spacing-6 pt-spacing-2 md:pt-0 text-body-large font-normal text-on-surface-variant leading-relaxed">
					<?php the_excerpt(); ?>
				</div>
			</div>
		<?php else : ?>
			<!-- No excerpt: inline metadata (date and read time only, author appears below) -->
			<div class="flex flex-wrap items-center gap-x-spacing-4 text-label-large text-on-surface-variant mb-spacing-8 max-w-[1046px] ml-0 md:ml-[107px]">
				<time datetime="<?php echo esc_attr( $date_iso ); ?>">
					<?php echo esc_html( $date_hr ); ?>
				</time>
				<?php if ( $read_time ) : ?>
					<span class="inline-flex items-center px-spacing-3 py-spacing-1 bg-primary-container text-primary rounded-full text-label-medium font-medium">
						<?php echo esc_html( $read_time ); ?>
					</span>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		
		<!-- Author Section with View Count and Share Button -->
		<div class="flex flex-wrap items-center justify-between gap-x-spacing-6 gap-y-spacing-4 mb-spacing-8 max-w-[1046px] ml-0 md:ml-[107px]">
			<!-- Author Info -->
			<div class="flex items-center gap-spacing-3">
				<?php if ( $avatar ) : ?>
					<img src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $author ); ?>" class="w-10 h-10 rounded-full bg-surface-variant shadow-elevation-1" loading="lazy">
				<?php endif; ?>
				<div class="flex flex-col">
					<a href="<?php echo esc_url( $author_url ); ?>" class="text-body-medium font-bold text-on-surface hover:text-primary transition-colors duration-short no-underline">
						<?php echo esc_html( $author ); ?>
					</a>
					<?php
					$author_title = get_the_author_meta( 'description', $author_id );
					if ( $author_title ) :
						?>
						<span class="text-body-small text-on-surface-variant">
							<?php echo esc_html( wp_trim_words( $author_title, 10 ) ); ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
			
			<!-- View Count and Share -->
			<div class="flex items-center gap-spacing-4">
				<!-- View Count (same pill style as share button) -->
				<?php
				$view_count = jagawarta_view_count_display( $post_id );
				if ( $view_count ) :
					?>
					<span class="inline-flex items-center gap-spacing-2 px-spacing-3 py-spacing-1 text-label-medium font-medium text-on-surface-variant hover:bg-surface-high rounded-lg transition-all duration-short ease-standard">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
							<circle cx="12" cy="12" r="3"></circle>
						</svg>
						<span><?php echo esc_html( $view_count ); ?></span>
					</span>
				<?php endif; ?>
				
				<!-- Share Button -->
				<?php get_template_part( 'template-parts/share-button' ); ?>
			</div>
		</div>
	</div>

	<!-- Featured Image: Aligned with H1 (107px left, 1046px width) -->
	<?php
	$header_img = jagawarta_get_post_display_image( $post_id );
	if ( ! empty( $header_img['url'] ) ) :
		?>
		<div class="mx-auto mt-spacing-6 max-w-[1260px] px-spacing-4">
			<div class="max-w-[1046px] ml-0 md:ml-[107px]">
				<figure class="overflow-hidden rounded-xl shadow-elevation-1">
					<?php jagawarta_the_post_display_image( $post_id, array( 'lcp' => true, 'class' => 'object-cover aspect-video w-full' ) ); ?>
				</figure>
			</div>
		</div>
	<?php endif; ?>
</header>
