<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/07 21:13
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class UCLatestVM
 */
class UCLatestVM extends BaseVM {

    /**
     * @var int 分页号
     */
    private $_page = 1;


    /**
     * @var int 作者ID
     */
    private $_authorId;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $page   分页号
     * @param   int    $author_id   作者ID
     * @return  static
     */
    public static function getInstance($page = 1, $author_id = 0) {
        $instance = new static(); // 因为不同分页不同作者共用该模型，不采用单例模式
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_author' . $author_id . '_page' . $page;
        $instance->_page = max(1, $page);
        $instance->_authorId = $author_id;
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => get_option('posts_per_page', 10),
            'paged' => $this->_page,
            'author' => $this->_authorId,
            'has_password' => false,
            'ignore_sticky_posts' => false,
            'orderby' => 'date', // modified - 如果按最新编辑时间排序
            'order' => 'DESC'
        );

        $query = new WP_Query($args);
        //$GLOBALS['wp_query'] = $query; // 取代主循环(query_posts只返回posts，为了获取其他有用数据，使用WP_Query)

        $uc_latest_posts = array();
        $pagination = array(
            'max_num_pages' => $query->max_num_pages,
            'current_page' => $this->_page,
            'base' => str_replace('999999999', '%#%', get_pagenum_link(999999999))
        );

        while ($query->have_posts()) : $query->the_post();
            $uc_latest_post = array();
            global $post;
            $uc_latest_post['ID'] = $post->ID;
            $uc_latest_post['title'] = get_the_title($post);
            $uc_latest_post['permalink'] = get_permalink($post);
            $uc_latest_post['comment_count'] = $post->comment_count;
            $uc_latest_post['excerpt'] = get_the_excerpt($post);
            $uc_latest_post['category'] = get_the_category_list(' ', '', $post->ID);
            $uc_latest_post['author'] = get_the_author();
            $uc_latest_post['author_url'] = get_author_posts_url(get_the_author_meta('ID'));
            $uc_latest_post['time'] = get_post_time('F j, Y', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            $uc_latest_post['datetime'] = get_the_time(DATE_W3C, $post);
            $uc_latest_post['thumb'] = tt_get_thumb($post, 'medium');
            $uc_latest_post['format'] = get_post_format($post) ? : 'standard';

            $uc_latest_posts[] = $uc_latest_post;
        endwhile;

        wp_reset_postdata();

        return (object)array(
            'pagination' => $pagination,
            'uc_latest_posts' => $uc_latest_posts
        );
    }
}