<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/21 21:39
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class SlideVM
 */
class SlideVM extends BaseVM {
    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24;
    }

    protected function getRealData() {
        $slide_postIds = explode(',', tt_get_option('tt_home_slides'));

        if(!count($slide_postIds)) {
            return null;
        }

        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'post__in' => $slide_postIds,
            'has_password' => false,
            'ignore_sticky_posts' => true,
            'orderby' => 'none'
        );

        $query = new WP_Query($args);

        $slide_posts = array();

        while ($query->have_posts()) : $query->the_post();
            $slide_post = array();
            global $post;
            $slide_post['title'] = get_the_title($post);
            $slide_post['permalink'] = get_permalink($post);
            $slide_post['comment_count'] = $post->comment_count;
            $slide_post['category'] = get_the_category_list(' · ', '', $post->ID);
            $slide_post['author'] = get_the_author(); //TODO add link
            $slide_post['author_url'] = home_url('/@' . $slide_post['author']);
            $slide_post['time'] = get_post_time('F j, Y', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            $slide_post['datetime'] = get_the_time(DATE_W3C, $post);
            $slide_post['thumb'] = tt_get_thumb($post, array(
                'width' => 750,
                'height' => 375,
                'str' => 'large'
            ));

            $slide_posts[] = $slide_post;
        endwhile;

        wp_reset_postdata();

        return $slide_posts;
    }
}