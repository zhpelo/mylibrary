# 传硕公版书 一个可以发布多章节书籍内容的WordPress主题


## demo
展示网站： https://www.7sbook.com

![alt 这是演示截图](./demo1.png)
![alt 这是演示截图](./demo2.png)
![alt 这是演示截图](./demo3.png)

新增了章节内容表
```
  CREATE TABLE `wp_chapters` (
  `chapter_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `chapter_title` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `chapter_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `chapter_content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `chapter_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `chapter_order` int(8) NOT NULL DEFAULT '0',
  `chapter_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `chapter_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `chapter_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `chapter_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `chapter_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'publish',
  PRIMARY KEY (`chapter_id`),
  KEY `chapter_parent` (`chapter_parent`),
  KEY `chapter_status` (`chapter_status`),
  KEY `chapter_author` (`chapter_author`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
```