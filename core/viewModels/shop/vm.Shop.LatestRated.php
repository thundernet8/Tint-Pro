<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/21 04:07
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class ShopLatestRatedVM
 */
class ShopLatestRatedVM extends BaseVM {
    protected function __construct() {
        $this->_cacheUpdateFrequency = 'hourly';
        $this->_cacheInterval = 1800; // 缓存保留半小时
    }

    protected function getRealData() {
        // 检索置顶用于排除
        //$stickies = get_option('sticky_posts');

        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'has_password' => false,
            'showposts'	=> 5,
            'ignore_sticky_posts' => true,
            //'post__not_in' => $stickies,
            'orderby' => 'meta_value_num',
            'meta_key' => 'tt_latest_rated',
            'order'	=> 'desc'
        );

        $query = new WP_Query($args);

        $latest_rated_products = array();

        while ($query->have_posts()) : $query->the_post();
            $latest_rated_product = array();
            global $post;
            $latest_rated_product['title'] = get_the_title($post);
            $latest_rated_product['permalink'] = get_permalink($post);
            //$latest_rated_product['category'] = get_the_term_list($post->ID, 'product_category', '', ' · ', ''); //get_the_category_list(' · ', '', $post->ID);
            $latest_rated_product['thumb'] = tt_get_thumb($post, array(
                'width' => 100,
                'height' => 100,
                'str' => 'thumbnail'
            ));
            //$latest_rated_product['sales'] = get_post_meta($post->ID, 'tt_product_sales', true);
            //$latest_rated_product['currency'] = get_post_meta( $post->ID, 'tt_pay_currency', true) ? 'cash' : 'credit';
            //$latest_rated_product['price'] = $latest_rated_product['currency'] == 'cash' ? sprintf('%0.2f', get_post_meta($post->ID, 'tt_product_price', true)) : (int)get_post_meta($post->ID, 'tt_product_price', true);
            //$latest_rated_product['price_unit'] = $latest_rated_product['currency'] == 'cash' ? __('YUAN', 'tt') : __('CREDITS', 'tt');
            //$latest_rated_product['price_icon'] = !($latest_rated_product['price'] > 0) ? '' : $latest_rated_product['currency'] == 'cash' ? '<i class="tico tico-cny"></i>' : '<i class="tico tico-diamond"></i>';
            //$latest_rated_product['discount'] = tt_get_product_discount_array($post->ID); // array 第1项为普通折扣, 第2项为会员(月付)折扣, 第3项为会员(年付)折扣, 第4项为会员(永久)折扣
            // 打分
            $rating_raw = get_post_meta($post->ID, 'tt_post_ratings', true);
            $rating_arr = $rating_raw ? (array)maybe_unserialize($rating_raw) : array(); // array(rating value1, rating value2...)
            $rating_count = count($rating_arr);
            $rating_value = !$rating_count ? '0.0' : sprintf('%0.1f', array_sum($rating_arr)/$rating_count);
            $rating_percent = intval($rating_value*100/5);
            $latest_rated_product['rating'] = array(
                'count' => $rating_count,
                'value' => $rating_value,
                'percent' => $rating_percent
            );
            $latest_rated_products[] = $latest_rated_product;

        endwhile;

        wp_reset_postdata();

        return $latest_rated_products;
    }
}