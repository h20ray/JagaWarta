<?php
/**
 * Hero slider overlay card.
 * Expects $args['post_id'], optional $args['is_lcp'].
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}
$post_id = isset($args['post_id']) ? (int)$args['post_id'] : get_the_ID();
$is_lcp = !empty($args['is_lcp']);
$permalink = get_permalink($post_id);
$title = get_the_title($post_id);
$excerpt = wp_strip_all_tags(get_the_excerpt($post_id));
$date_iso = get_the_date(DATE_W3C, $post_id);
$date_human = get_the_date('', $post_id);
 
$category = get_the_category($post_id);
$cat = $category ? $category[0] : null;

$read_time = function_exists('jagawarta_read_time_label')
	? jagawarta_read_time_label($post_id)
	: '';

// Detect content length for smart layout
$title_length = mb_strlen($title);
$excerpt_length = mb_strlen($excerpt);
$has_long_content = $title_length > 60 || $excerpt_length > 100;
?>
<article class="jw-card jw-card--hero jw-hero-card relative">
	<a href="<?php echo esc_url($permalink); ?>" class="block h-full focus:outline-none">
		<div class="jw-hero-media hero-image-wrap relative w-full jw-media-wrap">
			<?php
$display = function_exists('jagawarta_get_post_display_image') ? jagawarta_get_post_display_image($post_id) : array('attachment_id' => 0, 'url' => '');
if (!empty($display['url'])):
	if (!empty($display['attachment_id'])):
		jagawarta_the_image(
			$display['attachment_id'],
			array(
			'lcp' => $is_lcp,
			'size' => 'large',
			'sizes' => '(max-width: 1024px) 100vw, 1024px',
			'class' => 'h-full w-full object-cover',
		)
		);
	else:
?>
					<img src="<?php echo esc_url($display['url']); ?>" alt="" loading="<?php echo $is_lcp ? 'eager' : 'lazy'; ?>" <?php echo $is_lcp ? 'fetchpriority="high" ' : ''; ?>decoding="async" class="h-full w-full object-cover" />
					<?php
	endif;
else:
?>
				<div class="h-full w-full bg-surface-high"></div>
			<?php
endif; ?>

			<div class="jw-hero-overlay absolute inset-0"></div>
			<?php if ($cat): ?>
				<div class="jw-chip-overlay">
					<?php jagawarta_the_category_chip($cat, array('size' => 'small', 'show_link' => false)); ?>
				</div>
			<?php
endif; ?>
			<div class="absolute inset-0 flex items-end">
				<div class="jw-hero-content w-full min-h-32 p-spacing-4 sm:min-h-40 sm:p-spacing-6 lg:min-h-48 lg:p-spacing-8<?php echo $has_long_content ? ' jw-hero-content--flexible' : ''; ?>">
					<h2 class="jw-hero-title mt-spacing-2 max-w-3xl text-on-primary-container">
						<?php echo esc_html($title); ?>
					</h2>

					<div class="jw-hero-meta mt-spacing-2 flex flex-wrap items-center gap-x-spacing-3 gap-y-spacing-1 text-on-primary-container">
						<time datetime="<?php echo esc_attr($date_iso); ?>"><?php echo esc_html($date_human); ?></time>
						<?php if ($read_time): ?>
							<span aria-hidden="true">•</span>
							<span><?php echo esc_html($read_time); ?></span>
						<?php
endif; ?>
					</div>
				</div>
			</div>
		</div>
	</a>
</article>
