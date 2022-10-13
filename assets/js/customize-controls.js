/* global libraryBgColors, libraryColor, jQuery, wp, _ */
/**
 * Customizer enhancements for a better user experience.
 *
 * Contains extra logic for our Customizer controls & settings.
 *
 * @since Library 1.0
 */

( function() {
	// Wait until the customizer has finished loading.
	wp.customize.bind( 'ready', function() {
		// Add a listener for accent-color changes.
		wp.customize( 'accent_hue', function( value ) {
			value.bind( function( to ) {
				// Update the value for our accessible colors for all areas.
				Object.keys( libraryBgColors ).forEach( function( context ) {
					var backgroundColorValue;
					if ( libraryBgColors[ context ].color ) {
						backgroundColorValue = libraryBgColors[ context ].color;
					} else {
						backgroundColorValue = wp.customize( libraryBgColors[ context ].setting ).get();
					}
					librarySetAccessibleColorsValue( context, backgroundColorValue, to );
				} );
			} );
		} );

		// Add a listener for background-color changes.
		Object.keys( libraryBgColors ).forEach( function( context ) {
			wp.customize( libraryBgColors[ context ].setting, function( value ) {
				value.bind( function( to ) {
					// Update the value for our accessible colors for this area.
					librarySetAccessibleColorsValue( context, to, wp.customize( 'accent_hue' ).get(), to );
				} );
			} );
		} );

		// Show or hide retina_logo setting on the first load.
		librarySetRetineLogoVisibility( !! wp.customize( 'custom_logo' )() );

		// Add a listener for custom_logo changes.
		wp.customize( 'custom_logo', function( value ) {
			value.bind( function( to ) {
				// Show or hide retina_logo setting on changing custom_logo.
				librarySetRetineLogoVisibility( !! to );
			} );
		} );
	} );

	/**
	 * Updates the value of the "accent_accessible_colors" setting.
	 *
	 * @since Library 1.0
	 *
	 * @param {string} context The area for which we want to get colors. Can be for example "content", "header" etc.
	 * @param {string} backgroundColor The background color (HEX value).
	 * @param {number} accentHue Numeric representation of the selected hue (0 - 359).
	 *
	 * @return {void}
	 */
	function librarySetAccessibleColorsValue( context, backgroundColor, accentHue ) {
		var value, colors;

		// Get the current value for our accessible colors, and make sure it's an object.
		value = wp.customize( 'accent_accessible_colors' ).get();
		value = ( _.isObject( value ) && ! _.isArray( value ) ) ? value : {};

		// Get accessible colors for the defined background-color and hue.
		colors = libraryColor( backgroundColor, accentHue );

		// Sanity check.
		if ( colors.getAccentColor() && 'function' === typeof colors.getAccentColor().toCSS ) {
			// Update the value for this context.
			value[ context ] = {
				text: colors.getTextColor(),
				accent: colors.getAccentColor().toCSS(),
				background: backgroundColor
			};

			// Get borders color.
			value[ context ].borders = colors.bgColorObj
				.clone()
				.getReadableContrastingColor( colors.bgColorObj, 1.36 )
				.toCSS();

			// Get secondary color.
			value[ context ].secondary = colors.bgColorObj
				.clone()
				.getReadableContrastingColor( colors.bgColorObj )
				.s( colors.bgColorObj.s() / 2 )
				.toCSS();
		}

		// Change the value.
		wp.customize( 'accent_accessible_colors' ).set( value );

		// Small hack to save the option.
		wp.customize( 'accent_accessible_colors' )._dirty = true;
	}

	/**
	 * Shows or hides the "retina_logo" setting based on the given value.
	 *
	 * @since Library 1.3
	 *
	 * @param {boolean} visible The visible value.
	 *
	 * @return {void}
	 */
	function librarySetRetineLogoVisibility( visible ) {
		wp.customize.control( 'retina_logo' ).container.toggle( visible );
	}
}( jQuery ) );
