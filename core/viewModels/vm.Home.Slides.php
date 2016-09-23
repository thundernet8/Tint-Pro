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
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

class SlideVM extends BaseVM {
    protected function __construct() {

    }

    protected function configInstance() {
        if($cache = $this->getDataFromCache()) {
            $this->modelData = $cache;
        }else{
            $data = $this->getRealData();
            $this->setDataToCache($data);
            $this->modelData = $data;
        }
    }

    protected function getDataFromCache() {
        $transient = get_transient($this->_cacheKey);
        if(!$transient) {
            return false;
        }

        $cacheObj = (object)maybe_unserialize($transient);
        $this->cacheTime = $cacheObj->cacheTime;
        $this->isCache = true;

        return (object)$cacheObj->data;
    }

    protected function setDataToCache($data) {
        if(!$data) {
            return;
        }
        $cacheTime = current_time('mysql');

        $store = maybe_serialize(array(
            'data' => $data,
            'cacheTime' => $cacheTime
        ));
        set_transient($this->_cacheKey, $store, 3600);
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
            $slide_post['time'] = get_post_time('F j, Y', false, $post, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
            $slide_post['datetime'] = get_the_time(DATE_W3C, $post);
            $slide_post['thumb'] = tt_get_thumb($post, 'large');

            $slide_posts[] = $slide_post;
        endwhile;

        return $slide_posts;
    }
}