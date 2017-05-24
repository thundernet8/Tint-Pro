<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/23 20:30
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class MeDraftsVM
 */
class MeDraftsVM extends BaseVM {

    /**
     * @var int 用户ID
     */
    private $_userId;

    /**
     * @var int 分页号
     */
    private $_page = 1;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $user_id 用户ID
     * @param   int    $page   分页号
     * @return  static
     */
    public static function getInstance($user_id = 0, $page = 1) {
        $instance = new static();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_page' . $page . '_user' . $user_id;
        $instance->_userId = $user_id;
        $instance->_page = max(1, $page);
        $instance->_enableCache = false; // 不使用缓存
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $posts_per_page = get_option('posts_per_page', 10);
        $args = array(
            'post_type' => 'post',
            'post_status' => 'draft,pending',
            'author' => $this->_userId,
            'posts_per_page' => $posts_per_page,
            'paged' => $this->_page,
//            'has_password' => false,
            'ignore_sticky_posts' => true,
            'orderby' => 'date', // modified - 如果按最新编辑时间排序
            'order' => 'DESC'
        );

        $query = new WP_Query($args);
        $query->is_home = false;
        $query->is_author = false;
        $GLOBALS['wp_query'] = $query; // 取代主循环(query_posts只返回posts，为了获取其他有用数据，使用WP_Query) //TODO 缓存时无效

        $draft_posts = array();
        $count = $query->found_posts;
        $max_pages = $query->max_num_pages; //ceil($count / $posts_per_page);
        $pagination_base = tt_url_for('my_drafts') . '/page/%#%';

        $author_url = get_author_posts_url($this->_userId);

        while ($query->have_posts()) : $query->the_post();
            $draft_post = array();
            global $post;
            $draft_post['ID'] = $post->ID;
            $draft_post['title'] = get_the_title($post);
            //$draft_post['permalink'] = get_permalink($post);
            //$draft_post['comment_count'] = $post->comment_count;
            $draft_post['excerpt'] = get_the_excerpt($post);
            $draft_post['category'] = get_the_category_list(' ', '', $post->ID);
            //$draft_post['author'] = get_the_author();
            $draft_post['author_url'] = $author_url;
            $draft_post['time'] = get_post_time('Y-m-d H:i:s', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            $draft_post['datetime'] = get_the_time(DATE_W3C, $post);
            $draft_post['thumb'] = tt_get_thumb($post, 'medium');
            $draft_post['format'] = get_post_format($post) ? : 'standard';

            $draft_post['edit_link'] = tt_url_for('edit_post', $post->ID);

            $draft_posts[] = $draft_post;
        endwhile;

        wp_reset_postdata();

        return (object)array(
            'count' => $count,
            'drafts' => $draft_posts,
            'max_pages' => $max_pages,
            'pagination_base' => $pagination_base,
            'prev_page' => str_replace('%#%', max(1, $this->_page - 1), $pagination_base),
            'next_page' => str_replace('%#%', min($max_pages, $this->_page + 1), $pagination_base)
        );
    }
}