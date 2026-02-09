<?php
/**
 * Customizer Control: Preset Color Grid.
 *
 * @package JagaWarta
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

/**
 * Class JagaWarta_Preset_Color_Control
 */
class JagaWarta_Preset_Color_Control extends WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'jagawarta_preset_color';

	/**
	 * Presets to display.
	 *
	 * @var array
	 */
	public $presets = array();

	/**
	 * Enqueue control assets.
	 */
	public function enqueue(): void {
		wp_enqueue_style(
			'jagawarta-customizer-controls',
			get_template_directory_uri() . '/inc/assets/customizer-controls.css',
			array( 'wp-color-picker' ), // Dep: wp-color-picker
			JAGAWARTA_VERSION
		);
		wp_enqueue_script(
			'jagawarta-customizer-controls',
			get_template_directory_uri() . '/inc/assets/customizer-controls.js',
			array( 'jquery', 'customize-base', 'wp-color-picker' ), // Dep: wp-color-picker
			JAGAWARTA_VERSION,
			true
		);
	}

	/**
	 * Export data to JS.
	 */
	public function to_json(): void {
		parent::to_json();
		$this->json['presets'] = $this->presets;
		$this->json['id']      = $this->id;
		$this->json['value']   = $this->value();
		$this->json['link']    = $this->get_link();
		$this->json['defaultValue'] = $this->setting->default;
	}

	/**
	 * Render the JS template.
	 */
	public function content_template(): void {
		?>
		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<# if ( data.presets && data.presets.length ) { #>
			<div class="jagawarta-color-grid">
				<# _.each( data.presets, function( color ) { 
					var isSelected = ( color.toLowerCase() === data.value.toLowerCase() );
					var selectedClass = isSelected ? 'selected' : '';
				#>
					<button type="button" 
						class="jagawarta-color-preset {{ selectedClass }}"
						data-color="{{ color }}"
						style="background-color: {{ color }};"
						aria-label="<?php esc_attr_e( 'Select color', 'jagawarta' ); ?> {{ color }}">
					</button>
				<# } ); #>
			</div>
		<# } #>

		<div class="jagawarta-custom-color-wrap">
			<label class="customize-control-title" for="_customize-input-{{ data.id }}-custom">
				<?php esc_html_e( 'Custom Color', 'jagawarta' ); ?>
			</label>
			<input type="text" 
				id="_customize-input-{{ data.id }}-custom" 
				class="jagawarta-custom-color-input" 
				value="{{ data.value }}" 
				data-default-color="{{ data.defaultValue }}"
				{{{ data.link }}} />
		</div>
		<?php
	}
}
