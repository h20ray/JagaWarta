<?php
/**
 * MD3 HCT Palette Generator
 * 
 * Generates Material Design 3 tonal palettes from seed colors using the HCT
 * (Hue, Chroma, Tone) color space for perceptually uniform tones.
 * 
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Convert hex color to HCT color space.
 * 
 * @param string $hex Hex color (e.g., '#1e3a5f').
 * @return array HCT values ['h' => 0-360, 'c' => 0-150, 't' => 0-100].
 */
function jagawarta_hex_to_hct( string $hex ): array {
	$hex = ltrim( $hex, '#' );
	$r = hexdec( substr( $hex, 0, 2 ) ) / 255.0;
	$g = hexdec( substr( $hex, 2, 2 ) ) / 255.0;
	$b = hexdec( substr( $hex, 4, 2 ) ) / 255.0;

	// sRGB to linear RGB.
	$linearize = function( $c ) {
		return $c <= 0.04045 ? $c / 12.92 : pow( ( $c + 0.055 ) / 1.055, 2.4 );
	};
	$r = $linearize( $r );
	$g = $linearize( $g );
	$b = $linearize( $b );

	// Linear RGB to XYZ (D65 illuminant).
	$x = $r * 0.4124564 + $g * 0.3575761 + $b * 0.1804375;
	$y = $r * 0.2126729 + $g * 0.7151522 + $b * 0.0721750;
	$z = $r * 0.0193339 + $g * 0.1191920 + $b * 0.9503041;

	// XYZ to Lab.
	$xn = 0.95047;
	$yn = 1.00000;
	$zn = 1.08883;
	
	$fx = $x / $xn > 0.008856 ? pow( $x / $xn, 1 / 3 ) : ( 7.787 * $x / $xn ) + ( 16 / 116 );
	$fy = $y / $yn > 0.008856 ? pow( $y / $yn, 1 / 3 ) : ( 7.787 * $y / $yn ) + ( 16 / 116 );
	$fz = $z / $zn > 0.008856 ? pow( $z / $zn, 1 / 3 ) : ( 7.787 * $z / $zn ) + ( 16 / 116 );
	
	$l = 116 * $fy - 16;
	$a = 500 * ( $fx - $fy );
	$b_lab = 200 * ( $fy - $fz );

	// Lab to LCh (cylindrical Lab).
	$c = sqrt( $a * $a + $b_lab * $b_lab );
	$h = atan2( $b_lab, $a ) * 180 / M_PI;
	if ( $h < 0 ) {
		$h += 360;
	}

	// Map L and C to HCT Tone and Chroma.
	$tone = $l; // L is essentially tone.
	$chroma = $c * 1.5; // Approximate chroma scaling.

	// MD3 Expressive: constrain chroma for usability and accessibility.
	// Max chroma ~48 for vivid but accessible colors.
	// This prevents overly saturated colors (e.g., pure yellow #FFFF00)
	// from bypassing accessibility guidelines.
	$max_chroma = 48;
	$chroma = max( 0, min( $max_chroma, $chroma ) );

	return array(
		'h' => $h,
		'c' => $chroma,
		't' => max( 0, min( 100, $tone ) ),
	);
}

/**
 * Convert HCT color to hex.
 * 
 * @param array $hct HCT values ['h', 'c', 't'].
 * @return string Hex color.
 */
function jagawarta_hct_to_hex( array $hct ): string {
	$h = $hct['h'];
	$c = $hct['c'] / 1.5; // Reverse chroma scaling.
	$l = $hct['t'];

	// LCh to Lab.
	$a = $c * cos( $h * M_PI / 180 );
	$b_lab = $c * sin( $h * M_PI / 180 );

	// Lab to XYZ.
	$fy = ( $l + 16 ) / 116;
	$fx = $a / 500 + $fy;
	$fz = $fy - $b_lab / 200;

	$xn = 0.95047;
	$yn = 1.00000;
	$zn = 1.08883;

	$xr = pow( $fx, 3 ) > 0.008856 ? pow( $fx, 3 ) : ( $fx - 16 / 116 ) / 7.787;
	$yr = pow( $fy, 3 ) > 0.008856 ? pow( $fy, 3 ) : ( $fy - 16 / 116 ) / 7.787;
	$zr = pow( $fz, 3 ) > 0.008856 ? pow( $fz, 3 ) : ( $fz - 16 / 116 ) / 7.787;

	$x = $xr * $xn;
	$y = $yr * $yn;
	$z = $zr * $zn;

	// XYZ to linear RGB.
	$r =  3.2404542 * $x - 1.5371385 * $y - 0.4985314 * $z;
	$g = -0.9692660 * $x + 1.8760108 * $y + 0.0415560 * $z;
	$b =  0.0556434 * $x - 0.2040259 * $y + 1.0572252 * $z;

	// Linear RGB to sRGB.
	$gamma = function( $c ) {
		return $c <= 0.0031308 ? 12.92 * $c : 1.055 * pow( $c, 1 / 2.4 ) - 0.055;
	};
	$r = $gamma( $r );
	$g = $gamma( $g );
	$b = $gamma( $b );

	// Clamp and convert to hex.
	$r = max( 0, min( 1, $r ) );
	$g = max( 0, min( 1, $g ) );
	$b = max( 0, min( 1, $b ) );

	return sprintf( '#%02x%02x%02x', (int) round( $r * 255 ), (int) round( $g * 255 ), (int) round( $b * 255 ) );
}

/**
 * Generate tonal palette from seed color.
 * 
 * @param string $seed_hex Seed color hex.
 * @param array  $tones    Tone levels to generate (default: MD3 standard tones).
 * @return array Associative array of tone => hex.
 */
function jagawarta_generate_tonal_palette( string $seed_hex, array $tones = null ): array {
	if ( null === $tones ) {
		// MD3 standard tones.
		$tones = array( 0, 4, 10, 12, 17, 20, 22, 24, 30, 40, 50, 60, 80, 90, 92, 94, 95, 96, 99, 100 );
	}

	$hct = jagawarta_hex_to_hct( $seed_hex );
	$palette = array();

	foreach ( $tones as $tone ) {
		$palette[ (string) $tone ] = jagawarta_hct_to_hex( array(
			'h' => $hct['h'],
			'c' => $hct['c'],
			't' => $tone,
		) );
	}

	return $palette;
}

/**
 * Generate derived neutral palette from primary seed.
 * 
 * @param string $primary_hex Primary seed color.
 * @return array Neutral palette tones.
 */
function jagawarta_generate_neutral_palette( string $primary_hex ): array {
	$hct = jagawarta_hex_to_hct( $primary_hex );
	
	// Neutral uses same hue but very low chroma (~4).
	$neutral_hct = array(
		'h' => $hct['h'],
		'c' => 4,
		't' => 50, // Base tone, will be overridden per tone.
	);

	$tones = array( 0, 4, 10, 12, 17, 20, 22, 24, 30, 40, 50, 60, 80, 90, 92, 94, 95, 96, 99, 100 );
	$palette = array();

	foreach ( $tones as $tone ) {
		$neutral_hct['t'] = $tone;
		$palette[ (string) $tone ] = jagawarta_hct_to_hex( $neutral_hct );
	}

	return $palette;
}

/**
 * Generate analogous secondary palette from primary.
 * 
 * @param string $primary_hex Primary seed color.
 * @return array Secondary palette tones.
 */
function jagawarta_generate_secondary_palette( string $primary_hex ): array {
	$hct = jagawarta_hex_to_hct( $primary_hex );
	
	// Secondary: analogous color (hue -30°), reduced chroma.
	$secondary_hct = array(
		'h' => fmod( $hct['h'] - 30 + 360, 360 ),
		'c' => max( 16, $hct['c'] * 0.4 ), // Lower chroma for subtlety.
		't' => 50,
	);

	$tones = array( 10, 20, 30, 40, 50, 60, 80, 90, 100 );
	$palette = array();

	foreach ( $tones as $tone ) {
		$secondary_hct['t'] = $tone;
		$palette[ (string) $tone ] = jagawarta_hct_to_hex( $secondary_hct );
	}

	return $palette;
}

/**
 * Generate complementary tertiary palette from primary.
 * 
 * @param string $primary_hex Primary seed color.
 * @return array Tertiary palette tones.
 */
function jagawarta_generate_tertiary_palette( string $primary_hex ): array {
	$hct = jagawarta_hex_to_hct( $primary_hex );
	
	// Tertiary: complementary accent (hue +180°).
	$tertiary_hct = array(
		'h' => fmod( $hct['h'] + 180, 360 ),
		'c' => max( 24, $hct['c'] * 0.5 ),
		't' => 50,
	);

	$tones = array( 10, 20, 30, 40, 50, 60, 80, 90, 100 );
	$palette = array();

	foreach ( $tones as $tone ) {
		$tertiary_hct['t'] = $tone;
		$palette[ (string) $tone ] = jagawarta_hct_to_hex( $tertiary_hct );
	}

	return $palette;
}

/**
 * Generate standard MD3 error palette.
 * 
 * @return array Error palette tones.
 */
function jagawarta_generate_error_palette(): array {
	// Standard MD3 error seed: #ba1a1a.
	return jagawarta_generate_tonal_palette( '#ba1a1a', array( 10, 30, 40, 80, 90, 99 ) );
}

/**
 * Generate full MD3 palette from seeds.
 * 
 * @param array $seeds Seed colors ['primary', 'neutral', 'secondary', 'tertiary', 'error'].
 * @return array Complete palette structure for light and dark modes.
 */
function jagawarta_generate_full_palette( array $seeds ): array {
	$primary_seed = $seeds['primary'] ?? '#1e3a5f';
	
	// Generate palettes.
	$primary = jagawarta_generate_tonal_palette( $primary_seed );
	$neutral = ! empty( $seeds['neutral'] )
		? jagawarta_generate_tonal_palette( $seeds['neutral'] )
		: jagawarta_generate_neutral_palette( $primary_seed );
	$secondary = ! empty( $seeds['secondary'] )
		? jagawarta_generate_tonal_palette( $seeds['secondary'] )
		: jagawarta_generate_secondary_palette( $primary_seed );
	$tertiary = ! empty( $seeds['tertiary'] )
		? jagawarta_generate_tonal_palette( $seeds['tertiary'] )
		: jagawarta_generate_tertiary_palette( $primary_seed );
	$error = ! empty( $seeds['error'] )
		? jagawarta_generate_tonal_palette( $seeds['error'], array( 10, 30, 40, 80, 90, 99 ) )
		: jagawarta_generate_error_palette();

	return array(
		'light' => array(
			'primary' => $primary,
			'neutral' => $neutral,
			'secondary' => $secondary,
			'tertiary' => $tertiary,
			'error' => $error,
		),
		'dark' => array(
			'primary' => $primary,
			'neutral' => $neutral,
			'secondary' => $secondary,
			'tertiary' => $tertiary,
			'error' => $error,
		),
	);
}
