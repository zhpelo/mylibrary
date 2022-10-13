<?php
/**
 * Library SVG Icon helper functions
 *
 * @package My_Library
 * @subpackage My_Library
 * @since My_Library 1.0
 */

if ( ! function_exists( 'library_the_theme_svg' ) ) {
	/**
	 * Output and Get Theme SVG.
	 * Output and get the SVG markup for an icon in the library_SVG_Icons class.
	 *
	 * @since My_Library 1.0
	 *
	 * @param string $svg_name The name of the icon.
	 * @param string $group    The group the icon belongs to.
	 * @param string $color    Color code.
	 */
	function library_the_theme_svg( $svg_name, $group = 'ui', $color = '' ) {
		echo library_get_theme_svg( $svg_name, $group, $color ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in library_get_theme_svg().
	}
}

if ( ! function_exists( 'library_get_theme_svg' ) ) {

	/**
	 * Get information about the SVG icon.
	 *
	 * @since My_Library 1.0
	 *
	 * @param string $svg_name The name of the icon.
	 * @param string $group    The group the icon belongs to.
	 * @param string $color    Color code.
	 */
	function library_get_theme_svg( $svg_name, $group = 'ui', $color = '' ) {

		// Make sure that only our allowed tags and attributes are included.
		$svg = wp_kses(
			library_SVG_Icons::get_svg( $svg_name, $group, $color ),
			array(
				'svg'     => array(
					'class'       => true,
					'xmlns'       => true,
					'width'       => true,
					'height'      => true,
					'viewbox'     => true,
					'aria-hidden' => true,
					'role'        => true,
					'focusable'   => true,
				),
				'path'    => array(
					'fill'      => true,
					'fill-rule' => true,
					'd'         => true,
					'transform' => true,
				),
				'polygon' => array(
					'fill'      => true,
					'fill-rule' => true,
					'points'    => true,
					'transform' => true,
					'focusable' => true,
				),
			)
		);

		if ( ! $svg ) {
			return false;
		}
		return $svg;
	}
}
