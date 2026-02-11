<?php
/**
 * Single: post content. Centered, readable width.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php jagawarta_part( 'template-parts/font-size-controls' ); ?>
<div class="mx-auto max-w-content-max px-spacing-4 pt-spacing-6 pb-spacing-12">
	<div class="prose prose-article max-w-none text-body-large text-on-surface-variant leading-relaxed [&>p]:mt-0 [&>p]:mb-spacing-6 [&>p:first-of-type]:mt-0 [&>p:last-of-type]:mb-0 [&>h2]:text-headline-medium [&>h2]:text-on-surface [&>h2]:mt-spacing-8 [&>h2]:mb-spacing-4 [&>h3]:text-title-large [&>h3]:text-on-surface [&>h3]:mt-spacing-8 [&>h3]:mb-spacing-3 [&>ul]:mt-spacing-6 [&>ul]:mb-spacing-4 [&>ul]:list-disc [&>ul]:pl-spacing-6 [&>ol]:mt-spacing-6 [&>ol]:mb-spacing-4 [&>ol]:list-decimal [&>ol]:pl-spacing-6 [&>blockquote]:mt-spacing-6 [&>blockquote]:mb-spacing-6 [&>blockquote]:border-l-4 [&>blockquote]:border-primary [&>blockquote]:pl-spacing-4 [&>blockquote]:italic [&_a]:text-primary [&_a]:underline [&_a]:decoration-2 [&_a]:underline-offset-2">
		<?php the_content(); ?>
	</div>
</div>
