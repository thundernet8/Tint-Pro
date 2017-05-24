<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/21 04:54
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class ShopViewedHistoryVM
 */
class ShopViewedHistoryVM extends BaseVM {

    /**
     * @var int 用户ID
     */
    private $_uid;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'hourly';
        $this->_cacheInterval = 1800; // 缓存保留半小时
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $user_id   用户ID
     * @return  static
     */
    public static function getInstance($user_id = 0) {
        $instance = new static();
        if($user_id) {
            $key = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_user' . $user_id;
        }elseif(isset($_COOKIE["tt_view_product_history"]) && !empty($_COOKIE["tt_view_product_history"])){
            $key = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_cookie_value' . htmlspecialchars($_COOKIE["tt_view_product_history"]);
        }else{
            $key = '';
            $instance->_enableCache = false;
        }
        $instance->_cacheKey = $key;
        $instance->_uid = absint($user_id);
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        if(!($this->_uid) && (!isset($_COOKIE["tt_view_product_history"]) || empty($_COOKIE["tt_view_product_history"]))) {
            return array();
        }

        $ids_str = $this->_uid ? get_user_meta($this->_uid, 'tt_view_product_history', true) : htmlspecialchars($_COOKIE["tt_view_product_history"]);
        $ids = $ids_str ? explode('_', $ids_str) : false;
        if(!$ids || count($ids) < 1) {
            return array();
        }
        $ids = array_unique($ids);
        $ids = array_splice($ids, 0, 5);

        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'has_password' => false,
            'post__in' => $ids
            //'showposts'	=> 5,
            //'ignore_sticky_posts' => true,
            //'post__not_in' => $stickies,
            //'orderby' => '',
            //'order'	=> 'desc'
        );

        $query = new WP_Query($args);

        $latest_view_products = array();

        while ($query->have_posts()) : $query->the_post();
            $latest_view_product = array();
            global $post;
            $latest_view_product['title'] = get_the_title($post);
            $latest_view_product['permalink'] = get_permalink($post);
            //$latest_view_product['category'] = get_the_term_list($post->ID, 'product_category', '', ' · ', ''); //get_the_category_list(' · ', '', $post->ID);
            $latest_view_product['thumb'] = tt_get_thumb($post, array(
                'width' => 100,
                'height' => 100,
                'str' => 'thumbnail'
            ));
            //$latest_view_product['sales'] = get_post_meta($post->ID, 'tt_product_sales', true);
            $latest_view_product['currency'] = get_post_meta( $post->ID, 'tt_pay_currency', true) ? 'cash' : 'credit';
            $latest_view_product['price'] = $latest_view_product['currency'] == 'cash' ? sprintf('%0.2f', get_post_meta($post->ID, 'tt_product_price', true)) : (int)get_post_meta($post->ID, 'tt_product_price', true);
            $latest_view_product['price_unit'] = $latest_view_product['currency'] == 'cash' ? __('YUAN', 'tt') : __('CREDITS', 'tt');
            $latest_view_product['price_icon'] = !($latest_view_product['price'] > 0) ? '' : $latest_view_product['currency'] == 'cash' ? '<i class="tico tico-cny"></i>' : '<i class="tico tico-diamond"></i>';
            $latest_view_product['discount'] = tt_get_product_discount_array($post->ID); // array 第1项为普通折扣, 第2项为会员(月付)折扣, 第3项为会员(年付)折扣, 第4项为会员(永久)折扣
            // 打分
//            $rating_raw = get_post_meta($post->ID, 'tt_post_ratings', true);
//            $rating_arr = $rating_raw ? (array)maybe_unserialize($rating_raw) : array(); // array(rating value1, rating value2...)
//            $rating_count = count($rating_arr);
//            $rating_value = !$rating_count ? '0.0' : sprintf('%0.1f', array_sum($rating_arr)/$rating_count);
//            $rating_percent = intval($rating_value*100/5);
//            $latest_view_product['rating'] = array(
//                'count' => $rating_count,
//                'value' => $rating_value,
//                'percent' => $rating_percent
//            );
            $latest_view_products[] = $latest_view_product;

        endwhile;

        wp_reset_postdata();

        return $latest_view_products;
    }
}