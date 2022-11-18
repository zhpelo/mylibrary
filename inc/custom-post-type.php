<?php 
/**
 * 注册 书籍 帖子类型
 */
function mylibrary_add_books_type()
{
	$labels = array(
		'name'                  => _x('Books', 'Post type general name', "mylibrary"),
		'singular_name'         => _x('Book', 'Post type singular name', "mylibrary"),
		'menu_name'             => _x('Books', 'Admin Menu text', "mylibrary"),
		'name_admin_bar'        => _x('Book', 'Add New on Toolbar', "mylibrary"),
		'add_new'               => __('Add new book', "mylibrary"),
		'add_new_item'          => __('Add new book', "mylibrary"),
		'new_item'              => __('Add new book', "mylibrary"),
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
//新增文章类型
add_action( 'init', 'mylibrary_add_books_type' );


/**
 * +------------------------------------+
 * |               章节功能              |
 * +------------------------------------+
 */
/**
 * 章节后台功能设置
 */
//创建章节数据表
function mylibrary_create_chapter_table()
{
	global $wpdb;
	$table_name = "{$wpdb->prefix}chapters"; //获取表前缀，并设置新表的名称 

	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE `{$table_name}` (
					`chapter_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					`post_id` bigint(20) UNSIGNED NOT NULL,
					`chapter_author` bigint(20) unsigned NOT NULL DEFAULT '0',
					`chapter_title` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
					`chapter_content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
					`chapter_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
					`chapter_order` int(8) NOT NULL DEFAULT '0',
					`chapter_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					`chapter_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					`chapter_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  					`chapter_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					`chapter_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'publish',
					PRIMARY KEY  (`chapter_id`),
					KEY `chapter_parent` (`chapter_parent`),
					KEY `chapter_status` (`chapter_status`),
					KEY `chapter_author` (`chapter_author`)
				) $charset_collate;";
		require_once(ABSPATH . ("wp-admin/includes/upgrade.php"));
		dbDelta($sql);
	}
}
//在启用主题时创建数据表
add_action('after_switch_theme', 'mylibrary_create_chapter_table');

//  注册后台管理模块  
function mylibrary_add_submenu_page()
{
	add_submenu_page(
		'edit.php?post_type=book', 
		__("Chapter list", "mylibrary"), 
		__("Chapter list", "mylibrary"),
		'manage_options',
		'manage-chapters', //别名，也就是在URL中GET传送的参数  
		'mylibrary_manage_chapters' //调用显示内容调用的函数  
	);
}
add_action('admin_menu', 'mylibrary_add_submenu_page');



function mylibrary_manage_chapter_item()
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
					'chapter_content'   => $_POST['chapter_content'],
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
					'chapter_content'   => $_POST['chapter_content'],
					'chapter_date'       => current_time('mysql'),
					'chapter_date_gmt'       => current_time('mysql', 1),
					'chapter_modified'       => current_time('mysql'),
					'chapter_modified_gmt'       => current_time('mysql', 1),
				)
			);
		}

		echo "<div id=\"message\" class=\"updated notice notice-success is-dismissible\"><p>数据保存成功</p></div>";
	} 

	$chapters = mylibrary_get_chapters($post_id);
    $book = get_post($post_id);
	if ($chapter_id > 0) {
		//当前章节
		$chapter = $wpdb->get_row(
			$wpdb->prepare("SELECT * FROM {$wpdb->prefix}chapters WHERE chapter_id=%d", $chapter_id)
		);
	}
	
	require get_template_directory() . "/inc/chapter-item.php";
}
function mylibrary_manage_chapters()
{
	if (isset($_GET['post_id']) && $_GET['post_id'] > 0) {
		mylibrary_manage_chapter_item();
	}else{
        mylibrary_manage_chapter_list();
		
	}
}
function mylibrary_manage_chapter_list(){
    //显示章节列表
	$chapter_list = mylibrary_get_chapter_list();
    require get_template_directory() . "/inc/chapter-list.php";
}

//添加文章列表
function mylibrary_add_columns($columns)
{
	$columns['post_chapters'] = __("Operate", "mylibrary");
	return $columns;
}
add_filter('manage_book_posts_columns', 'mylibrary_add_columns');

function mylibrary_add_columns_item($column, $post_id)
{
	switch ($column) {
		case 'post_chapters':
			echo "<a href=\"edit.php?post_type=book&page=manage-chapters&post_id={$post_id}\">".__("Chapter list", "mylibrary")."</a>";
			break;
	}
}
add_action('manage_book_posts_custom_column', 'mylibrary_add_columns_item', 10, 2);


/**
 * 章节前台功能设置
 */
function mylibrary_add_query_vars() {

	add_filter('query_vars', function ($query_vars) {
		$query_vars[] = 'chapter';
		return $query_vars;
	});
}
add_action( 'init', 'mylibrary_add_query_vars' );


add_action( 'template_include' , function ($template) {
	if (get_query_var('chapter')) {
		return get_template_directory() . '/template-parts/content-chapter.php';
	}
	return $template;
});


function mylibrary_add_body_classes($classes) {
	// 给章节内容页的 body 添加上 singular class属性
	if (get_query_var('chapter')) {
		$classes[] = 'singular';
	}
	return $classes;
}
add_filter('body_class','mylibrary_add_body_classes');
