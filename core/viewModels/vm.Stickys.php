<?php
/**
 * Copyright (c) 2014-2017, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/02/18 20:51
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class StickysVM
 */
class StickysVM extends BaseVM {

    /**
     * @var int 限制文章数量, 0即为不限制
     */
    private $_limit = 0;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $limit   限制文章数量
     * @return  static
     */
    public static function getInstance($limit = 0) {
        $instance = new static();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . get_called_class() . '_limit' . $limit;
        $instance->_limit = max(0, $limit);
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $stickys = get_option('sticky_posts');
        $stickys_num = count($stickys);

        if($stickys_num < 1) {
            return (object)array(
                'count' => 0,
                'sticky_posts' => array()
            );
        }

        $args = array(
            'post__in' => $stickys,
            'post_status' => 'publish',
            'has_password' => false,
            'orderby' => 'date', // modified - 如果按最新编辑时间排序
            'order' => 'DESC'
        );
        if($this->_limit > 0) {
            $args['showposts'] = $this->_limit;
        }

        $query = new WP_Query($args);
        $GLOBALS['wp_query'] = $query; // 取代主循环(query_posts只返回posts，为了获取其他有用数据，使用WP_Query) //TODO 缓存时无效

        $sticky_posts = array();

        while ($query->have_posts()) : $query->the_post();
            $sticky_post = array();
            global $post;
            $sticky_post['ID'] = $post->ID;
            $sticky_post['title'] = get_the_title($post);
            $sticky_post['permalink'] = get_permalink($post);
            $sticky_post['comment_count'] = $post->comment_count;
            $sticky_post['excerpt'] = get_the_excerpt($post);
            $sticky_post['category'] = get_the_category_list(' ', '', $post->ID);
            $sticky_post['author'] = get_the_author();
            $sticky_post['author_url'] = get_author_posts_url($post->post_author);
            $sticky_post['time'] = get_post_time('F j, Y', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            $sticky_post['datetime'] = get_the_time(DATE_W3C, $post);
            $sticky_post['thumb'] = tt_get_thumb($post, 'medium');
            $sticky_post['format'] = get_post_format($post) ? : 'standard';
            $sticky_post['sticky_class'] = 'sticky';

            $sticky_posts[] = $sticky_post;
        endwhile;

        wp_reset_postdata();

        return (object)array(
            'count' => count($sticky_posts),
            'sticky_posts' => $sticky_posts
        );
    }
}