<?php
/**
 * Top Split: Left hero featured + right stacked cards.
 *
 * Expects:
 * - $args['slider_ids'] int[]
 * - $args['side_ids']   int[]
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}

$slider_ids = isset($args['slider_ids']) ? array_values(array_filter(array_map('intval', (array)$args['slider_ids']))) : array();
$side_ids = isset($args['side_ids']) ? array_values(array_filter(array_map('intval', (array)$args['side_ids']))) : array();

if (empty($slider_ids)) {
	return;
}

$use_slider = (bool)get_theme_mod('jagawarta_front_hero_slider', true);
$primary_id = (int)$slider_ids[0];
?>
<section class="bg-surface" aria-label="<?php esc_attr_e('Featured', 'jagawarta'); ?>">
	<div class="mx-auto max-w-screen-xl px-4 py-6">
		<div class="jw-top-split grid grid-cols-1 gap-4 lg:grid-cols-12">
			<div class="jw-top-split-main lg:col-span-8">
				<?php if ($use_slider && count($slider_ids) > 1): ?>
					<?php
	get_template_part(
		'template-parts/hero/hero-slider',
		null,
		array(
		'post_ids' => $slider_ids,
		'slider' => true,
	)
	);
?>
				<?php
elseif ($primary_id): ?>
					<?php
	$permalink = get_permalink($primary_id);
	$title = get_the_title($primary_id);
	$excerpt = wp_strip_all_tags(get_the_excerpt($primary_id));
	$date_iso = get_the_date(DATE_W3C, $primary_id);
	$date_human = get_the_date('', $primary_id);

	$category = get_the_category($primary_id);
	$cat = $category ? $category[0] : null;

	$read_time = function_exists('jagawarta_read_time_label')
		? jagawarta_read_time_label($primary_id)
		: '';
?>
					<article class="relative h-full overflow-hidden rounded-md bg-surface-high ring-1 ring-outline-variant">
						<a href="<?php echo esc_url($permalink); ?>" class="block focus:outline-none">
							<div class="relative jw-media-wrap">
								<?php
	$display = function_exists('jagawarta_get_post_display_image') ? jagawarta_get_post_display_image($primary_id) : array('attachment_id' => 0, 'url' => '');
	if (!empty($display['url'])):
		if (!empty($display['attachment_id'])):
			jagawarta_the_image(
				$display['attachment_id'],
				array(
				'lcp' => true,
				'size' => 'large',
				'sizes' => '100vw',
				'class' => 'h-hero-mobile w-full object-cover sm:h-hero-sm lg:h-hero-lg',
			)
			);
		else:
?>
										<img src="<?php echo esc_url($display['url']); ?>" alt="" loading="eager" fetchpriority="high" decoding="async" class="h-hero-mobile w-full object-cover sm:h-hero-sm lg:h-hero-lg" />
										<?php
		endif;
?>
									<div class="absolute inset-0 bg-scrim/40"></div>
									<?php if ($cat): ?>
										<div class="jw-chip-overlay">
											<?php jagawarta_the_category_chip($cat, array('size' => 'small', 'show_link' => false)); ?>
										</div>
									<?php
		endif; ?>
								<?php
	else: ?>
									<div class="h-hero-mobile w-full bg-surface-high sm:h-hero-sm lg:h-hero-lg"></div>
								<?php
	endif; ?>

								<div class="absolute inset-0 flex items-end">
									<div class="w-full p-5 sm:p-6 lg:p-8">
										<div class="flex flex-wrap items-center gap-2">
											<time datetime="<?php echo esc_attr($date_iso); ?>" class="text-label-small text-on-surface-variant">
												<?php echo esc_html($date_human); ?>
											</time>

											<?php if ($read_time): ?>
												<span aria-hidden="true" class="text-on-surface-variant">•</span>
												<span class="text-label-small text-on-surface-variant">
													<?php echo esc_html($read_time); ?>
												</span>
											<?php
	endif; ?>
										</div>

										<h1 class="mt-3 max-w-3xl text-headline-large text-on-surface sm:text-display-small lg:text-display-medium">
											<?php echo esc_html($title); ?>
										</h1>

										<?php if ($excerpt): ?>
											<p class="mt-3 max-w-prose text-body-large text-on-surface-variant line-clamp-2">
												<?php echo esc_html($excerpt); ?>
											</p>
										<?php
	endif; ?>

										<div class="mt-4 inline-flex items-center rounded-sm bg-primary-container px-3 py-2 text-body-medium text-on-primary-container">
											<?php esc_html_e('Read story', 'jagawarta'); ?>
										</div>
									</div>
								</div>
							</div>
						</a>
					</article>
				<?php
endif; ?>
			</div>

			<div class="jw-top-split-side flex flex-col justify-between gap-4 h-full lg:col-span-4">
				<?php foreach ($side_ids as $side_id): ?>
					<?php get_template_part('template-parts/cards/card-bento', null, array('post_id' => $side_id)); ?>
				<?php
endforeach; ?>
			</div>
		</div>
	</div>
</section>
