<?php
/**
 * Share helpers for single posts.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get normalized share data for the current singular post.
 *
 * @return array{
 *   url: string,
 *   title: string,
 *   text: string,
 *   services: array<string, array<string, string>>
 * }
 */
function jagawarta_get_share_data(): array {
	$post_id = get_queried_object_id();

	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$url   = get_permalink( $post_id );
	$title = get_the_title( $post_id );

	$excerpt = get_the_excerpt( $post_id );
	if ( ! $excerpt ) {
		$excerpt = wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ), 24, '…' );
	}

	$text = $title;
	if ( $excerpt ) {
		$text .= ' — ' . $excerpt;
	}

	$encoded_url   = rawurlencode( $url );
	$encoded_title = rawurlencode( $title );
	$encoded_text  = rawurlencode( $text );

	$services = array(
		'x'        => array(
			'label' => __( 'Share on X (Twitter)', 'jagawarta' ),
			'url'   => 'https://twitter.com/intent/tweet?url=' . $encoded_url . '&text=' . $encoded_title,
		),
		'facebook' => array(
			'label' => __( 'Share on Facebook', 'jagawarta' ),
			'url'   => 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url,
		),
		'whatsapp' => array(
			'label' => __( 'Share on WhatsApp', 'jagawarta' ),
			'url'   => 'https://wa.me/?text=' . $encoded_text,
		),
		'telegram' => array(
			'label' => __( 'Share on Telegram', 'jagawarta' ),
			'url'   => 'https://t.me/share/url?url=' . $encoded_url . '&text=' . $encoded_title,
		),
		'email'    => array(
			'label' => __( 'Share via email', 'jagawarta' ),
			'url'   => 'mailto:?subject=' . rawurlencode( wp_specialchars_decode( $title ) ) . '&body=' . $encoded_text . '%0A%0A' . $encoded_url,
		),
		'copy'     => array(
			'label' => __( 'Copy link', 'jagawarta' ),
			'url'   => $url,
		),
	);

	return array(
		'url'      => $url,
		'title'    => $title,
		'text'     => $text,
		'services' => $services,
	);
}

