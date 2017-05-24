<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/25 23:40
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class HotHitPostsVM
 */
class HotHitPostsVM extends BaseVM {

    /**
     * @var int 文章数量
     */
    private $_count = 5;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $count   文章数量
     * @return  static
     */
    public static function getInstance($count = 5) {
        $posts_count = min(5, absint($count));
        $instance = new static(); // 因为配置不同文章数量共用该模型，不采用单例模式 //TODO 小工具参数更新时可清理缓存，可采用单例
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_count' . $posts_count;
        $instance->_count = $posts_count;;
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
//        // 排除分类
//        $uncat = tt_filter_of_multicheck_option(tt_get_option('tt_home_undisplay_cats', array()));
        // 检索置顶用于排除
        $stickies = get_option('sticky_posts');

        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'has_password' => false,
            'showposts'	=> $this->_count,
            'ignore_sticky_posts' => true,
            'post__not_in' => $stickies,
            'orderby' => 'meta_value_num',
            'meta_key' => 'views',
            'order'	=> 'desc'
        );

        $query = new WP_Query($args);

        $hothit_posts = array();

        while ($query->have_posts()) : $query->the_post();
            $hothit_post = array();
            global $post;
            $hothit_post['ID'] = $post->ID;
            $hothit_post['title'] = get_the_title($post);
            $hothit_post['permalink'] = get_permalink($post);
            $hothit_post['comment_count'] = $post->comment_count;
            //$hothit_post['category'] = get_the_category_list(' · ', '', $post->ID);
            //$hothit_post['author'] = get_the_author(); //TODO add link
            $hothit_post['time'] = get_post_time('Y-m-d H:i', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            $hothit_post['datetime'] = get_the_time(DATE_W3C, $post);
            $hothit_post['timediff'] = Utils::getTimeDiffString($hothit_post['time']);
            $hothit_post['thumb'] = tt_get_thumb($post, array(
                'width' => 200,
                'height' => 150,
                'str' => 'thumbnail'
            ));

            // 点击数
            $hothit_post['views'] = absint(get_post_meta( $post->ID, 'views', true ));

            $hothit_posts[] = $hothit_post;
        endwhile;

        wp_reset_postdata();

        return $hothit_posts;
    }
}