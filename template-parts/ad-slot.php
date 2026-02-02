<?php
/**
 * Ad slot placeholder. Plugin or third-party targets via data-ad-slot.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$slot = isset( $args['slot'] ) ? sanitize_key( (string) $args['slot'] ) : 'default';
?>
<div class="jagawarta-ad-slot my-4 min-h-[90px]" data-ad-slot="<?php echo esc_attr( $slot ); ?>" aria-hidden="true"></div>
