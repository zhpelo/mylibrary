<?php
/**
 * Library functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package My_Library
 * @subpackage My_Library
 * @since My_Library 1.0
 */

/**
 * Table of Contents:
 * Theme Support
 * Required Files
 * Register Styles
 * Register Scripts
 * Register Menus
 * Custom Logo
 * WP Body Open
 * Register Sidebars
 * Enqueue Block Editor Assets
 * Enqueue Classic Editor Styles
 * Block Editor Settings
 */

/**
 * 设置主题默认值并注册对各种 WordPress 功能的支持。
 *
 * 请注意，此函数与 after_setup_theme 挂钩，该挂钩在 init 挂钩之前运行。 init 钩子对于某些功能来说为时已晚，例如指示对帖子缩略图的支持。
 *
 * @since My_Library 1.0
 */
function library_theme_support() {

	// Add default posts and comments RSS feed links to head.
	// 将默认帖子和评论 的 RSS feed links 添加到头部。
	add_theme_support( 'automatic-feed-links' );

	// 自定义背景颜色。
	add_theme_support(
		'custom-background',
		array(
			'default-color' => 'f5efe0',
		)
	);

	// 设置帖子内容宽度。
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 580;
	}

	/*
	 * 在帖子和页面上启用对帖子缩略图的支持。
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// Set post thumbnail size.
	set_post_thumbnail_size( 1200, 9999 );

	// Add custom image size used in Cover Template.
	add_image_size( 'library-fullscreen', 1980, 9999 );

	// Custom logo.
	$logo_width  = 120;
	$logo_height = 90;

	// If the retina setting is active, double the recommended width and height.
	// 如果视网膜设置处于启用状态，请将推荐的宽度和高度加倍。
	if ( get_theme_mod( 'retina_logo', false ) ) {
		$logo_width  = floor( $logo_width * 2 );
		$logo_height = floor( $logo_height * 2 );
	}

	add_theme_support(
		'custom-logo',
		array(
			'height'      => $logo_height,
			'width'       => $logo_width,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	/*
	 * 让 WordPress 管理文档标题。
	 * 通过添加主题支持，我们声明此主题在文档头部不使用硬编码的 <title> 标签，并希望 WordPress 为我们提供。
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 * 切换搜索表单、评论表单和评论的默认核心标记以输出有效的 HTML5。
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
			'navigation-widgets',
		)
	);

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Library, use a find and replace
	 * to change 'library' to the name of your theme in all the template files.
	 * 加载翻译文件
	 */
	load_theme_textdomain( 'library' );

	// Add support for full and wide align images.
	// 添加对全对齐和宽对齐图像的支持。
	add_theme_support( 'align-wide' );

	// Add support for responsive embeds.
	// 添加对响应式嵌入的支持。
	add_theme_support( 'responsive-embeds' );

	/*
	 * Adds starter content to highlight the theme on fresh sites.
	 * 添加入门内容以突出新网站上的主题。
	 * This is done conditionally to avoid loading the starter content on every
	 * page load, as it is a one-off operation only needed once in the customizer.
	 * 这是有条件地完成的，以避免在每次页面加载时加载起始内容，因为它是一次性操作，只需要在定制器中进行一次。
	 */
	if ( is_customize_preview() ) { // 是否正在定制器中预览站点。
		require get_template_directory() . '/inc/starter-content.php';
		add_theme_support( 'starter-content', library_get_starter_content() );
	}

	// Add theme support for selective refresh for widgets.
	// 为小部件的选择性刷新添加主题支持。
	add_theme_support( 'customize-selective-refresh-widgets' );

	/*
	 * Adds `async` and `defer` support for scripts registered or enqueued
	 * by the theme.
	 * 为主题注册或入队的脚本添加了 `async` 和 `defer` 支持
	 */
	$loader = new library_Script_Loader();
	add_filter( 'script_loader_tag', array( $loader, 'filter_script_loader_tag' ), 10, 2 );

	//ebooks_add_post_type();
	//设置chapter 伪静态规则
	add_rewrite_rule( '^chapter/(\d+)[/]?$', 'index.php?chapter=$matches[1]', 'top' );
	flush_rewrite_rules();
}

add_action( 'after_setup_theme', 'library_theme_support' );

/**
 * REQUIRED FILES
 * Include required files.
 */
require get_template_directory() . '/inc/template-tags.php';

// Handle SVG icons.
require get_template_directory() . '/classes/class-library-svg-icons.php';
require get_template_directory() . '/inc/svg-icons.php';

// Handle Customizer settings.
require get_template_directory() . '/classes/class-library-customize.php';

// Require Separator Control class.
require get_template_directory() . '/classes/class-library-separator-control.php';

// Custom comment walker.
require get_template_directory() . '/classes/class-library-walker-comment.php';

// Custom page walker.
require get_template_directory() . '/classes/class-library-walker-page.php';

// Custom script loader class.
require get_template_directory() . '/classes/class-library-script-loader.php';

// Non-latin language handling.
// 非拉丁语处理。
require get_template_directory() . '/classes/class-library-non-latin-languages.php';

// Custom CSS.
// 自定义CSS
require get_template_directory() . '/inc/custom-css.php';

// Block Patterns.
require get_template_directory() . '/inc/block-patterns.php';

/**
 * Register and Enqueue Styles.
 *
 * @since My_Library 1.0
 */
function library_register_styles() {

	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'library-style', get_stylesheet_uri(), array(), $theme_version );
	wp_style_add_data( 'library-style', 'rtl', 'replace' );

	// Add output of Customizer settings as inline style.
	// 将定制器设置的输出添加为内联样式。
	// 向已注册的样式表添加额外的 CSS 样式。
	wp_add_inline_style( 'library-style', library_get_customizer_css( 'front-end' ) );

	// Add print CSS.
	wp_enqueue_style( 'library-print-style', get_template_directory_uri() . '/print.css', null, $theme_version, 'print' );

}

add_action( 'wp_enqueue_scripts', 'library_register_styles' );

/**
 * Register and Enqueue Scripts.
 * 注册和加入 Scripts
 * @since My_Library 1.0
 */
function library_register_scripts() {

	$theme_version = wp_get_theme()->get( 'Version' );

	if ( ( ! is_admin() ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		//使用 评论回复 js脚本
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'library-js', get_template_directory_uri() . '/assets/js/index.js', array(), $theme_version, false );
	wp_script_add_data( 'library-js', 'async', true );

}

add_action( 'wp_enqueue_scripts', 'library_register_scripts' );

/**
 * Fix skip link focus in IE11.
 * 修复IE11中的跳过链接焦点。
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 * 这不会将脚本排队，因为它很小，而且只适用于IE11，因此，它不保证加载整个专用阻塞脚本。
 *
 * @since My_Library 1.0
 *
 * @link https://git.io/vWdr2
 */
function library_skip_link_focus_fix() {
	// The following is minified via `terser --compress --mangle -- assets/js/skip-link-focus-fix.js`.
	?>
	<script>
	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'library_skip_link_focus_fix' );

/**
 * Enqueue non-latin language styles.
 * 加入 非拉丁语css。
 * @since My_Library 1.0
 *
 * @return void
 */
function library_non_latin_languages() {
	$custom_css = library_Non_Latin_Languages::get_non_latin_css( 'front-end' );

	if ( $custom_css ) {
		//向已注册的样式表添加额外的 CSS 样式。
		wp_add_inline_style( 'library-style', $custom_css );
	}
}

add_action( 'wp_enqueue_scripts', 'library_non_latin_languages' );

/**
 * Register navigation menus uses wp_nav_menu in five places.
 * 注册导航菜单
 *
 * @since My_Library 1.0
 */
function library_menus() {

	$locations = array(
		'primary'  => __( 'Desktop Horizontal Menu', 'library' ),
		'expanded' => __( 'Desktop Expanded Menu', 'library' ),
		'mobile'   => __( 'Mobile Menu', 'library' ),
		'footer'   => __( 'Footer Menu', 'library' ),
		'social'   => __( 'Social Menu', 'library' ),
	);

	register_nav_menus( $locations );
	//新增文章类型
	ebooks_add_post_type();
}

add_action( 'init', 'library_menus' );

/**
 * Get the information about the logo.
 * 获取有关 logo 的信息。
 * @since My_Library 1.0
 *
 * @param string $html The HTML output from get_custom_logo (core function).
 * @return string
 */
function library_get_custom_logo( $html ) {

	$logo_id = get_theme_mod( 'custom_logo' );

	if ( ! $logo_id ) {
		return $html;
	}

	$logo = wp_get_attachment_image_src( $logo_id, 'full' );

	if ( $logo ) {
		// For clarity.
		$logo_width  = esc_attr( $logo[1] );
		$logo_height = esc_attr( $logo[2] );

		// If the retina logo setting is active, reduce the width/height by half.
		// 如果 启用 视网膜logo，将宽度/高度减半
		if ( get_theme_mod( 'retina_logo', false ) ) {
			$logo_width  = floor( $logo_width / 2 );
			$logo_height = floor( $logo_height / 2 );

			$search = array(
				'/width=\"\d+\"/iU',
				'/height=\"\d+\"/iU',
			);

			$replace = array(
				"width=\"{$logo_width}\"",
				"height=\"{$logo_height}\"",
			);

			// Add a style attribute with the height, or append the height to the style attribute if the style attribute already exists.
			if ( strpos( $html, ' style=' ) === false ) {
				$search[]  = '/(src=)/';
				$replace[] = "style=\"height: {$logo_height}px;\" src=";
			} else {
				$search[]  = '/(style="[^"]*)/';
				$replace[] = "$1 height: {$logo_height}px;";
			}

			$html = preg_replace( $search, $replace, $html );

		}
	}

	return $html;

}

add_filter( 'get_custom_logo', 'library_get_custom_logo' );

if ( ! function_exists( 'wp_body_open' ) ) {

	/**
	 * Shim for wp_body_open, ensuring backward compatibility with versions of WordPress older than 5.2.
	 * 填充wp_body_open，确保向后兼容早于5.2的WordPress版本。
	 * @since My_Library 1.0
	 */
	function wp_body_open() {
		/** This action is documented in wp-includes/general-template.php */
		do_action( 'wp_body_open' );
	}
}

/**
 * Include a skip to content link at the top of the page so that users can bypass the menu.
 * 在页面顶部包含跳转到内容链接，以便用户可以绕过菜单。
 * @since My_Library 1.0
 */
function library_skip_link() {
	echo '<a class="skip-link screen-reader-text" href="#site-content">' . __( 'Skip to the content', 'library' ) . '</a>';
}

add_action( 'wp_body_open', 'library_skip_link', 5 );

/**
 * Register widget areas.
 * 注册小部件区域。
 * @since My_Library 1.0
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function library_sidebar_registration() {

	// Arguments used in all register_sidebar() calls.
	$shared_args = array(
		'before_title'  => '<h2 class="widget-title subheading heading-size-3">',
		'after_title'   => '</h2>',
		'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
		'after_widget'  => '</div></div>',
	);

	// Footer #1.
	register_sidebar(
		array_merge(
			$shared_args,
			array(
				'name'        => __( 'Footer #1', 'library' ),
				'id'          => 'sidebar-1',
				'description' => __( 'Widgets in this area will be displayed in the first column in the footer.', 'library' ),
			)
		)
	);

	// Footer #2.
	register_sidebar(
		array_merge(
			$shared_args,
			array(
				'name'        => __( 'Footer #2', 'library' ),
				'id'          => 'sidebar-2',
				'description' => __( 'Widgets in this area will be displayed in the second column in the footer.', 'library' ),
			)
		)
	);

}

add_action( 'widgets_init', 'library_sidebar_registration' );

/**
 * Enqueue supplemental block editor styles.
 * 将区块编辑器的补充样式 加入队列。
 * @since My_Library 1.0
 */
function library_block_editor_styles() {

	// Enqueue the editor styles.
	wp_enqueue_style( 'library-block-editor-styles', get_theme_file_uri( '/assets/css/editor-style-block.css' ), array(), wp_get_theme()->get( 'Version' ), 'all' );
	wp_style_add_data( 'library-block-editor-styles', 'rtl', 'replace' );

	// Add inline style from the Customizer.
	wp_add_inline_style( 'library-block-editor-styles', library_get_customizer_css( 'block-editor' ) );

	// Add inline style for non-latin fonts.
	// 为非拉丁字体添加内联样式。
	wp_add_inline_style( 'library-block-editor-styles', library_Non_Latin_Languages::get_non_latin_css( 'block-editor' ) );

	// Enqueue the editor script.
	wp_enqueue_script( 'library-block-editor-script', get_theme_file_uri( '/assets/js/editor-script-block.js' ), array( 'wp-blocks', 'wp-dom' ), wp_get_theme()->get( 'Version' ), true );
}

add_action( 'enqueue_block_editor_assets', 'library_block_editor_styles', 1, 1 );

/**
 * Enqueue classic editor styles.
 * 将经典编辑器样式 排入队列。
 * @since My_Library 1.0
 */
function library_classic_editor_styles() {

	$classic_editor_styles = array(
		'/assets/css/editor-style-classic.css',
	);

	add_editor_style( $classic_editor_styles );

}

add_action( 'init', 'library_classic_editor_styles' );

/**
 * Output Customizer settings in the classic editor.
 * 经典编辑器中的输出定制器设置。
 * Adds styles to the head of the TinyMCE iframe. Kudos to @Otto42 for the original solution.
 * 将样式添加到 TinyMCE iframe 的头部。 感谢@Otto42 的原始解决方案。
 * @since My_Library 1.0
 *
 * @param array $mce_init TinyMCE styles.
 * @return array TinyMCE styles.
 */
function library_add_classic_editor_customizer_styles( $mce_init ) {

	$styles = library_get_customizer_css( 'classic-editor' );

	if ( ! isset( $mce_init['content_style'] ) ) {
		$mce_init['content_style'] = $styles . ' ';
	} else {
		$mce_init['content_style'] .= ' ' . $styles . ' ';
	}

	return $mce_init;

}

add_filter( 'tiny_mce_before_init', 'library_add_classic_editor_customizer_styles' );

/**
 * Output non-latin font styles in the classic editor.
 * 在经典编辑器中输出非拉丁字体样式。
 * Adds styles to the head of the TinyMCE iframe. Kudos to @Otto42 for the original solution.
 *
 * @param array $mce_init TinyMCE styles.
 * @return array TinyMCE styles.
 */
function library_add_classic_editor_non_latin_styles( $mce_init ) {

	$styles = library_Non_Latin_Languages::get_non_latin_css( 'classic-editor' );

	// Return if there are no styles to add.
	if ( ! $styles ) {
		return $mce_init;
	}

	if ( ! isset( $mce_init['content_style'] ) ) {
		$mce_init['content_style'] = $styles . ' ';
	} else {
		$mce_init['content_style'] .= ' ' . $styles . ' ';
	}

	return $mce_init;

}

add_filter( 'tiny_mce_before_init', 'library_add_classic_editor_non_latin_styles' );

/**
 * Block Editor Settings.
 * 区块编辑器 设置
 * Add custom colors and font sizes to the block editor.
 * 将自定义颜色和字体大小添加到块编辑器。
 * 
 * @since My_Library 1.0
 */
function library_block_editor_settings() {

	// Block Editor Palette.
	$editor_color_palette = array(
		array(
			'name'  => __( 'Accent Color', 'library' ),
			'slug'  => 'accent',
			'color' => library_get_color_for_area( 'content', 'accent' ),
		),
		array(
			'name'  => _x( 'Primary', 'color', 'library' ),
			'slug'  => 'primary',
			'color' => library_get_color_for_area( 'content', 'text' ),
		),
		array(
			'name'  => _x( 'Secondary', 'color', 'library' ),
			'slug'  => 'secondary',
			'color' => library_get_color_for_area( 'content', 'secondary' ),
		),
		array(
			'name'  => __( 'Subtle Background', 'library' ),
			'slug'  => 'subtle-background',
			'color' => library_get_color_for_area( 'content', 'borders' ),
		),
	);

	// Add the background option.
	$background_color = get_theme_mod( 'background_color' );
	if ( ! $background_color ) {
		$background_color_arr = get_theme_support( 'custom-background' );
		$background_color     = $background_color_arr[0]['default-color'];
	}
	$editor_color_palette[] = array(
		'name'  => __( 'Background Color', 'library' ),
		'slug'  => 'background',
		'color' => '#' . $background_color,
	);

	// If we have accent colors, add them to the block editor palette.
	if ( $editor_color_palette ) {
		add_theme_support( 'editor-color-palette', $editor_color_palette );
	}

	// Block Editor Font Sizes.
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name'      => _x( 'Small', 'Name of the small font size in the block editor', 'library' ),
				'shortName' => _x( 'S', 'Short name of the small font size in the block editor.', 'library' ),
				'size'      => 18,
				'slug'      => 'small',
			),
			array(
				'name'      => _x( 'Regular', 'Name of the regular font size in the block editor', 'library' ),
				'shortName' => _x( 'M', 'Short name of the regular font size in the block editor.', 'library' ),
				'size'      => 21,
				'slug'      => 'normal',
			),
			array(
				'name'      => _x( 'Large', 'Name of the large font size in the block editor', 'library' ),
				'shortName' => _x( 'L', 'Short name of the large font size in the block editor.', 'library' ),
				'size'      => 26.25,
				'slug'      => 'large',
			),
			array(
				'name'      => _x( 'Larger', 'Name of the larger font size in the block editor', 'library' ),
				'shortName' => _x( 'XL', 'Short name of the larger font size in the block editor.', 'library' ),
				'size'      => 32,
				'slug'      => 'larger',
			),
		)
	);

	add_theme_support( 'editor-styles' );

	// If we have a dark background color then add support for dark editor style.
	// 如果我们有深色背景颜色，则添加对深色编辑器样式的支持。
	// We can determine if the background color is dark by checking if the text-color is white.
	// 我们可以通过检查文本颜色是否为白色来确定背景颜色是否为深。
	if ( '#ffffff' === strtolower( library_get_color_for_area( 'content', 'text' ) ) ) {
		add_theme_support( 'dark-editor-style' );
	}

}

add_action( 'after_setup_theme', 'library_block_editor_settings' );

/**
 * Overwrite default more tag with styling and screen reader markup.
 * 使用自定义样式 替换 默认的 more 标记。
 *  
 * @param string $html The default output HTML for the more tag.
 * @return string
 */
function library_read_more_tag( $html ) {
	return preg_replace( '/<a(.*)>(.*)<\/a>/iU', sprintf( '<div class="read-more-button-wrap"><a$1><span class="faux-button">$2</span> <span class="screen-reader-text">"%1$s"</span></a></div>', get_the_title( get_the_ID() ) ), $html );
}

add_filter( 'the_content_more_link', 'library_read_more_tag' );

/**
 * Enqueues scripts for customizer controls & settings.
 *
 * @since My_Library 1.0
 *
 * @return void
 */
function library_customize_controls_enqueue_scripts() {
	$theme_version = wp_get_theme()->get( 'Version' );

	// Add main customizer js file.
	wp_enqueue_script( 'library-customize', get_template_directory_uri() . '/assets/js/customize.js', array( 'jquery' ), $theme_version, false );

	// Add script for color calculations.
	wp_enqueue_script( 'library-color-calculations', get_template_directory_uri() . '/assets/js/color-calculations.js', array( 'wp-color-picker' ), $theme_version, false );

	// Add script for controls.
	wp_enqueue_script( 'library-customize-controls', get_template_directory_uri() . '/assets/js/customize-controls.js', array( 'library-color-calculations', 'customize-controls', 'underscore', 'jquery' ), $theme_version, false );
	wp_localize_script( 'library-customize-controls', 'libraryBgColors', library_get_customizer_color_vars() );
}

add_action( 'customize_controls_enqueue_scripts', 'library_customize_controls_enqueue_scripts' );

/**
 * Enqueue scripts for the customizer preview.
 *
 * @since My_Library 1.0
 *
 * @return void
 */
function library_customize_preview_init() {
	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_script( 'library-customize-preview', get_theme_file_uri( '/assets/js/customize-preview.js' ), array( 'customize-preview', 'customize-selective-refresh', 'jquery' ), $theme_version, true );
	wp_localize_script( 'library-customize-preview', 'libraryBgColors', library_get_customizer_color_vars() );
	wp_localize_script( 'library-customize-preview', 'libraryPreviewEls', library_get_elements_array() );

	wp_add_inline_script(
		'library-customize-preview',
		sprintf(
			'wp.customize.selectiveRefresh.partialConstructor[ %1$s ].prototype.attrs = %2$s;',
			wp_json_encode( 'cover_opacity' ),
			wp_json_encode( library_customize_opacity_range() )
		)
	);
}

add_action( 'customize_preview_init', 'library_customize_preview_init' );

/**
 * Get accessible color for an area.
 *
 * @since My_Library 1.0
 *
 * @param string $area    The area we want to get the colors for.
 * @param string $context Can be 'text' or 'accent'.
 * @return string Returns a HEX color.
 */
function library_get_color_for_area( $area = 'content', $context = 'text' ) {

	// Get the value from the theme-mod.
	$settings = get_theme_mod(
		'accent_accessible_colors',
		array(
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
		)
	);

	// If we have a value return it.
	if ( isset( $settings[ $area ] ) && isset( $settings[ $area ][ $context ] ) ) {
		return $settings[ $area ][ $context ];
	}

	// Return false if the option doesn't exist.
	return false;
}

/**
 * Returns an array of variables for the customizer preview.
 *
 * @since My_Library 1.0
 *
 * @return array
 */
function library_get_customizer_color_vars() {
	$colors = array(
		'content'       => array(
			'setting' => 'background_color',
		),
		'header-footer' => array(
			'setting' => 'header_footer_background_color',
		),
	);
	return $colors;
}

/**
 * Get an array of elements.
 *
 * @since My_Library 1.0
 *
 * @return array
 */
function library_get_elements_array() {

	// The array is formatted like this:
	// [key-in-saved-setting][sub-key-in-setting][css-property] = [elements].
	$elements = array(
		'content'       => array(
			'accent'     => array(
				'color'            => array( '.color-accent', '.color-accent-hover:hover', '.color-accent-hover:focus', ':root .has-accent-color', '.has-drop-cap:not(:focus):first-letter', '.wp-block-button.is-style-outline', 'a' ),
				'border-color'     => array( 'blockquote', '.border-color-accent', '.border-color-accent-hover:hover', '.border-color-accent-hover:focus' ),
				'background-color' => array( 'button', '.button', '.faux-button', '.wp-block-button__link', '.wp-block-file .wp-block-file__button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', '.bg-accent', '.bg-accent-hover:hover', '.bg-accent-hover:focus', ':root .has-accent-background-color', '.comment-reply-link' ),
				'fill'             => array( '.fill-children-accent', '.fill-children-accent *' ),
			),
			'background' => array(
				'color'            => array( ':root .has-background-color', 'button', '.button', '.faux-button', '.wp-block-button__link', '.wp-block-file__button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', '.wp-block-button', '.comment-reply-link', '.has-background.has-primary-background-color:not(.has-text-color)', '.has-background.has-primary-background-color *:not(.has-text-color)', '.has-background.has-accent-background-color:not(.has-text-color)', '.has-background.has-accent-background-color *:not(.has-text-color)' ),
				'background-color' => array( ':root .has-background-background-color' ),
			),
			'text'       => array(
				'color'            => array( 'body', '.entry-title a', ':root .has-primary-color' ),
				'background-color' => array( ':root .has-primary-background-color' ),
			),
			'secondary'  => array(
				'color'            => array( 'cite', 'figcaption', '.wp-caption-text', '.post-meta', '.entry-content .wp-block-archives li', '.entry-content .wp-block-categories li', '.entry-content .wp-block-latest-posts li', '.wp-block-latest-comments__comment-date', '.wp-block-latest-posts__post-date', '.wp-block-embed figcaption', '.wp-block-image figcaption', '.wp-block-pullquote cite', '.comment-metadata', '.comment-respond .comment-notes', '.comment-respond .logged-in-as', '.pagination .dots', '.entry-content hr:not(.has-background)', 'hr.styled-separator', ':root .has-secondary-color' ),
				'background-color' => array( ':root .has-secondary-background-color' ),
			),
			'borders'    => array(
				'border-color'        => array( 'pre', 'fieldset', 'input', 'textarea', 'table', 'table *', 'hr' ),
				'background-color'    => array( 'caption', 'code', 'code', 'kbd', 'samp', '.wp-block-table.is-style-stripes tbody tr:nth-child(odd)', ':root .has-subtle-background-background-color' ),
				'border-bottom-color' => array( '.wp-block-table.is-style-stripes' ),
				'border-top-color'    => array( '.wp-block-latest-posts.is-grid li' ),
				'color'               => array( ':root .has-subtle-background-color' ),
			),
		),
		
		'header-footer' => array(
			'accent'     => array(
				// 'color'            => array( 'body:not(.overlay-header) .primary-menu > li > a', 'body:not(.overlay-header) .primary-menu > li > .icon', '.modal-menu a', '.footer-menu a, .footer-widgets a', '#site-footer .wp-block-button.is-style-outline', '.wp-block-pullquote:before', '.singular:not(.overlay-header) .entry-header a', '.archive-header a', '.header-footer-group .color-accent', '.header-footer-group .color-accent-hover:hover' ),
				'background-color' => array( '.social-icons a', '#site-footer button:not(.toggle)', '#site-footer .button', '#site-footer .faux-button', '#site-footer .wp-block-button__link', '#site-footer .wp-block-file__button', '#site-footer input[type="button"]', '#site-footer input[type="reset"]', '#site-footer input[type="submit"]' ),
			),
			'background' => array(
				'color'            => array( '.social-icons a', 'body:not(.overlay-header) .primary-menu ul', '.header-footer-group button', '.header-footer-group .button', '.header-footer-group .faux-button', '.header-footer-group .wp-block-button:not(.is-style-outline) .wp-block-button__link', '.header-footer-group .wp-block-file__button', '.header-footer-group input[type="button"]', '.header-footer-group input[type="reset"]', '.header-footer-group input[type="submit"]' ),
				'background-color' => array( '#site-header', '.footer-nav-widgets-wrapper', '#site-footer', '.menu-modal', '.menu-modal-inner', '.search-modal-inner', '.archive-header', '.singular .entry-header', '.singular .featured-media:before', '.wp-block-pullquote:before' ),
			),
			'text'       => array(
				'color'               => array( '.header-footer-group', 'body:not(.overlay-header) #site-header .toggle', '.menu-modal .toggle' ),
				'background-color'    => array( 'body:not(.overlay-header) .primary-menu ul' ),
				'border-bottom-color' => array( 'body:not(.overlay-header) .primary-menu > li > ul:after' ),
				'border-left-color'   => array( 'body:not(.overlay-header) .primary-menu ul ul:after' ),
			),
			'secondary'  => array(
				'color' => array( '.site-description', 'body:not(.overlay-header) .toggle-inner .toggle-text', '.widget .post-date', '.widget .rss-date', '.widget_archive li', '.widget_categories li', '.widget cite', '.widget_pages li', '.widget_meta li', '.widget_nav_menu li', '.powered-by-wordpress', '.to-the-top', '.singular .entry-header .post-meta', '.singular:not(.overlay-header) .entry-header .post-meta a' ),
			),
			'borders'    => array(
				'border-color'     => array( '.header-footer-group pre', '.header-footer-group fieldset', '.header-footer-group input', '.header-footer-group textarea', '.header-footer-group table', '.header-footer-group table *', '.footer-nav-widgets-wrapper', '#site-footer', '.menu-modal nav *', '.footer-widgets-outer-wrapper', '.footer-top' ),
				'background-color' => array( '.header-footer-group table caption', 'body:not(.overlay-header) .header-inner .toggle-wrapper::before' ),
			),
		),
	);

	/**
	 * Filters Library theme elements.
	 *
	 * @since My_Library 1.0
	 *
	 * @param array Array of elements.
	 */
	return apply_filters( 'library_get_elements_array', $elements );
}



/**
 * Register a custom post type called "book".
 *
 * @see get_post_type_labels() for label keys.
 */
function ebooks_add_post_type()
{
	$labels = array(
		'name'                  => _x('Books', 'Post type general name', "library"),
		'singular_name'         => _x('Book', 'Post type singular name', "library"),
		'menu_name'             => _x('Books', 'Admin Menu text', "library"),
		'name_admin_bar'        => _x('Book', 'Add New on Toolbar', "library"),
		'add_new'               => __('Add new book', "library"),
		'add_new_item'          => __('Add new book', "library"),
		'new_item'              => __('Add new book', "library"),

	);

	$args = array(
		'labels'             => $labels,
		//是否公开
		'public'             => true,
		//是否可查询
		'publicly_queryable' => true,
		//显示在后台菜单中
		'show_ui'            => true,
		//显示在后台菜单中
		'show_in_menu'       => true,
		//是否可以查询，和publicly_queryable一起使用
		'query_var'          => true,
		//重写url
		'rewrite'            => array('slug' => 'book'),
		//该文章类型的权限
		'capability_type'    => 'post',
		'taxonomies'		 => array( 'category', 'post_tag' ),
		//是否有归档
		'has_archive'        => true,
		//是否水平，如果水平就是页面，否则类似文章这种可以有分类目录（需要自定义分类目录）
		'hierarchical'       => false,
		'menu_icon'	=> 'dashicons-media-document',
		//菜单定位
		'menu_position'      => 5,
		//该文章类型支持的功能
		'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
	);
	register_post_type('book', $args);
}

function ashuwp_posts_per_page($query)
{
	//首页或者搜索页的主循环
	if ((is_home() || is_search() || is_category() || is_tag()) && $query->is_main_query()) {
		$query->set('post_type', array('post', 'book')); 
	}
	return $query;
}
add_action('pre_get_posts', 'ashuwp_posts_per_page');


function library_add_query_vars() {

	add_filter('query_vars', function ($query_vars) {
		$query_vars[] = 'chapter';
		return $query_vars;
	});
}

add_action( 'init', 'library_add_query_vars' );

add_action( 'template_include' , function ($template) {
	if (get_query_var('chapter') == false || get_query_var('chapter') == '') {
		return $template;
	}
	return get_template_directory() . '/template-parts/content-chapter.php';
});

add_filter('body_class','ebooks_add_body_classes');

function ebooks_add_body_classes($classes) {
	// 给章节内容页的 body 添加上 singular class属性
	if (get_query_var('chapter')) {
		$classes[] = 'singular';
	}
	return $classes;
}


//注册后台管理模块  
add_action('admin_menu', 'add_theme_options_menu');
function add_theme_options_menu()
{
	add_submenu_page(
		'edit.php?post_type=book', 
		__("Chapter list", "library"), 
		__("Chapter list", "library"),
		'manage_options',
		'manage-chapters', //别名，也就是在URL中GET传送的参数  
		'ebooks_manage_chapters' //调用显示内容调用的函数  
	);
}

function ebooks_manage_chapter_post()
{
	global $wpdb;
	$post_id = (int)$_GET['post_id'];
	$chapter_id = isset($_GET['chapter_id']) ? (int)$_GET['chapter_id'] : 0;
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if ($chapter_id > 0) {
			//更新章节
			$wpdb->update(
				$wpdb->prefix . 'chapters',
				array(
					'chapter_title'     => esc_sql($_POST['chapter_title']),
					'chapter_content'   => stripslashes($_POST['chapter_content']),
					'chapter_modified'       => current_time('mysql'),
					'chapter_modified_gmt'       => current_time('mysql', 1),
				),
				array(
					'chapter_id' => $chapter_id
				)
			);
		} else {
			//新增章节
			$wpdb->insert(
				$wpdb->prefix . 'chapters',
				array(
					'post_id'           => $post_id,
					'chapter_title'     => esc_sql($_POST['chapter_title']),
					'chapter_content'   => addslashes($_POST['chapter_content']),
					'chapter_date'       => current_time('mysql'),
					'chapter_date_gmt'       => current_time('mysql', 1),
					'chapter_modified'       => current_time('mysql'),
					'chapter_modified_gmt'       => current_time('mysql', 1),
				)
			);
		}

		echo "<div id=\"message\" class=\"updated notice notice-success is-dismissible\"><p>数据保存成功</p></div>";
	} 

	$chapters = ebooks_get_chapters($post_id);
	if ($chapter_id > 0) {
		//当前章节
		$chapter = $wpdb->get_row(
			$wpdb->prepare("SELECT * FROM {$wpdb->prefix}chapters WHERE chapter_id=%d", $chapter_id)
		);
	}
	
	require get_template_directory() . "/inc/manage-chapters.php";
}
function ebooks_manage_chapters()
{
	if (isset($_GET['post_id']) && $_GET['post_id'] > 0) {
		ebooks_manage_chapter_post();
	}else{
		//显示章节列表
		require get_template_directory() . "/inc/chapter-list.php";
	}
}

//添加文章列表
function ebooks_add_chapters_column($columns)
{
	$columns['post_chapters'] = __("Operate", "library");
	return $columns;
}
add_filter('manage_book_posts_columns', 'ebooks_add_chapters_column');

function views_column_content($column, $post_id)
{
	switch ($column) {
		case 'post_chapters':
			echo "<a href=\"edit.php?post_type=book&page=manage-chapters&post_id={$post_id}\">".__("Chapter list", "library")."</a>";
			break;
	}
}
add_action('manage_book_posts_custom_column', 'views_column_content', 10, 2);



// 测试代码

// var_dump(sanitize_hex_color("#cd2653"));
// exit();