<?php
/**
 * Newsletter slot placeholder. Plugin or third-party targets via data-newsletter-slot.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$slot = isset( $args['slot'] ) ? sanitize_key( (string) $args['slot'] ) : 'footer';
?>
<div class="jagawarta-newsletter-slot my-6 min-h-[60px]" data-newsletter-slot="<?php echo esc_attr( $slot ); ?>" aria-hidden="true"></div>
