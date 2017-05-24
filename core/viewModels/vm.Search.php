<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/24 23:28
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class SearchVM
 */
class SearchVM extends BaseVM {

    /**
     * @var int 分页号
     */
    private $_page = 1;

    /**
     * @var int  搜索关键词
     */
    private $_search;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $page   分页号
     * @param   string $search 搜索关键词
     * @return  static
     */
    public static function getInstance($page = 1, $search = '') {
        $instance = new static();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_page' . $page . '_search' . $search;
        $instance->_page = max(1, $page);
        $instance->_search = $search;
        //$instance->_enableCache = false; //Debug use
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $posts_per_page = get_option('posts_per_page', 10);
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page,
            's' => $this->_search,
            'paged' => $this->_page,
            'has_password' => false,
            'ignore_sticky_posts' => true,
            'orderby' => 'date', // modified - 如果按最新编辑时间排序
            'order' => 'DESC'
        );


        $query = new WP_Query($args);
        $GLOBALS['wp_query'] = $query; // 取代主循环(query_posts只返回posts，为了获取其他有用数据，使用WP_Query) //TODO 缓存时无效

        $search_posts = array();
//        $pagination = array(
//            'max_num_pages' => $query->max_num_pages,
//            'current_page' => $this->_page,
//            'base' => str_replace('999999999', '%#%', get_pagenum_link(999999999))
//        );
        $count = $query->found_posts;
        $max_pages = $query->max_num_pages;
        $pagination_base = str_replace('999999999', '%#%', get_pagenum_link(999999999));

        while ($query->have_posts()) : $query->the_post();
            $search_post = array();
            global $post;
            $search_post['ID'] = $post->ID;
            $search_post['title'] = get_the_title($post);
            $search_post['permalink'] = get_permalink($post);
            $search_post['comment_count'] = $post->comment_count;
            $search_post['excerpt'] = get_the_excerpt($post);
            $search_post['category'] = get_the_category_list(' ', '', $post->ID);
            $search_post['author'] = get_the_author();
            $search_post['author_url'] = get_author_posts_url($post->post_author);
            $search_post['time'] = get_post_time('F j, Y', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            $search_post['datetime'] = get_the_time(DATE_W3C, $post);
            $search_post['thumb'] = tt_get_thumb($post, 'medium');
            $search_post['format'] = get_post_format($post) ? : 'standard';

            $search_posts[] = $search_post;
        endwhile;

        wp_reset_postdata();

        return (object)array(
            'count' => $count,
            'results' => $search_posts,
            'max_pages' => $max_pages,
            'pagination_base' => $pagination_base,
            'prev_page' => str_replace('%#%', max(1, $this->_page - 1), $pagination_base),
            'next_page' => str_replace('%#%', min($max_pages, $this->_page + 1), $pagination_base)
        );
    }
}