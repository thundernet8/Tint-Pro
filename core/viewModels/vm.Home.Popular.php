<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/21 21:50
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class PopularVM
 */
class PopularVM extends BaseVM {
    protected function __construct() {
        $this->_cacheUpdateFrequency = 'hourly';
        $this->_cacheInterval = 1800; // 缓存保留半小时
    }

    protected function getRealData() {
        // 排除分类
        $uncat = tt_filter_of_multicheck_option(tt_get_option('tt_home_undisplay_cats', array()));
        // 检索置顶用于排除
        $stickies = get_option('sticky_posts');

        $algorithm = tt_get_option('tt_home_popular_algorithm', 'latest_reviewed'); // 1.most_viewed 2.most_reviewed 3.latest_reviewed
        $orderby = 'meta_value_num';
        $meta_key = 'tt_latest_reviewed'; //TODO 评论时加上postmeta

        switch ($algorithm) {
            case 'most_viewed':
                $meta_key = 'views'; // 依赖wp-postviews插件，或者tt_post_views?
                break;
            case 'most_reviewed':
                $orderby = 'comment_count';
                $meta_key = '';
                break;
        }
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'has_password' => false,
            'showposts'	=> 4,
            'category__not_in'	=> $uncat,
            'ignore_sticky_posts' => true,
            'post__not_in' => $stickies,
            'orderby' => ''.$orderby.'',
            'meta_key' => ''.$meta_key.'',
            'order'	=> 'desc'
        );

        $query = new WP_Query($args);

        $popular_posts = array();

        while ($query->have_posts()) : $query->the_post();
            $popular_post = array();
            global $post;
            $popular_post['title'] = get_the_title($post);
            $popular_post['permalink'] = get_permalink($post);
            $popular_post['comment_count'] = $post->comment_count;
            //$popular_post['category'] = get_the_category_list(' · ', '', $post->ID);
            //$popular_post['author'] = get_the_author(); //TODO add link
            $popular_post['time'] = get_post_time('F j, Y', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            //$popular_post['datetime'] = get_the_time(DATE_W3C, $post);
            $popular_post['thumb'] = tt_get_thumb($post, array(
                'width' => 200,
                'height' => 150,
                'str' => 'thumbnail'
            ));

            $popular_posts[] = $popular_post;
        endwhile;

        wp_reset_postdata();

        return $popular_posts;
    }
}