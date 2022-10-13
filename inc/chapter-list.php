<div class="wrap">
    <h1 class="wp-heading-inline">全部章节</h1>
    <a href="/wp-admin/post-new.php" class="page-title-action">写文章</a>
    <h2 class="screen-reader-text">筛选文章列表</h2>

    <table class="wp-list-table widefat fixed striped table-view-list posts">
        <thead>
            <tr>
                
                <th class="manage-column column-author">
                    所属书籍
                </th>
                <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                    <a href="#">
                        <span>标题</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>

                <th scope="col" id="author" class="manage-column column-author">作者</th>
               

            
                <th scope="col" id="date" class="manage-column column-date sortable asc">
                    <a href="#">
                        <span>日期</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>

            </tr>
        </thead>

        <tbody id="the-list">
        <?php foreach($chapter_list as $chapter){ ?>
            <tr class="iedit author-self level-0 type-post status-publish format-standard hentry">

            <td class="author column-author" data-colname="书籍">
                    <a href="/wp-admin/post.php?post=<?=$chapter->post_id;?>&action=edit">
                        《<?=get_post($chapter->post_id)->post_title;?>》
                    </a>
                </td>

                <td class="title column-title has-row-actions column-primary page-title" data-colname="标题">
                    
                    <strong>
                        <a class="row-title" href="/wp-admin/edit.php?post_type=book&page=manage-chapters&post_id=<?=$chapter->post_id;?>&chapter_id=<?=$chapter->chapter_id;?>" ><?=$chapter->chapter_title;?></a>
                    </strong>
                </td>
                <td class="author column-author" data-colname="作者">
                    <a href="edit.php?post_type=post&amp;author=1">小叮当</a>
                </td>

                <td class="date column-date" data-colname="日期">已发布<br>2022-08-25 下午1:08</td>
            </tr>
        <?php } ?>
        </tbody>

        <tfoot>
            <tr>
                
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                    <a href="http://localhost:8000/wp-admin/edit.php?orderby=title&amp;order=asc">
                        <span>标题</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>

                <th scope="col" class="manage-column column-author">作者</th>
                
                <th scope="col" class="manage-column column-date sortable asc">
                    <a href="#">
                        <span>日期</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
            </tr>
        </tfoot>

    </table>
</div>