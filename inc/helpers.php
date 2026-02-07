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

function jagawarta_get_post_display_image( $post = null ): array {
	$post = get_post( $post );
	if ( ! $post ) {
		return array( 'attachment_id' => 0, 'url' => '' );
	}
	if ( has_post_thumbnail( $post ) ) {
		$id = (int) get_post_thumbnail_id( $post );
		$url = wp_get_attachment_image_url( $id, 'large' );
		return array( 'attachment_id' => $id, 'url' => $url ?: '' );
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

function jagawarta_get_modern_image_sources( int $attachment_id, string $size = 'large' ): array {
	$src    = wp_get_attachment_image_url( $attachment_id, $size );
	$srcset = wp_get_attachment_image_srcset( $attachment_id, $size );

	if ( ! $src ) {
		$sources = array(
			'default_src'     => '',
			'default_srcset'  => '',
			'avif_srcset'     => '',
			'webp_srcset'     => '',
		);
		return (array) apply_filters( 'jagawarta_modern_image_sources', $sources, $attachment_id, $size );
	}

	$sources = array(
		'default_src'     => $src,
		'default_srcset'  => $srcset ?: '',
		'avif_srcset'     => '',
		'webp_srcset'     => '',
	);

	$upload = wp_get_upload_dir();

	if (
		! empty( $upload['baseurl'] ) &&
		! empty( $upload['basedir'] ) &&
		0 === strpos( $src, $upload['baseurl'] )
	) {
		$relative = substr( $src, strlen( $upload['baseurl'] ) );
		$path     = $upload['basedir'] . $relative;

		if ( preg_match( '/\.(jpe?g|png)$/i', $path ) ) {
			$avif_path = preg_replace( '/\.(jpe?g|png)$/i', '.avif', $path );
			$webp_path = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $path );

			if ( $avif_path && file_exists( $avif_path ) ) {
				if ( $srcset ) {
					$sources['avif_srcset'] = preg_replace( '/\.(jpe?g|png)(\s+\d+w)/i', '.avif$2', $srcset );
				} else {
					$avif_url               = preg_replace( '/\.(jpe?g|png)$/i', '.avif', $src );
					$sources['avif_srcset'] = $avif_url ? $avif_url . ' 1x' : '';
				}
			}

			if ( $webp_path && file_exists( $webp_path ) ) {
				if ( $srcset ) {
					$sources['webp_srcset'] = preg_replace( '/\.(jpe?g|png)(\s+\d+w)/i', '.webp$2', $srcset );
				} else {
					$webp_url               = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $src );
					$sources['webp_srcset'] = $webp_url ? $webp_url . ' 1x' : '';
				}
			}
		}
	}

	$sources = (array) apply_filters( 'jagawarta_modern_image_sources', $sources, $attachment_id, $size );

	return $sources;
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

	$html = '<img src="' . esc_url( $img['url'] ) . '" alt="" loading="' . esc_attr( $loading ) . '" class="' . esc_attr( $class ) . '" />';

	/**
	 * Filter the fallback <img> tag for non-attachment display images.
	 *
	 * This is used when `jagawarta_get_post_display_image()` resolves to an
	 * external URL or a non-library image, where the theme cannot safely guess
	 * AVIF/WebP variants. Consumers can replace the HTML (e.g. with <picture>)
	 * or add attributes.
	 *
	 * @param string $html Final HTML to output for the image.
	 * @param array  $img  Array from jagawarta_get_post_display_image().
	 * @param array  $args Arguments passed to jagawarta_the_post_display_image().
	 */
	echo apply_filters( 'jagawarta_external_image_tag', $html, $img, $args );
}

/**
 * Output a responsive image element for a library attachment.
 *
 * Uses jagawarta_get_modern_image_sources() to prefer AVIF/WebP via <picture>
 * when sidecar files exist, falling back to a plain <img> with srcset/sizes.
 */
function jagawarta_the_image( int $attachment_id, array $args = array() ): void {
	$args = wp_parse_args( $args, array(
		'lcp'   => false,
		'sizes' => '(max-width: 768px) 100vw, 768px',
		'class' => '',
		'size'  => 'large',
	) );
	$lcp   = (bool) $args['lcp'];
	$sizes = (string) $args['sizes'];
	$class = (string) $args['class'];
	$size  = (string) $args['size'];

	$sources = jagawarta_get_modern_image_sources( $attachment_id, $size );

	if ( empty( $sources['default_src'] ) ) {
		return;
	}

	$src    = (string) $sources['default_src'];
	$srcset = (string) $sources['default_srcset'];
	$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
	$loading = $lcp ? 'eager' : 'lazy';
	$fetchpriority = $lcp ? 'high' : 'auto';
	$img_class = trim( 'w-full h-auto ' . $class );

	$has_avif = ! empty( $sources['avif_srcset'] );
	$has_webp = ! empty( $sources['webp_srcset'] );

	if ( ! $has_avif && ! $has_webp ) {
		?>
		<img
			src="<?php echo esc_url( $src ); ?>"
			<?php if ( $srcset ) : ?>srcset="<?php echo esc_attr( $srcset ); ?>"<?php endif; ?>
			sizes="<?php echo esc_attr( $sizes ); ?>"
			alt="<?php echo esc_attr( $alt ?: '' ); ?>"
			loading="<?php echo esc_attr( $loading ); ?>"
			fetchpriority="<?php echo esc_attr( $fetchpriority ); ?>"
			class="<?php echo esc_attr( $img_class ); ?>"
		/>
		<?php
		return;
	}

	?>
	<picture>
		<?php if ( $has_avif ) : ?>
			<source
				type="image/avif"
				srcset="<?php echo esc_attr( (string) $sources['avif_srcset'] ); ?>"
				sizes="<?php echo esc_attr( $sizes ); ?>"
			/>
		<?php endif; ?>
		<?php if ( $has_webp ) : ?>
			<source
				type="image/webp"
				srcset="<?php echo esc_attr( (string) $sources['webp_srcset'] ); ?>"
				sizes="<?php echo esc_attr( $sizes ); ?>"
			/>
		<?php endif; ?>
		<img
			src="<?php echo esc_url( $src ); ?>"
			<?php if ( $srcset ) : ?>srcset="<?php echo esc_attr( $srcset ); ?>"<?php endif; ?>
			sizes="<?php echo esc_attr( $sizes ); ?>"
			alt="<?php echo esc_attr( $alt ?: '' ); ?>"
			loading="<?php echo esc_attr( $loading ); ?>"
			fetchpriority="<?php echo esc_attr( $fetchpriority ); ?>"
			class="<?php echo esc_attr( $img_class ); ?>"
		/>
	</picture>
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
