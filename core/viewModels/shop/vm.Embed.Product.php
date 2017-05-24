<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/17 21:35
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class EmbedProductVM
 */
class EmbedProductVM extends BaseVM {
    /**
     * @var int 商品ID
     */
    private $_productId = 0;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $product_id   商品ID
     * @return  static
     */
    public static function getInstance($product_id = 0) {
        $instance = new static();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_product' . $product_id;
        $instance->_productId = absint($product_id);
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $product = get_post($this->_productId);

        $data = array();
        // 基本信息
        if($product && $product->post_type == 'product' && $product->post_status == 'publish'){
            $pay_currency = get_post_meta( $product->ID, 'tt_pay_currency', true) ? 'cash' : 'credit';
            $product_price = $pay_currency == 'cash' ? sprintf('%0.2f', get_post_meta($product->ID, 'tt_product_price', true)) : (int)get_post_meta($product->ID, 'tt_product_price', true);
            $rating_raw = get_post_meta($product->ID, 'tt_post_ratings', true);
            $rating_arr = $rating_raw ? (array)maybe_unserialize($rating_raw) : array(); // array(rating value1, rating value2...)
            $rating_count = count($rating_arr);
            $rating_value = !$rating_count ? '0.0' : sprintf('%0.1f', array_sum($rating_arr)/$rating_count);
            $rating_percent = intval($rating_value*100/5);
            $rating = array(
                'count' => $rating_count,
                'value' => $rating_value,
                'percent' => $rating_percent
            );
            $data = array(
                'product_id' => $product->ID,
                'product_name' => $product->post_title,
                'product_description' => get_the_excerpt($product),
                'product_link' => get_permalink($product),
                'product_thumb' => tt_get_thumb($product, array('width' => 100, 'height' => 100, 'str' => 'thumbnail')),
                'pay_currency' => $pay_currency,
                'product_price' => $product_price,
                'price_unit' => $pay_currency == 'cash' ? __('YUAN', 'tt') : __('CREDITS', 'tt'),
                'price_icon' => !($product_price > 0) ? '' : $pay_currency == 'cash' ? '<i class="tico tico-cny"></i>' : '<i class="tico tico-diamond"></i>',
                'product_discount' => tt_get_product_discount_array($product->ID),
                'product_sales' => get_post_meta($product->ID, 'tt_product_sales', true),
                'product_views' => absint(get_post_meta( $product->ID, 'views', true )),
                'product_rating' => $rating
            );
        }

        return (object)$data;
    }
}