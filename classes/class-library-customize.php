<?php
/**
 * Customizer settings for this theme.
 *
 * @package My_Library
 * @subpackage My_Library
 * @since My_Library 1.0
 */

if ( ! class_exists( 'library_Customize' ) ) {
	/**
	 * CUSTOMIZER SETTINGS
	 *
	 * @since My_Library 1.0
	 */
	class library_Customize {

		/**
		 * Register customizer options.
		 *
		 * @since My_Library 1.0
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public static function register( $wp_customize ) {

			/**
			 * Site Title & Description.
			 * */
			$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
			$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

			$wp_customize->selective_refresh->add_partial(
				'blogname',
				array(
					'selector'        => '.site-title a',
					'render_callback' => 'library_customize_partial_blogname',
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				array(
					'selector'        => '.site-description',
					'render_callback' => 'library_customize_partial_blogdescription',
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'custom_logo',
				array(
					'selector'            => '.header-titles [class*=site-]:not(.site-description)',
					'render_callback'     => 'library_customize_partial_site_logo',
					'container_inclusive' => true,
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'retina_logo',
				array(
					'selector'        => '.header-titles [class*=site-]:not(.site-description)',
					'render_callback' => 'library_customize_partial_site_logo',
				)
			);

			/**
			 * Site Identity
			 */

			/* 2X Header Logo ---------------- */
			$wp_customize->add_setting(
				'retina_logo',
				array(
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'retina_logo',
				array(
					'type'        => 'checkbox',
					'section'     => 'title_tagline',
					'priority'    => 10,
					'label'       => __( 'Retina logo', 'library' ),
					'description' => __( 'Scales the logo to half its uploaded size, making it sharp on high-res screens.', 'library' ),
				)
			);

			// Header & Footer Background Color.
			// 网站头部和底部 背景颜色
			$wp_customize->add_setting(
				'header_footer_background_color',
				array(
					'default'           => '#5d6146',
					'sanitize_callback' => 'sanitize_hex_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'header_footer_background_color',
					array(
						'label'   => __( 'Header &amp; Footer Background Color', 'library' ),
						'section' => 'colors',
					)
				)
			);

			// Enable picking an accent color.
			$wp_customize->add_setting(
				'accent_hue_active',
				array(
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( __CLASS__, 'sanitize_select' ),
					'transport'         => 'postMessage',
					'default'           => 'default',
				)
			);

			$wp_customize->add_control(
				'accent_hue_active',
				array(
					'type'    => 'radio',
					'section' => 'colors',
					'label'   => __( 'Primary Color', 'library' ),
					'choices' => array(
						'default' => _x( 'Default', 'color', 'library' ),
						'custom'  => _x( 'Custom', 'color', 'library' ),
					),
				)
			);

			/**
			 * Implementation for the accent color.
			 * This is different to all other color options because of the accessibility enhancements.
			 * The control is a hue-only colorpicker, and there is a separate setting that holds values
			 * for other colors calculated based on the selected hue and various background-colors on the page.
			 *
			 * @since My_Library 1.0
			 */

			// Add the setting for the hue colorpicker.
			$wp_customize->add_setting(
				'accent_hue',
				array(
					'default'           => 344,
					'type'              => 'theme_mod',
					'sanitize_callback' => 'absint',
					'transport'         => 'postMessage',
				)
			);

			// Add setting to hold colors derived from the accent hue.
			$wp_customize->add_setting(
				'accent_accessible_colors',
				array(
					'default'           => array(
						'content'       => array(
							'text'      => '#000000',
							'accent'    => '#cd2653',
							'secondary' => '#6d6d6d',
							'borders'   => '#dcd7ca',
						),
						'header-footer' => array(
							'text'      => '#000000',
							'accent'    => '#cd2653',
							'secondary' => '#6d6d6d',
							'borders'   => '#dcd7ca',
						),
					),
					'type'              => 'theme_mod',
					'transport'         => 'postMessage',
					'sanitize_callback' => array( __CLASS__, 'sanitize_accent_accessible_colors' ),
				)
			);

			// Add the hue-only colorpicker for the accent color.
			// 为强调色添加仅色调颜色选择器。
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'accent_hue',
					array(
						'section'         => 'colors',
						'settings'        => 'accent_hue',
						'description'     => __( 'Apply a custom color for links, buttons, featured images.', 'library' ),
						'mode'            => 'hue',
						'active_callback' => static function() use ( $wp_customize ) {
							return ( 'custom' === $wp_customize->get_setting( 'accent_hue_active' )->value() );
						},
					)
				)
			);

			// Update background color with postMessage, so inline CSS output is updated as well.
			// 用postMessage更新背景颜色，这样内联CSS输出也会更新。
			$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';

			/**
			 * Theme Options
			 */

			$wp_customize->add_section(
				'options',
				array(
					'title'      => __( 'Theme Options', 'library' ),
					'priority'   => 40,
					'capability' => 'edit_theme_options',
				)
			);

			/* Enable Header Search ----------------------------------------------- */

			$wp_customize->add_setting(
				'enable_header_search',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
				)
			);

			$wp_customize->add_control(
				'enable_header_search',
				array(
					'type'     => 'checkbox',
					'section'  => 'options',
					'priority' => 10,
					'label'    => __( 'Show search in header', 'library' ),
				)
			);

			/* Show author bio ---------------------------------------------------- */

			$wp_customize->add_setting(
				'show_author_bio',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
				)
			);

			$wp_customize->add_control(
				'show_author_bio',
				array(
					'type'     => 'checkbox',
					'section'  => 'options',
					'priority' => 10,
					'label'    => __( 'Show author bio', 'library' ),
				)
			);

			/* Display full content or excerpts on the blog and archives --------- */

			$wp_customize->add_setting(
				'blog_content',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => 'full',
					'sanitize_callback' => array( __CLASS__, 'sanitize_select' ),
				)
			);

			$wp_customize->add_control(
				'blog_content',
				array(
					'type'     => 'radio',
					'section'  => 'options',
					'priority' => 10,
					'label'    => __( 'On archive pages, posts show:', 'library' ),
					'choices'  => array(
						'full'    => __( 'Full text', 'library' ),
						'summary' => __( 'Summary', 'library' ),
					),
				)
			);

			/**
			 * Template: Cover Template.
			 */
			$wp_customize->add_section(
				'cover_template_options',
				array(
					'title'       => __( 'Cover Template', 'library' ),
					'capability'  => 'edit_theme_options',
					'description' => __( 'Settings for the "Cover Template" page template. Add a featured image to use as background.', 'library' ),
					'priority'    => 42,
				)
			);

			/* Overlay Fixed Background ------ */

			$wp_customize->add_setting(
				'cover_template_fixed_background',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'cover_template_fixed_background',
				array(
					'type'        => 'checkbox',
					'section'     => 'cover_template_options',
					'label'       => __( 'Fixed Background Image', 'library' ),
					'description' => __( 'Creates a parallax effect when the visitor scrolls.', 'library' ),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'cover_template_fixed_background',
				array(
					'selector' => '.cover-header',
					'type'     => 'cover_fixed',
				)
			);

			/* Separator --------------------- */

			$wp_customize->add_setting(
				'cover_template_separator_1',
				array(
					'sanitize_callback' => 'wp_filter_nohtml_kses',
				)
			);

			$wp_customize->add_control(
				new library_Separator_Control(
					$wp_customize,
					'cover_template_separator_1',
					array(
						'section' => 'cover_template_options',
					)
				)
			);

			/* Overlay Background Color ------ */

			$wp_customize->add_setting(
				'cover_template_overlay_background_color',
				array(
					'default'           => library_get_color_for_area( 'content', 'accent' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'cover_template_overlay_background_color',
					array(
						'label'       => __( 'Overlay Background Color', 'library' ),
						'description' => __( 'The color used for the overlay. Defaults to the accent color.', 'library' ),
						'section'     => 'cover_template_options',
					)
				)
			);

			/* Overlay Text Color ------------ */

			$wp_customize->add_setting(
				'cover_template_overlay_text_color',
				array(
					'default'           => '#ffffff',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'cover_template_overlay_text_color',
					array(
						'label'       => __( 'Overlay Text Color', 'library' ),
						'description' => __( 'The color used for the text in the overlay.', 'library' ),
						'section'     => 'cover_template_options',
					)
				)
			);

			/* Overlay Color Opacity --------- */

			$wp_customize->add_setting(
				'cover_template_overlay_opacity',
				array(
					'default'           => 80,
					'sanitize_callback' => 'absint',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'cover_template_overlay_opacity',
				array(
					'label'       => __( 'Overlay Opacity', 'library' ),
					'description' => __( 'Make sure that the contrast is high enough so that the text is readable.', 'library' ),
					'section'     => 'cover_template_options',
					'type'        => 'range',
					'input_attrs' => library_customize_opacity_range(),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'cover_template_overlay_opacity',
				array(
					'selector' => '.cover-color-overlay',
					'type'     => 'cover_opacity',
				)
			);
		}

		/**
		 * Sanitization callback for the "accent_accessible_colors" setting.
		 *“accent_accessible_colors”设置的 清理回调。
		 * @since My_Library 1.0
		 *
		 * @param array $value The value we want to sanitize.
		 * @return array Returns sanitized value. Each item in the array gets sanitized separately.
		 * 返回已清理的值。阵列中的每个项目都将分别进行清理。
		 */
		public static function sanitize_accent_accessible_colors( $value ) {

			// Make sure the value is an array. Do not typecast, use empty array as fallback.
			//确保该值是一个数组。不要进行类型转换，使用空数组作为后备。
			$value = is_array( $value ) ? $value : array();

			// Loop values.
			foreach ( $value as $area => $values ) {
				foreach ( $values as $context => $color_val ) {
					//清除十六进制颜色。
					$value[ $area ][ $context ] = sanitize_hex_color( $color_val );
				}
			}

			return $value;
		}

		/**
		 * Sanitize select.
		 *
		 * @since My_Library 1.0
		 *
		 * @param string $input   The input from the setting.
		 * @param object $setting The selected setting.
		 * @return string The input from the setting or the default setting.
		 */
		public static function sanitize_select( $input, $setting ) {
			$input   = sanitize_key( $input );
			$choices = $setting->manager->get_control( $setting->id )->choices;
			return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
		}

		/**
		 * Sanitize boolean for checkbox.
		 *
		 * @since My_Library 1.0
		 *
		 * @param bool $checked Whether or not a box is checked.
		 * @return bool
		 */
		public static function sanitize_checkbox( $checked ) {
			return ( ( isset( $checked ) && true === $checked ) ? true : false );
		}

	}

	// Setup the Theme Customizer settings and controls.
	add_action( 'customize_register', array( 'library_Customize', 'register' ) );

}

/**
 * PARTIAL REFRESH FUNCTIONS
 * */
if ( ! function_exists( 'library_customize_partial_blogname' ) ) {
	/**
	 * Render the site title for the selective refresh partial.
	 *
	 * @since My_Library 1.0
	 */
	function library_customize_partial_blogname() {
		bloginfo( 'name' );
	}
}

if ( ! function_exists( 'library_customize_partial_blogdescription' ) ) {
	/**
	 * Render the site description for the selective refresh partial.
	 *
	 * @since My_Library 1.0
	 */
	function library_customize_partial_blogdescription() {
		bloginfo( 'description' );
	}
}

if ( ! function_exists( 'library_customize_partial_site_logo' ) ) {
	/**
	 * Render the site logo for the selective refresh partial.
	 *
	 * Doing it this way so we don't have issues with `render_callback`'s arguments.
	 *
	 * @since My_Library 1.0
	 */
	function library_customize_partial_site_logo() {
		library_site_logo();
	}
}


/**
 * Input attributes for cover overlay opacity option.
 *
 * @since My_Library 1.0
 *
 * @return array Array containing attribute names and their values.
 */
function library_customize_opacity_range() {
	/**
	 * Filters the input attributes for opacity.
	 *
	 * @since My_Library 1.0
	 *
	 * @param array $attrs {
	 *     The attributes.
	 *
	 *     @type int $min  Minimum value.
	 *     @type int $max  Maximum value.
	 *     @type int $step Interval between numbers.
	 * }
	 */
	return apply_filters(
		'library_customize_opacity_range',
		array(
			'min'  => 0,
			'max'  => 90,
			'step' => 5,
		)
	);
}
