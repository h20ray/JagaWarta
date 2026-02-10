<?php
/**
 * Hero slider or grid (block/front). Expects $args['post_ids'], $args['slider'].
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}
$post_ids = isset($args['post_ids']) && is_array($args['post_ids']) ? $args['post_ids'] : array();
$slider = !empty($args['slider']);
if (empty($post_ids)) {
	return;
}

if ($slider):
	$slider_id = wp_unique_id('jw-hero-slider-');
	$slide_total = count($post_ids);
?>
	<section class="hero hero--slider" aria-label="<?php esc_attr_e('Featured', 'jagawarta'); ?>">
		<div
			id="<?php echo esc_attr($slider_id); ?>"
			class="jw-hero-slider transition-shadow duration-medium ease-emphasized"
			data-hero-slider="true"
			data-hero-slider-autoplay="false"
			aria-roledescription="<?php esc_attr_e('carousel', 'jagawarta'); ?>"
			aria-label="<?php esc_attr_e('Featured stories', 'jagawarta'); ?>"
		>
			<div class="jw-hero-slider__viewport" aria-live="polite" aria-atomic="true">
				<ul class="jw-hero-slider__list">
					<?php
	$slide_index = 0;
	foreach ($post_ids as $pid) {
		$post = get_post($pid);
		if (!$post) {
			continue;
		}
		setup_postdata($post);
		$is_active = (0 === $slide_index);
?>
						<li
							class="jw-hero-slider__slide"
							data-hero-slide="<?php echo esc_attr($pid); ?>"
							data-hero-slide-index="<?php echo esc_attr($slide_index); ?>"
							aria-roledescription="<?php esc_attr_e('slide', 'jagawarta'); ?>"
							aria-label="<?php echo esc_attr(sprintf(__('Slide %1$d of %2$d', 'jagawarta'), $slide_index + 1, $slide_total)); ?>"
							<?php if (!$is_active): ?>
								aria-hidden="true"
							<?php
		else: ?>
								aria-hidden="false"
							<?php
		endif; ?>
						>
							<?php
		get_template_part(
			'template-parts/cards/card-overlay',
			null,
			array(
			'post_id' => $pid,
			'is_lcp' => $is_active,
		)
		);
?>
						</li>
						<?php
		wp_reset_postdata();
		$slide_index++;
	}
?>
				</ul>
			</div>

			<?php if (count($post_ids) > 1): ?>
				<div class="jw-hero-slider__controls" aria-label="<?php esc_attr_e('Slider controls', 'jagawarta'); ?>">
					<button
						type="button"
						class="jw-hero-slider__arrow jw-hero-slider__arrow--prev"
						data-hero-slider-prev
						aria-controls="<?php echo esc_attr($slider_id); ?>"
						aria-label="<?php esc_attr_e('Previous featured story', 'jagawarta'); ?>"
					>
						<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg>
					</button>
					<button
						type="button"
						class="jw-hero-slider__arrow jw-hero-slider__arrow--next"
						data-hero-slider-next
						aria-controls="<?php echo esc_attr($slider_id); ?>"
						aria-label="<?php esc_attr_e('Next featured story', 'jagawarta'); ?>"
					>
						<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
					</button>
				</div>
			<?php
	endif; ?>
		</div>
	</section>
	<?php
else:
	$first_id = $post_ids[0];
	$rest = array_slice($post_ids, 1, 4);
?>
	<section class="hero grid gap-spacing-4 sm:gap-spacing-6 md:grid-cols-2 lg:grid-cols-3 lg:gap-spacing-8" aria-label="<?php esc_attr_e('Featured', 'jagawarta'); ?>">
		<div class="min-w-0 lg:col-span-2">
			<?php
	$post = get_post($first_id);
	if ($post) {
		setup_postdata($post);
		get_template_part('template-parts/cards/card-hero');
		wp_reset_postdata();
	}
?>
		</div>
		<?php if (!empty($rest)): ?>
			<div class="grid min-w-0 gap-spacing-4 sm:grid-cols-2 lg:grid-cols-1">
				<?php
		foreach ($rest as $pid) {
			$post = get_post($pid);
			if (!$post) {
				continue;
			}
			setup_postdata($post);
?>
					<div><?php get_template_part('template-parts/cards/card-categories', null, array('post_id' => $pid)); ?></div>
					<?php
			wp_reset_postdata();
		}
?>
			</div>
		<?php
	endif; ?>
	</section>
	<?php
endif;
