<?php
/**
 * Customizer Separator Control settings for this theme.
 *
 * @package My_Library
 * @subpackage My_Library
 * @since My_Library 1.0
 */

if ( class_exists( 'WP_Customize_Control' ) ) {

	if ( ! class_exists( 'mylibrary_Separator_Control' ) ) {
		/**
		 * Separator Control.
		 *
		 * @since My_Library 1.0
		 */
		class mylibrary_Separator_Control extends WP_Customize_Control {
			/**
			 * Render the hr.
			 *
			 * @since My_Library 1.0
			 */
			public function render_content() {
				echo '<hr/>';
			}

		}
	}
}
