<?php
/**
 * Theme helpers — no DB writes, pure utilities.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jagawarta_excerpt_length( int $length = 25 ): int {
	return (int) apply_filters( 'jagawarta_excerpt_length', $length );
}

function jagawarta_excerpt_more( string $more = '…' ): string {
	return (string) apply_filters( 'jagawarta_excerpt_more', $more );
}

function jagawarta_read_time( $post = null ): int {
	$post = get_post( $post );
	if ( ! $post || empty( $post->post_content ) ) {
		return 0;
	}
	$meta = get_post_meta( $post->ID, '_jagawarta_read_time', true );
	if ( '' !== $meta && is_numeric( $meta ) ) {
		return (int) $meta;
	}
	$text = wp_strip_all_tags( $post->post_content );
	$words = str_word_count( $text );
	$mins = (int) max( 1, ceil( $words / 200 ) );
	return $mins;
}

function jagawarta_read_time_label( $post = null ): string {
	$mins = jagawarta_read_time( $post );
	if ( $mins <= 0 ) {
		return '';
	}
	return sprintf( _n( '%d min read', '%d min read', $mins, 'jagawarta' ), $mins );
}

function jagawarta_view_count_display( int $post_id ): string {
	$count = apply_filters( 'jagawarta_view_count', null, $post_id );
	if ( is_numeric( $count ) && (int) $count >= 0 ) {
		return (string) number_format_i18n( (int) $count );
	}
	$post = get_post( $post_id );
	if ( ! $post ) {
		return '';
	}
	$ymd = get_the_date( 'Ymd', $post );
	$seed = crc32( (string) $post_id . $ymd );
	$base = abs( $seed ) % 400;
	$age_days = ( time() - get_post_timestamp( $post ) ) / DAY_IN_SECONDS;
	$display = $base + (int) min( $age_days * 12, 3000 );
	return (string) number_format_i18n( $display );
}

function jagawarta_format_date( $post = null, string $format = '' ): string {
	if ( empty( $format ) ) {
		$format = get_option( 'date_format' );
	}
	return get_the_date( $format, $post );
}

function jagawarta_fallback_menu(): void {
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'jagawarta' ) . '</a></li>';
}

function jagawarta_default_image_url(): string {
	$path = JAGAWARTA_DIR . '/assets/images/jwid_default.png';
	if ( ! file_exists( $path ) ) {
		return '';
	}
	return JAGAWARTA_URI . '/assets/images/jwid_default.png';
}

function jagawarta_image_onerror_attr(): string {
	$fallback = jagawarta_default_image_url();
	if ( empty( $fallback ) ) {
		return '';
	}
	return 'this.onerror=null;this.src=\'' . esc_url( $fallback ) . '\';this.srcset=\'\';';
}

function jagawarta_image_fallback_attr(): string {
	$fallback = jagawarta_default_image_url();
	if ( empty( $fallback ) ) {
		return '';
	}
	return 'data-jw-fallback="' . esc_url( $fallback ) . '"';
}

function jagawarta_get_post_display_image( $post = null ): array {
	$post = get_post( $post );
	if ( ! $post ) {
		return array( 'attachment_id' => 0, 'url' => '' );
	}
	if ( has_post_thumbnail( $post ) ) {
		$id = (int) get_post_thumbnail_id( $post );
		$url = wp_get_attachment_image_url( $id, 'large' );
		if ( $url ) {
			return array( 'attachment_id' => $id, 'url' => $url );
		}
	}
	$content = $post->post_content;
	if ( ! empty( $content ) && preg_match( '/<img[^>]+src=(["\'])([^"\']+)\1/', $content, $m ) ) {
		$url = $m[2];
		if ( 0 === strpos( $url, '//' ) ) {
			$url = 'https:' . $url;
		} elseif ( '/' === substr( $url, 0, 1 ) ) {
			$url = home_url( $url );
		}
		$id = (int) attachment_url_to_postid( $url );
		return array( 'attachment_id' => $id, 'url' => $url );
	}
	$default = jagawarta_default_image_url();
	return array( 'attachment_id' => 0, 'url' => $default );
}

function jagawarta_lcp_preload_url( $post = null ): ?string {
	$post = get_post( $post );
	if ( ! $post ) {
		return null;
	}
	$img = jagawarta_get_post_display_image( $post );
	if ( empty( $img['url'] ) ) {
		return null;
	}
	if ( $img['attachment_id'] ) {
		$url = wp_get_attachment_image_url( $img['attachment_id'], 'large' );
		return $url ?: null;
	}
	return $img['url'];
}

function jagawarta_the_post_display_image( $post = null, array $args = array() ): void {
	$img = jagawarta_get_post_display_image( $post );
	if ( empty( $img['url'] ) ) {
		return;
	}
	$args = wp_parse_args( $args, array( 'lcp' => false, 'class' => '' ) );
	if ( $img['attachment_id'] ) {
		jagawarta_the_image( $img['attachment_id'], $args );
		return;
	}
	$loading = ! empty( $args['lcp'] ) ? 'eager' : 'lazy';
	$class   = trim( 'w-full h-auto ' . (string) $args['class'] );
	$onerror = jagawarta_image_onerror_attr();
	$fallback = jagawarta_image_fallback_attr();

	$html = '<img src="' . esc_url( $img['url'] ) . '" alt="" loading="' . esc_attr( $loading ) . '" class="' . esc_attr( $class ) . '"' . ( $onerror ? ' onerror="' . esc_attr( $onerror ) . '"' : '' ) . ( $fallback ? ' ' . $fallback : '' ) . ' />';

	echo apply_filters( 'jagawarta_external_image_tag', $html, $img, $args );
}

function jagawarta_the_image( int $attachment_id, array $args = array() ): void {
	$args  = wp_parse_args( $args, array(
		'lcp'   => false,
		'sizes' => '(max-width: 768px) 100vw, 768px',
		'class' => '',
		'size'  => 'large',
	) );
	$src   = wp_get_attachment_image_url( $attachment_id, $args['size'] );
	$srcset = wp_get_attachment_image_srcset( $attachment_id, $args['size'] );

	if ( ! $src ) {
		return;
	}

	$alt    = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
	$loading = ! empty( $args['lcp'] ) ? 'eager' : 'lazy';
	$fetchpriority = ! empty( $args['lcp'] ) ? 'high' : 'auto';
	$class  = trim( 'w-full h-auto ' . (string) $args['class'] );
	$onerror = jagawarta_image_onerror_attr();
	$fallback = jagawarta_image_fallback_attr();
	?>
	<img
		src="<?php echo esc_url( $src ); ?>"
		<?php if ( $srcset ) : ?>srcset="<?php echo esc_attr( $srcset ); ?>"<?php endif; ?>
		sizes="<?php echo esc_attr( (string) $args['sizes'] ); ?>"
		alt="<?php echo esc_attr( $alt ?: '' ); ?>"
		loading="<?php echo esc_attr( $loading ); ?>"
		fetchpriority="<?php echo esc_attr( $fetchpriority ); ?>"
		class="<?php echo esc_attr( $class ); ?>"
		<?php if ( $onerror ) : ?>onerror="<?php echo esc_attr( $onerror ); ?>"<?php endif; ?>
		<?php if ( $fallback ) : ?> <?php echo $fallback; ?><?php endif; ?>
	/>
	<?php
}

/**
 * Get category color scheme based on category slug
 * Returns MD3 color role classes for background and text
 *
 * @param WP_Term|null $category Category term object
 * @return array Array with 'bg' and 'text' classes
 */
function jagawarta_get_category_colors( $category = null ): array {
	if ( ! $category || ! isset( $category->slug ) ) {
		return array(
			'bg'   => 'bg-primary-container',
			'text' => 'text-on-primary-container',
		);
	}
	
	// Define category color mapping using MD3 roles
	$color_map = array(
		'nasional'     => array( 'bg' => 'bg-primary-container', 'text' => 'text-on-primary-container' ),
		'daerah'       => array( 'bg' => 'bg-secondary-container', 'text' => 'text-on-secondary-container' ),
		'politik'      => array( 'bg' => 'bg-tertiary-container', 'text' => 'text-on-tertiary-container' ),
		'ekonomi'      => array( 'bg' => 'bg-primary-container', 'text' => 'text-on-primary-container' ),
		'olahraga'     => array( 'bg' => 'bg-secondary-container', 'text' => 'text-on-secondary-container' ),
		'teknologi'    => array( 'bg' => 'bg-tertiary-container', 'text' => 'text-on-tertiary-container' ),
		'gaya-hidup'   => array( 'bg' => 'bg-primary-container', 'text' => 'text-on-primary-container' ),
		'breaking'     => array( 'bg' => 'bg-error-container', 'text' => 'text-on-error-container' ),
	);
	
	$slug = $category->slug;
	
	// Return mapped colors or default to rotating based on term ID
	if ( isset( $color_map[ $slug ] ) ) {
		return $color_map[ $slug ];
	}
	
	// Fallback: rotate through color roles based on ID
	$colors = array(
		array( 'bg' => 'bg-primary-container', 'text' => 'text-on-primary-container' ),
		array( 'bg' => 'bg-secondary-container', 'text' => 'text-on-secondary-container' ),
		array( 'bg' => 'bg-tertiary-container', 'text' => 'text-on-tertiary-container' ),
	);
	
	$index = $category->term_id % count( $colors );
	return $colors[ $index ];
}

/**
 * Output a category chip/badge with color coding
 *
 * @param WP_Term|null $category Category term object
 * @param array $args Optional arguments (size, show_link)
 */
function jagawarta_the_category_chip( $category = null, array $args = array() ): void {
	if ( ! $category ) {
		return;
	}
	
	$args = wp_parse_args( $args, array(
		'size'      => 'medium', // 'small', 'medium', 'large'
		'show_link' => true,
	) );
	
	$colors = jagawarta_get_category_colors( $category );
	
	$size_classes = array(
		'small'  => 'px-2 py-0.5 text-label-small rounded-md',
		'medium' => 'px-3 py-1 text-label-medium rounded-lg',
		'large'  => 'px-4 py-1.5 text-label-large rounded-xl',
	);
	
	$size_class = isset( $size_classes[ $args['size'] ] ) ? $size_classes[ $args['size'] ] : $size_classes['medium'];
	
	$chip_classes = sprintf(
		'inline-flex items-center gap-1 %s %s %s font-medium uppercase tracking-wide transition-all duration-short ease-standard',
		$colors['bg'],
		$colors['text'],
		$size_class
	);
	
	if ( $args['show_link'] ) {
		printf(
			'<a href="%s" class="%s hover:shadow-elevation-1">%s</a>',
			esc_url( get_category_link( $category->term_id ) ),
			esc_attr( $chip_classes ),
			esc_html( $category->name )
		);
	} else {
		printf(
			'<span class="%s">%s</span>',
			esc_attr( $chip_classes ),
			esc_html( $category->name )
		);
	}
}

function jagawarta_svg_arrow_right( string $class = 'w-5 h-5 fill-current' ): void {
	echo '<svg viewBox="0 0 32 32" class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><polygon points="16,0 13.2,2.8 24.3,14 0,14 0,18 24.3,18 13.2,29.2 16,32 32,16"/></svg>';
}

function jagawarta_clean_archive_title( $title ) {
	if ( is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	} elseif ( is_author() ) {
		$title = get_the_author();
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'jagawarta_clean_archive_title' );
