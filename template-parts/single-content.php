<?php
/**
 * Single: post content (Google Blog Style).
 * - Max width 726px
 * - Centered
 * - Readable typography
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="mx-auto max-w-[726px] px-spacing-4 py-spacing-12">
	<div class="prose prose-article max-w-none text-body-large text-on-surface-variant leading-relaxed [&>p]:mt-spacing-6 [&>p]:mb-0 [&>h2]:text-headline-medium [&>h2]:text-on-surface [&>h2]:pt-spacing-8 [&>h2]:pb-spacing-4 [&>h2]:mt-0 [&>h2]:mb-0 [&>h3]:text-title-large [&>h3]:text-on-surface [&>h3]:mt-spacing-8 [&>h3]:mb-0 [&>ul]:mt-spacing-4 [&>ul]:list-disc [&>ul]:pl-spacing-6 [&>ol]:mt-spacing-4 [&>ol]:list-decimal [&>ol]:pl-spacing-6 [&>blockquote]:mt-spacing-8 [&>blockquote]:border-l-4 [&>blockquote]:border-primary [&>blockquote]:pl-spacing-4 [&>blockquote]:italic [&_a]:text-primary [&_a]:underline [&_a]:decoration-2 [&_a]:underline-offset-2">
		<?php the_content(); ?>
	</div>
</div>
