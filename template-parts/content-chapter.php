<?php
get_header();
$chapter_id = get_query_var('chapter');
$chapter = mylibrary_get_chapter($chapter_id);
$book = get_post($chapter->post_id);
$chapters = mylibrary_get_chapters($chapter->post_id);
$chapter_ids = array_column($chapters, 'chapter_id');
$chapter_titles = array_column($chapters, 'chapter_title');
$current_key = array_search($chapter_id, $chapter_ids);

$prev = $next = [];
if ($current_key >= 1) {
    $prev['id'] = $chapter_ids[$current_key - 1];
    $prev['title'] = $chapter_titles[$current_key - 1];
}
if (count($chapter_ids) > $current_key + 1) {
    $next['id'] = $chapter_ids[$current_key + 1];
    $next['title'] = $chapter_titles[$current_key + 1];
}

?>
<main id="site-content">
    <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
        <header class="entry-header has-text-align-center header-footer-group">

            <div class="entry-header-inner section-inner medium">

                <h1 class="chapter-title">
                <a class="chapter-book-title" href="<?php echo get_permalink($book->ID); ?>" >
                    <?php echo $book->post_title; ?>
                </a> / 
                <?php echo $chapter->chapter_title; ?></h1>
                <div class="post-meta-wrapper post-meta-single post-meta-single-top">
                    <ul class="post-meta">
                        <li class="post-author meta-wrapper">
                            <span class="meta-icon">
                                <span class="screen-reader-text"><?php _e( 'Post author', 'mylibrary' ); ?></span>
                                <?php mylibrary_the_theme_svg( 'user' ); ?>
                            </span>

                            <span class="meta-text">
                                <?php
                                printf(
                                    /* translators: %s: Author name. */
                                    __( 'By %s', 'mylibrary' ),
                                    '<a href="' . esc_url( get_author_posts_url( $book->post_author ) ) . '">' . esc_html( get_the_author_meta( 'display_name', $book->post_author ) ) . '</a>'
                                );
                                ?>
                            </span>
                        </li>
                        <li class="post-date meta-wrapper">
                            <span class="meta-icon">
                                <span class="screen-reader-text"><?php _e( 'Post date', 'mylibrary' ); ?></span>
                                <?php mylibrary_the_theme_svg( 'calendar' ); ?>
                            </span>
                            <span class="meta-text">
                                <?php echo date("Y年 m月 d日", strtotime($chapter->chapter_modified));?>
                            </span>
                        </li>
                        <li class="post-comment-link meta-wrapper">
                            <span class="meta-icon">
                                <span class="screen-reader-text"><?php _e( 'Categories', 'mylibrary' ); ?></span>
                                <?php mylibrary_the_theme_svg( 'folder' ); ?>
                            </span>

                            <span class="meta-text">
                                <?php echo mylibrary_word_count($chapter->chapter_content);?> 字 
                            </span>
                        </li>

                    </ul><!-- .post-meta -->

                </div><!-- .post-meta-wrapper -->


            </div><!-- .entry-header-inner -->

        </header>

        <div class="post-inner <?php echo is_page_template('templates/template-full-width.php') ? '' : 'thin'; ?> ">
            <div class="entry-content">
                <?php echo wpautop($chapter->chapter_content); ?>
            </div>
        </div>

        <nav class="pagination-single section-inner" aria-label="Post">
            <hr class="styled-separator is-style-wide" aria-hidden="true">
            <div class="pagination-single-inner">
                    <?php  if ($prev) { ?>
                    <a class="previous-post" href="<?php echo get_chapter_url($prev['id']); ?>">
                        <span class="arrow" aria-hidden="true">←</span>
                        <span class="title">
                            <span class="title-inner"><?=$prev['title']?></span>
                        </span>
                    </a>
                    <?php } ?>
                    <?php if ($next) { ?>
                    <a class="next-post" href="<?php echo get_chapter_url($next['id']) ?>">
                        <span class="arrow" aria-hidden="true">→</span>
                            <span class="title">
                                <span class="title-inner"><?=$next['title']?></span>
                            </span>
                    </a>
                    <?php } ?>
                    
            </div><!-- .pagination-single-inner -->
            <hr class="styled-separator is-style-wide" aria-hidden="true">
        </nav>


        <div class="entry-content">
            <style>
                .active a{
                    color: #000;
                }
            </style>

            <h2>章节目录</h2>
            <ul style="margin-bottom: 40px;">
            <?php
                foreach ($chapters as $c) {
                    if($chapter_id == $c->chapter_id){
                        echo "<li class=\"". ($chapter_id == $c->chapter_id ? "active" : '') ."\">{$c->chapter_title}</li>";
                    }else{
                        echo "<li><a href=\"".get_chapter_url($c->chapter_id)."\">{$c->chapter_title}</a></li>";
                    }
                }
                ?>
            </ul>
        </div>
    </article>
</main>


<?php get_footer(); ?>