<?php
/**
 * Category chip color: term meta, admin UI, hex→token conversion, fallback.
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const JAGAWARTA_CHIP_COLOR_META = 'jagawarta_chip_color';

add_action( 'category_add_form_fields', 'jagawarta_category_chip_color_field_add' );
add_action( 'category_edit_form_fields', 'jagawarta_category_chip_color_field_edit' );
add_action( 'created_category', 'jagawarta_category_chip_color_save', 10, 2 );
add_action( 'edited_category', 'jagawarta_category_chip_color_save', 10, 2 );
add_action( 'wp_head', 'jagawarta_category_chip_css_vars', 5 );

/**
 * 15 preset hex colors for chip swatches (base hue; front-end converts to container pair).
 * First 4 align with legacy presets for backward-compat display in edit form.
 *
 * @return array<int, string> Index => hex.
 */
function jagawarta_get_chip_color_swatches(): array {
	return array(
		'#1e3a5f', '#5c5c6b', '#7d5a00', '#b3261e',
		'#0d5c42', '#006a6a', '#284893', '#6e3678',
		'#8d4e00', '#a34318', '#2d5a27', '#004a77',
		'#4a4458', '#6b2d5c', '#005c5c',
	);
}

function jagawarta_chip_color_swatch_container_hex( string $hex ): string {
	if ( ! function_exists( 'jagawarta_hex_to_container_pair' ) ) {
		return $hex;
	}
	$pair = jagawarta_hex_to_container_pair( $hex );
	return $pair['container'];
}

function jagawarta_category_chip_color_field_add(): void {
	$swatches = jagawarta_get_chip_color_swatches();
	$current_hex = '';
	?>
	<div class="form-field jagawarta-chip-color-field">
		<label class="jagawarta-chip-color-label"><?php esc_html_e( 'Chip color', 'jagawarta' ); ?></label>
		<div class="jagawarta-chip-color-swatches" role="group" aria-label="<?php esc_attr_e( 'Choose a color for the category chip', 'jagawarta' ); ?>">
			<input type="radio" name="jagawarta_chip_color" id="jagawarta_chip_color_default" value="" class="jagawarta-chip-radio" checked />
			<label for="jagawarta_chip_color_default" class="jagawarta-chip-swatch jagawarta-chip-swatch-default" title="<?php esc_attr_e( 'Default (auto)', 'jagawarta' ); ?>">
				<span class="jagawarta-chip-swatch-circle" aria-hidden="true">A</span>
			</label>
			<?php foreach ( $swatches as $i => $hex ) :
				$container_hex = jagawarta_chip_color_swatch_container_hex( $hex );
				$id = 'jagawarta_chip_swatch_' . $i;
			?>
				<input type="radio" name="jagawarta_chip_color" id="<?php echo esc_attr( $id ); ?>" value="hex" class="jagawarta-chip-radio jagawarta-chip-radio-hex" data-hex="<?php echo esc_attr( $hex ); ?>" />
				<label for="<?php echo esc_attr( $id ); ?>" class="jagawarta-chip-swatch" title="<?php echo esc_attr( $hex ); ?>">
					<span class="jagawarta-chip-swatch-circle" style="background-color:<?php echo esc_attr( $container_hex ); ?>"></span>
				</label>
			<?php endforeach; ?>
			<input type="radio" name="jagawarta_chip_color" id="jagawarta_chip_color_custom" value="hex" class="jagawarta-chip-radio jagawarta-chip-radio-custom" data-hex="" />
			<label for="jagawarta_chip_color_custom" class="jagawarta-chip-swatch jagawarta-chip-swatch-custom" title="<?php esc_attr_e( 'Custom', 'jagawarta' ); ?>">
				<span class="jagawarta-chip-swatch-circle jagawarta-chip-swatch-plus" aria-hidden="true">+</span>
			</label>
		</div>
		<input type="hidden" name="jagawarta_chip_color_hex_text" id="jagawarta_chip_color_hex_text" value="" />
		<div class="jagawarta-chip-color-custom jagawarta-chip-color-picker-wrap" style="display:none;">
			<label for="jagawarta_chip_color_hex" class="screen-reader-text"><?php esc_html_e( 'Custom color', 'jagawarta' ); ?></label>
			<input type="color" id="jagawarta_chip_color_hex" name="jagawarta_chip_color_hex" value="#1e3a5f" />
			<input type="text" id="jagawarta_chip_color_hex_display" value="#1e3a5f" class="small-text" maxlength="7" readonly aria-label="<?php esc_attr_e( 'Hex value', 'jagawarta' ); ?>" />
		</div>
		<p class="description"><?php esc_html_e( 'Color of the category chip in post content. Default (auto) assigns a unique color per category.', 'jagawarta' ); ?></p>
	</div>
	<?php
	jagawarta_category_chip_color_admin_assets();
	jagawarta_category_chip_color_script( $swatches, $current_hex );
}

function jagawarta_category_chip_color_field_edit( WP_Term $term ): void {
	$value    = get_term_meta( $term->term_id, JAGAWARTA_CHIP_COLOR_META, true );
	$hex_meta = (string) get_term_meta( $term->term_id, 'jagawarta_chip_color_hex', true );
	$hex_meta = sanitize_hex_color( $hex_meta ) ?: '';
	$swatches = jagawarta_get_chip_color_swatches();
	$preset_to_hex = array( 'primary' => $swatches[0], 'secondary' => $swatches[1], 'tertiary' => $swatches[2], 'error' => $swatches[3] );
	$current_hex = '';
	if ( $value === 'hex' && $hex_meta ) {
		$current_hex = strtolower( $hex_meta );
	} elseif ( isset( $preset_to_hex[ $value ] ) ) {
		$current_hex = strtolower( $preset_to_hex[ $value ] );
	}
	$effective_hex = ( $value === 'hex' && $hex_meta ) ? $hex_meta : ( isset( $preset_to_hex[ $value ] ) ? $preset_to_hex[ $value ] : '#1e3a5f' );
	$is_custom_hex = $value === 'hex' && $hex_meta && array_search( $current_hex, array_map( 'strtolower', $swatches ), true ) === false;
	?>
	<tr class="form-field jagawarta-chip-color-field">
		<th scope="row"><span class="jagawarta-chip-color-label"><?php esc_html_e( 'Chip color', 'jagawarta' ); ?></span></th>
		<td>
			<div class="jagawarta-chip-color-swatches" role="group" aria-label="<?php esc_attr_e( 'Choose a color for the category chip', 'jagawarta' ); ?>">
				<input type="radio" name="jagawarta_chip_color" id="jagawarta_chip_color_default" value="" class="jagawarta-chip-radio" <?php checked( $value === '' ); ?> />
				<label for="jagawarta_chip_color_default" class="jagawarta-chip-swatch jagawarta-chip-swatch-default" title="<?php esc_attr_e( 'Default (auto)', 'jagawarta' ); ?>">
					<span class="jagawarta-chip-swatch-circle" aria-hidden="true">A</span>
				</label>
				<?php foreach ( $swatches as $i => $hex ) :
					$container_hex = jagawarta_chip_color_swatch_container_hex( $hex );
					$id = 'jagawarta_chip_swatch_' . $i;
					$checked = ( $value === 'hex' && $current_hex === strtolower( $hex ) ) || ( isset( $preset_to_hex[ $value ] ) && $preset_to_hex[ $value ] === $hex );
				?>
					<input type="radio" name="jagawarta_chip_color" id="<?php echo esc_attr( $id ); ?>" value="hex" class="jagawarta-chip-radio jagawarta-chip-radio-hex" data-hex="<?php echo esc_attr( $hex ); ?>" <?php checked( $checked ); ?> />
					<label for="<?php echo esc_attr( $id ); ?>" class="jagawarta-chip-swatch" title="<?php echo esc_attr( $hex ); ?>">
						<span class="jagawarta-chip-swatch-circle" style="background-color:<?php echo esc_attr( $container_hex ); ?>"></span>
					</label>
				<?php endforeach; ?>
				<input type="radio" name="jagawarta_chip_color" id="jagawarta_chip_color_custom" value="hex" class="jagawarta-chip-radio jagawarta-chip-radio-custom" data-hex="" <?php checked( $is_custom_hex ); ?> />
				<label for="jagawarta_chip_color_custom" class="jagawarta-chip-swatch jagawarta-chip-swatch-custom" title="<?php esc_attr_e( 'Custom', 'jagawarta' ); ?>">
					<span class="jagawarta-chip-swatch-circle jagawarta-chip-swatch-plus" aria-hidden="true">+</span>
				</label>
			</div>
			<input type="hidden" name="jagawarta_chip_color_hex_text" id="jagawarta_chip_color_hex_text" value="<?php echo esc_attr( $current_hex ?: $effective_hex ); ?>" />
			<div class="jagawarta-chip-color-custom jagawarta-chip-color-picker-wrap" style="<?php echo $is_custom_hex ? '' : 'display:none;'; ?>">
				<label for="jagawarta_chip_color_hex" class="screen-reader-text"><?php esc_html_e( 'Custom color', 'jagawarta' ); ?></label>
				<input type="color" id="jagawarta_chip_color_hex" name="jagawarta_chip_color_hex" value="<?php echo esc_attr( $effective_hex ); ?>" />
				<input type="text" id="jagawarta_chip_color_hex_display" value="<?php echo esc_attr( $effective_hex ); ?>" class="small-text" maxlength="7" readonly aria-label="<?php esc_attr_e( 'Hex value', 'jagawarta' ); ?>" />
			</div>
			<p class="description"><?php esc_html_e( 'Color of the category chip in post content. Default (auto) assigns a unique color per category.', 'jagawarta' ); ?></p>
		</td>
	</tr>
	<?php
	jagawarta_category_chip_color_admin_assets();
	jagawarta_category_chip_color_script( $swatches, $current_hex ?: $effective_hex );
}

function jagawarta_category_chip_color_admin_assets(): void {
	?>
	<style>
	.jagawarta-chip-color-swatches { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; margin: 6px 0; width: max-content; max-width: 100%; }
	.jagawarta-chip-radio { position: absolute; width: 1px; height: 1px; margin: -1px; padding: 0; overflow: hidden; clip: rect(0,0,0,0); border: 0; }
	.jagawarta-chip-swatch { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; min-width: 32px; min-height: 32px; flex: 0 0 32px; cursor: pointer; padding: 2px; border-radius: 50%; border: 2px solid transparent; transition: border-color .15s ease, box-shadow .15s ease; box-sizing: border-box; }
	.jagawarta-chip-swatch:hover { border-color: #8c8c8c; }
	.jagawarta-chip-radio:focus + .jagawarta-chip-swatch { outline: 2px solid #2271b1; outline-offset: 2px; }
	.jagawarta-chip-radio:checked + .jagawarta-chip-swatch { border-color: #1d2327; box-shadow: 0 0 0 1px #1d2327; }
	.jagawarta-chip-swatch-circle { width: 28px; height: 28px; min-width: 28px; min-height: 28px; flex-shrink: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; color: #1d2327; background: #f0f0f1; box-sizing: border-box; }
	.jagawarta-chip-swatch-plus { background: #f0f0f1 !important; color: #50575e; font-size: 16px; line-height: 1; }
	.jagawarta-chip-color-picker-wrap { margin-top: 12px; display: flex; align-items: center; gap: 10px; }
	.jagawarta-chip-color-picker-wrap input[type="color"] { width: 40px; height: 40px; padding: 2px; border: 1px solid #8c8f94; border-radius: 8px; cursor: pointer; }
	.jagawarta-chip-color-picker-wrap input[type="text"] { width: 82px; }
	</style>
	<?php
}

function jagawarta_category_chip_color_script( array $swatches, string $current_hex ): void {
	$swatches_js = array_map( 'strtolower', $swatches );
	?>
	<script>
	(function(){
		var swatches = <?php echo wp_json_encode( $swatches_js ); ?>;
		var currentHex = <?php echo wp_json_encode( strtolower( $current_hex ) ); ?>;
		var radios = document.querySelectorAll('.jagawarta-chip-radio');
		var customWrap = document.querySelector('.jagawarta-chip-color-picker-wrap');
		var hexHidden = document.getElementById('jagawarta_chip_color_hex_text');
		var colorInput = document.getElementById('jagawarta_chip_color_hex');
		var hexDisplay = document.getElementById('jagawarta_chip_color_hex_display');
		function setHex(val) {
			if (!hexHidden) return;
			hexHidden.value = val || '';
			if (hexDisplay) hexDisplay.value = val || '';
			if (colorInput && val) colorInput.value = val;
		}
		function getSelectedHex() {
			var r = document.querySelector('.jagawarta-chip-radio:checked');
			if (!r) return '';
			if (r.id === 'jagawarta_chip_color_default') return '';
			if (r.classList.contains('jagawarta-chip-radio-hex') && r.dataset.hex) return r.dataset.hex;
			if (r.id === 'jagawarta_chip_color_custom' && colorInput) return colorInput.value;
			return '';
		}
		function toggleCustom() {
			var r = document.getElementById('jagawarta_chip_color_custom');
			if (!customWrap || !r) return;
			customWrap.style.display = r.checked ? '' : 'none';
		}
		if (radios.length) {
			radios.forEach(function(radio){
				radio.addEventListener('change', function(){
					var hex = getSelectedHex();
					if (this.classList.contains('jagawarta-chip-radio-hex') && this.dataset.hex) setHex(this.dataset.hex);
					else if (this.id === 'jagawarta_chip_color_custom') setHex(colorInput ? colorInput.value : '');
					else if (this.id === 'jagawarta_chip_color_default') setHex('');
					toggleCustom();
				});
			});
		}
		if (colorInput && hexDisplay) {
			colorInput.addEventListener('input', function(){ setHex(this.value); });
		}
		toggleCustom();
		if (currentHex && hexHidden) setHex(currentHex);
	})();
	</script>
	<?php
}

function jagawarta_category_chip_color_save( int $term_id ): void {
	if ( ! isset( $_POST['jagawarta_chip_color'] ) ) {
		return;
	}
	$presets = array( '', 'primary', 'secondary', 'tertiary', 'error', 'hex' );
	$value   = isset( $_POST['jagawarta_chip_color'] ) ? sanitize_text_field( wp_unslash( $_POST['jagawarta_chip_color'] ) ) : '';
	if ( ! in_array( $value, $presets, true ) ) {
		$value = '';
	}
	if ( $value === 'hex' && isset( $_POST['jagawarta_chip_color_hex_text'] ) ) {
		$hex = sanitize_hex_color( wp_unslash( $_POST['jagawarta_chip_color_hex_text'] ) );
		if ( $hex ) {
			update_term_meta( $term_id, JAGAWARTA_CHIP_COLOR_META, 'hex' );
			update_term_meta( $term_id, 'jagawarta_chip_color_hex', $hex );
			return;
		}
	}
	update_term_meta( $term_id, JAGAWARTA_CHIP_COLOR_META, $value );
	delete_term_meta( $term_id, 'jagawarta_chip_color_hex' );
}

function jagawarta_hex_to_hsl( string $hex ): array {
	$hex = ltrim( $hex, '#' );
	if ( strlen( $hex ) !== 6 ) {
		return array( 0, 0, 50 );
	}
	$r = (int) hexdec( substr( $hex, 0, 2 ) ) / 255;
	$g = (int) hexdec( substr( $hex, 2, 2 ) ) / 255;
	$b = (int) hexdec( substr( $hex, 4, 2 ) ) / 255;
	$max = max( $r, $g, $b );
	$min = min( $r, $g, $b );
	$l = ( $max + $min ) / 2;
	if ( $max === $min ) {
		$h = $s = 0;
	} else {
		$d = $max - $min;
		$s = $l > 0.5 ? $d / ( 2 - $max - $min ) : $d / ( $max + $min );
		switch ( $max ) {
			case $r:
				$h = ( ( $g - $b ) / $d ) + ( $g < $b ? 6 : 0 );
				break;
			case $g:
				$h = ( ( $b - $r ) / $d ) + 2;
				break;
			default:
				$h = ( ( $r - $g ) / $d ) + 4;
		}
		$h = $h / 6 * 360;
	}
	return array( round( $h, 2 ), round( $s * 100, 2 ), round( $l * 100, 2 ) );
}

function jagawarta_hsl_to_hex( float $h, float $s, float $l ): string {
	$s /= 100;
	$l /= 100;
	$c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
	$x = $c * ( 1 - abs( fmod( $h / 60, 2 ) - 1 ) );
	$m = $l - $c / 2;
	$r = $g = $b = 0;
	if ( $h < 60 ) {
		$r = $c; $g = $x; $b = 0;
	} elseif ( $h < 120 ) {
		$r = $x; $g = $c; $b = 0;
	} elseif ( $h < 180 ) {
		$r = 0; $g = $c; $b = $x;
	} elseif ( $h < 240 ) {
		$r = 0; $g = $x; $b = $c;
	} elseif ( $h < 300 ) {
		$r = $x; $g = 0; $b = $c;
	} else {
		$r = $c; $g = 0; $b = $x;
	}
	$r = str_pad( dechex( (int) round( ( $r + $m ) * 255 ) ), 2, '0', STR_PAD_LEFT );
	$g = str_pad( dechex( (int) round( ( $g + $m ) * 255 ) ), 2, '0', STR_PAD_LEFT );
	$b = str_pad( dechex( (int) round( ( $b + $m ) * 255 ) ), 2, '0', STR_PAD_LEFT );
	return '#' . $r . $g . $b;
}

/**
 * Convert a hex color to an MD3-style container + on-container pair (muted bg, dark text).
 *
 * @param string $hex Hex color (e.g. #e53935).
 * @return array{container: string, on_container: string} Hex values for background and text.
 */
function jagawarta_hex_to_container_pair( string $hex ): array {
	list( $h, $s, $l ) = jagawarta_hex_to_hsl( $hex );
	$container    = jagawarta_hsl_to_hex( $h, min( $s * 0.5, 35 ), 92 );
	$on_container = jagawarta_hsl_to_hex( $h, min( $s + 20, 80 ), 18 );
	return array(
		'container'    => $container,
		'on_container' => $on_container,
	);
}

/**
 * Deterministic hue from term_id so each category gets a unique fallback hue (no duplicates).
 *
 * @param int $term_id Category term_id.
 * @return float Hue 0–360.
 */
function jagawarta_category_fallback_hue( int $term_id ): float {
	$golden_angle = 137.508;
	return fmod( (float) $term_id * $golden_angle, 360.0 );
}

/**
 * Get container + on-container hex pair for a category (custom hex or fallback).
 * Used by helpers and for wp_head CSS vars.
 *
 * @param int    $term_id Category term_id.
 * @param string $hex     Optional hex (for custom); if empty, use fallback hue.
 * @return array{container: string, on_container: string}
 */
function jagawarta_category_container_pair( int $term_id, string $hex = '' ): array {
	if ( $hex !== '' && sanitize_hex_color( $hex ) ) {
		return jagawarta_hex_to_container_pair( $hex );
	}
	$hue = jagawarta_category_fallback_hue( $term_id );
	$container    = jagawarta_hsl_to_hex( $hue, 25, 92 );
	$on_container = jagawarta_hsl_to_hex( $hue, 45, 18 );
	return array(
		'container'    => $container,
		'on_container' => $on_container,
	);
}

/**
 * Get all categories that need CSS vars (custom hex or default/auto fallback).
 *
 * @return array<int, array{container: string, on_container: string}> term_id => pair.
 */
function jagawarta_get_category_css_var_pairs(): array {
	$terms = get_terms( array(
		'taxonomy'   => 'category',
		'hide_empty' => false,
		'fields'     => 'ids',
	) );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return array();
	}
	$out = array();
	foreach ( $terms as $term_id ) {
		$term_id = (int) $term_id;
		$meta    = get_term_meta( $term_id, JAGAWARTA_CHIP_COLOR_META, true );
		$hex_meta = get_term_meta( $term_id, 'jagawarta_chip_color_hex', true );
		$presets = array( 'primary', 'secondary', 'tertiary', 'error' );
		if ( in_array( $meta, $presets, true ) ) {
			continue;
		}
		if ( $meta === 'hex' && $hex_meta ) {
			$hex = sanitize_hex_color( $hex_meta );
			if ( $hex ) {
				$out[ $term_id ] = jagawarta_category_container_pair( $term_id, $hex );
			} else {
				$out[ $term_id ] = jagawarta_category_container_pair( $term_id, '' );
			}
		} else {
			$out[ $term_id ] = jagawarta_category_container_pair( $term_id, '' );
		}
	}
	return $out;
}

function jagawarta_category_chip_css_vars(): void {
	$pairs = jagawarta_get_category_css_var_pairs();
	if ( empty( $pairs ) ) {
		return;
	}
	$lines = array();
	foreach ( $pairs as $term_id => $pair ) {
		$lines[] = sprintf(
			'  --jw-cat-%d-bg: %s; --jw-cat-%d-fg: %s;',
			$term_id,
			$pair['container'],
			$term_id,
			$pair['on_container']
		);
	}
	echo "<style id=\"jagawarta-category-chip-vars\">\n:root {\n" . implode( "\n", $lines ) . "\n}\n</style>\n";
}
