<?php
/**
 * Displays the search icon and modal
 *
 * @package My_Library
 * @subpackage My_Library
 * @since My_Library 1.0
 */

?>
<div class="search-modal cover-modal header-footer-group" data-modal-target-string=".search-modal" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Search', 'mylibrary' ); ?>">

	<div class="search-modal-inner modal-inner">

		<div class="section-inner">

			<?php
			get_search_form(
				array(
					'aria_label' => __( 'Search for:', 'mylibrary' ),
				)
			);
			?>

			<button class="toggle search-untoggle close-search-toggle fill-children-current-color" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field">
				<span class="screen-reader-text"><?php _e( 'Close search', 'mylibrary' ); ?></span>
				<?php mylibrary_the_theme_svg( 'cross' ); ?>
			</button><!-- .search-toggle -->

		</div><!-- .section-inner -->

	</div><!-- .search-modal-inner -->

</div><!-- .menu-modal -->
