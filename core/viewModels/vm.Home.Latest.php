<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/21 22:26
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class HomeLatestVM
 */
class HomeLatestVM extends BaseVM {

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
     * @param   int    $page   分页号
     * @return  static
     */
    public static function getInstance($page = 1) {
        $instance = new static(); // 因为不同分页共用该模型，不采用单例模式
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . get_called_class() . '_page' . $page;
        $instance->_page = max(1, $page);
        //$instance->_enableCache = false; // TODO Debug use
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        //$featured_catIds = array(tt_get_option('tt_home_featured_category_one'), tt_get_option('tt_home_featured_category_two'), tt_get_option('tt_home_featured_category_three'));

        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => get_option('posts_per_page', 10),
            'paged' => $this->_page,
            //'category__not_in' => $featured_catIds, // TODO: 第二页置顶分类隐藏了会仍然不显示这些分类的文章
            'has_password' => false,
            'ignore_sticky_posts' => true,
            'post__not_in' => get_option('sticky_posts'),
            'orderby' => 'date', // modified - 如果按最新编辑时间排序
            'order' => 'DESC'
        );

        $query = new WP_Query($args);
        $GLOBALS['wp_query'] = $query; // 取代主循环(query_posts只返回posts，为了获取其他有用数据，使用WP_Query) //TODO 缓存时无效

        $latest_posts = array();
        $pagination = array(
            'max_num_pages' => $query->max_num_pages,
            'current_page' => $this->_page,
            'base' => str_replace('999999999', '%#%', get_pagenum_link(999999999))
        );

        while ($query->have_posts()) : $query->the_post();
            $latest_post = array();
            global $post;
            $latest_post['ID'] = $post->ID;
            $latest_post['title'] = get_the_title($post);
            $latest_post['permalink'] = get_permalink($post);
            $latest_post['comment_count'] = $post->comment_count;
            $latest_post['excerpt'] = get_the_excerpt($post);
            $latest_post['category'] = get_the_category_list(' ', '', $post->ID);
            $latest_post['author'] = get_the_author();
            $latest_post['author_url'] = get_author_posts_url($post->post_author);
            $latest_post['time'] = get_post_time('F j, Y', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            $latest_post['datetime'] = get_the_time(DATE_W3C, $post);
            $latest_post['thumb'] = tt_get_thumb($post, 'medium');
            $latest_post['format'] = get_post_format($post) ? : 'standard';
            $latest_post['sticky_class'] = '';

            $latest_posts[] = $latest_post;
        endwhile;

        wp_reset_postdata();

        return (object)array(
            'pagination' => $pagination,
            'latest_posts' => $latest_posts
        );
    }
}