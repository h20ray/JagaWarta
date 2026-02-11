<?php
/**
 * Share modal popup for single posts.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$share = jagawarta_get_share_data();

$url   = $share['url'];
$title = $share['title'];
$text  = $share['text'];

$services = $share['services'];
?>
<div class="jw-share-modal" data-share-modal hidden>
	<div class="jw-share-modal-scrim" data-share-modal-scrim></div>
	<div class="jw-share-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="jw-share-modal-title">
		<div class="jw-share-modal-header">
			<h2 id="jw-share-modal-title" class="jw-share-modal-title">
				<?php esc_html_e( 'Share article', 'jagawarta' ); ?>
			</h2>
			<button
				type="button"
				class="jw-share-modal-close"
				data-share-modal-close
				aria-label="<?php esc_attr_e( 'Close share dialog', 'jagawarta' ); ?>"
			>
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</button>
		</div>

		<div class="jw-share-modal-content" data-share-root data-share-title="<?php echo esc_attr( $title ); ?>" data-share-text="<?php echo esc_attr( $text ); ?>" data-share-url="<?php echo esc_url( $url ); ?>">
			<div class="jw-share-modal-services">
				<?php
				$ordered_keys = array( 'x', 'facebook', 'whatsapp', 'telegram', 'email', 'copy' );

				foreach ( $ordered_keys as $key ) :
					if ( ! isset( $services[ $key ] ) ) {
						continue;
					}

					$service = $services[ $key ];
					$label   = $service['label'];
					$link    = $service['url'];
					$is_copy = ( 'copy' === $key );
					?>
					<?php if ( $is_copy ) : ?>
						<button
							type="button"
							class="jw-share-modal-item"
							data-share-copy
							data-share-copy-url="<?php echo esc_url( $link ); ?>"
							aria-label="<?php echo esc_attr( $label ); ?>"
						>
							<span class="jw-share-modal-item-icon" aria-hidden="true">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
									<path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
								</svg>
							</span>
							<span class="jw-share-modal-item-label"><?php esc_html_e( 'Copy link', 'jagawarta' ); ?></span>
						</button>
					<?php else : ?>
						<a
							href="<?php echo esc_url( $link ); ?>"
							class="jw-share-modal-item"
							target="_blank"
							rel="noopener noreferrer"
							aria-label="<?php echo esc_attr( $label ); ?>"
						>
							<span class="jw-share-modal-item-icon" aria-hidden="true">
								<?php
								switch ( $key ) {
									case 'x':
										?>
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M18.25 3H21L14.5 10.37L22.5 21H16.75L12.2 14.85L6.95 21H4.2L11.15 13.09L3.5 3H9.4L13.45 8.63L18.25 3ZM17.25 19.2H18.82L8.82 4.7H7.15L17.25 19.2Z" fill="currentColor"/>
										</svg>
										<?php
										break;
									case 'facebook':
										?>
										<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
											<path d="M13.5 9H16V6H13.5C11.57 6 10 7.57 10 9.5V11H8V14H10V21H13V14H15.5L16 11H13V9.5C13 9.22 13.22 9 13.5 9Z"/>
										</svg>
										<?php
										break;
									case 'whatsapp':
										?>
										<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
											<path d="M20 4.5C17.8 2.3 14.7 1.3 11.6 1.8C7.1 2.5 3.6 6.1 3.1 10.6C2.8 13.2 3.5 15.7 5.1 17.8L4 22L8.3 20.9C9.9 21.8 11.7 22.2 13.5 22.2H13.6C18.6 22.1 22.6 18.1 22.9 13.1C23.1 9.9 22.1 6.8 20 4.5ZM13.6 19.9C12.2 19.9 10.9 19.6 9.7 19L9.3 18.8L7.1 19.4L7.7 17.3L7.5 16.9C6.4 15.5 5.9 13.8 6.1 12C6.4 8.9 8.9 6.4 12 6.1C14.2 5.9 16.3 6.7 17.8 8.3C19.3 9.9 20.1 12 19.9 14.2C19.7 17.3 17 19.9 13.6 19.9ZM16.4 13.6C16.2 13.5 15.4 13.1 15.2 13C15 12.9 14.9 12.9 14.7 13.1C14.5 13.3 14.1 13.8 14 13.9C13.9 14 13.8 14.1 13.6 14C13.4 13.9 12.7 13.7 11.9 13C11.3 12.5 10.9 11.8 10.8 11.6C10.7 11.4 10.8 11.3 10.9 11.2C11 11.1 11.1 10.9 11.2 10.8C11.3 10.7 11.4 10.5 11.5 10.3C11.6 10.1 11.5 10 11.4 9.9C11.3 9.8 10.7 8.9 10.5 8.6C10.3 8.3 10.1 8.3 9.9 8.3C9.8 8.3 9.6 8.3 9.4 8.3C9.2 8.3 8.9 8.5 8.8 8.7C8.6 9 8.1 9.5 8.1 10.4C8.1 11.3 8.8 12.2 8.9 12.4C9 12.6 10.2 14.5 12.1 15.4C12.8 15.7 13.4 15.9 13.8 16C14.4 16.1 14.9 16.1 15.3 16C15.7 15.9 16.5 15.5 16.7 14.9C16.9 14.3 16.9 13.8 16.8 13.6C16.7 13.6 16.5 13.6 16.4 13.6Z"/>
										</svg>
										<?php
										break;
									case 'telegram':
										?>
										<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
											<path d="M9.04 16.81L9.14 13.73L16.96 7.04C17.3 6.75 16.88 6.6 16.43 6.84L7.06 11.7L4.06 10.75C3.41 10.55 3.4 10.11 4.2 9.79L18.91 4.12C19.47 3.87 20 4.29 19.78 5.19L17.28 16.81C17.12 17.57 16.68 17.75 16.04 17.41L12.54 14.83L10.86 16.46C10.68 16.64 10.53 16.79 10.19 16.81L9.04 16.81Z"/>
										</svg>
										<?php
										break;
									case 'email':
										?>
										<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
											<path d="M20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 8L12 13L4 8V6L12 11L20 6V8Z"/>
										</svg>
										<?php
										break;
								}
								?>
							</span>
							<span class="jw-share-modal-item-label"><?php echo esc_html( $label ); ?></span>
						</a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<span class="sr-only" aria-live="polite" data-share-message></span>
</div>
