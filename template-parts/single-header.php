<?php
/**
 * Single: Article header (Google Blog style).
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}
$post_id = get_the_ID();
$title = get_the_title($post_id);
$date_iso = get_the_date(DATE_W3C, $post_id);
$date_hr = jagawarta_format_date($post_id);
$author_id = (int)get_post_field('post_author', $post_id);
$author = get_the_author_meta('display_name', $author_id);
$author_url = get_author_posts_url($author_id);
$avatar = get_avatar_url($author_id, array('size' => 48));
$read_time = jagawarta_read_time_label($post_id);
?>
<header class="pt-spacing-12 pb-spacing-10 flex flex-col">
	<!-- 1. Title Block -->
	<div class="mx-auto max-w-page-max w-full px-spacing-4 order-1">
		<?php jagawarta_part('template-parts/breadcrumb'); ?>

		<h1 class="layout-article-inner jw-article-title font-normal text-on-surface mb-spacing-4 tracking-tight leading-[1.35] md:leading-tight">
			<?php echo esc_html($title); ?>
		</h1>
	</div>

	<!-- 2. Mobile Meta row (Hidden on Desktop) -->
	<div class="mx-auto max-w-page-max w-full px-spacing-4 order-2 md:hidden mb-spacing-6">
		<div class="layout-article-inner flex items-center justify-between text-label-large text-on-surface-variant font-light">
			<time datetime="<?php echo esc_attr($date_iso); ?>">
				<?php echo esc_html($date_hr); ?>
			</time>
			<?php if ($read_time): ?>
				<?php jagawarta_the_read_time_pill($post_id); ?>
			<?php
endif; ?>
		</div>
	</div>

	<!-- 3. Featured Image (Full width on mobile) -->
	<?php
$header_img = jagawarta_get_post_display_image($post_id);
if (!empty($header_img['url'])):
?>
		<div class="mt-spacing-2 mb-spacing-8 w-full order-3 md:order-4 md:mb-0 md:mx-auto md:max-w-page-max md:px-spacing-4">
			<div class="layout-article-inner">
				<figure class="overflow-hidden md:rounded-xl shadow-elevation-1">
					<?php jagawarta_the_post_display_image($post_id, array('lcp' => true, 'class' => 'object-cover aspect-video w-full')); ?>
				</figure>
			</div>
		</div>
	<?php
endif; ?>

	<!-- 4. Excerpt + Desktop Meta Sidebar -->
	<div class="mx-auto max-w-page-max w-full px-spacing-4 order-4 md:order-2">
		<?php if (has_excerpt()): ?>
			<div class="layout-article-inner flex flex-col md:flex-row mb-spacing-10 md:mb-spacing-6">
				<aside class="hidden md:block md:w-sidebar-width flex-shrink-0 pr-0 md:pr-spacing-6 border-r-0 md:border-r border-outline-variant">
					<div class="text-label-large font-light text-on-surface-variant leading-relaxed">
						<time datetime="<?php echo esc_attr($date_iso); ?>" class="block">
							<?php echo esc_html($date_hr); ?>
						</time>
						<?php if ($read_time): ?>
							<span class="block mt-spacing-2"><?php jagawarta_the_read_time_pill($post_id); ?></span>
						<?php
	endif; ?>
					</div>
				</aside>
				
				<div class="flex-1 pl-0 md:pl-spacing-6 pt-0 text-body-large font-normal text-on-surface-variant leading-[1.65] jw-single-excerpt">
					<?php the_excerpt(); ?>
				</div>
			</div>
		<?php
else: ?>
			<div class="layout-article-inner hidden md:flex flex-wrap items-center gap-x-spacing-4 text-label-large text-on-surface-variant mb-spacing-8">
				<time datetime="<?php echo esc_attr($date_iso); ?>">
					<?php echo esc_html($date_hr); ?>
				</time>
				<?php if ($read_time): ?>
					<?php jagawarta_the_read_time_pill($post_id); ?>
				<?php
	endif; ?>
			</div>
		<?php
endif; ?>
	</div>
	
	<!-- 5. Author + Share -->
	<div class="mx-auto max-w-page-max w-full px-spacing-4 order-5 md:order-3">
		<div class="layout-article-inner flex flex-col sm:flex-row sm:items-center justify-between gap-y-spacing-6 mb-spacing-10">
			<div class="flex items-center gap-spacing-3">
				<?php if ($avatar): ?>
					<img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($author); ?>" class="w-10 h-10 rounded-full bg-surface-variant shadow-elevation-1" loading="lazy">
				<?php
endif; ?>
				<div class="flex flex-col">
					<a href="<?php echo esc_url($author_url); ?>" class="text-body-medium font-bold text-on-surface hover:text-primary transition-colors duration-short no-underline">
						<?php echo esc_html($author); ?>
					</a>
					<?php
$author_title = get_the_author_meta('description', $author_id);
if ($author_title):
?>
						<span class="text-body-small text-on-surface-variant">
							<?php echo esc_html(wp_trim_words($author_title, 10)); ?>
						</span>
					<?php
endif; ?>
				</div>
			</div>

			<div class="flex items-center justify-between sm:justify-end gap-spacing-4 border-t border-outline-variant sm:border-t-0 pt-spacing-4 sm:pt-0">
				<?php
$view_count = jagawarta_view_count_display($post_id);
if ($view_count):
?>
					<span class="inline-flex items-center gap-spacing-2 px-spacing-3 py-spacing-1 text-label-medium font-medium text-on-surface-variant hover:bg-surface-high rounded-lg transition-all duration-short ease-standard">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
							<circle cx="12" cy="12" r="3"></circle>
						</svg>
						<span><?php echo esc_html($view_count); ?></span>
					</span>
				<?php
endif; ?>

				<?php jagawarta_part('template-parts/share-button'); ?>
			</div>
		</div>
	</div>
</header>
