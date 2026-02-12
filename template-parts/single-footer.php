<?php
/**
 * Single: Article Footer (Google Blog Style).
 * Tags section labeled "POSTED IN:".
 *
 * @package JagaWarta
 */

if (!defined('ABSPATH')) {
	exit;
}

$tags = get_the_tags();

if (!$tags) {
	return;
}

// Limit to max 4 tags as per Google Blog style request
$tags = array_slice($tags, 0, 4);
?>
<footer class="mt-spacing-8 pt-spacing-8 border-t border-outline-variant" data-reading-end>
	<div class="flex flex-col sm:flex-row sm:items-baseline gap-spacing-4">
		<span class="text-body-large font-medium uppercase tracking-wide text-on-surface-variant flex-shrink-0">
			<?php esc_html_e('Posted In:', 'jagawarta'); ?>
		</span>
		<div class="flex flex-wrap gap-2">
			<?php foreach ($tags as $tag):
	// Convert tag name to #Hashtag format (CamelCase)
	$tag_name = ucwords($tag->name);
	$tag_name = str_replace(' ', '', $tag_name);
	$tag_text = '#' . $tag_name;
?>
				<a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" 
				   class="inline-flex items-center px-spacing-4 py-spacing-1 bg-surface-container-high text-on-surface-variant rounded-full text-label-medium font-normal transition-all duration-short hover:bg-primary-container hover:text-primary hover:shadow-elevation-1 no-underline border border-outline-variant">
					<?php echo esc_html($tag_text); ?>
				</a>
			<?php
endforeach; ?>
		</div>
	</div>
</footer>
