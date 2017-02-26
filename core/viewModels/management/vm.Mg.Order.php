<?php
/**
 * Copyright (c) 2014-2017, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/15 18:30
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class MgOrderVM
 */
class MgOrderVM extends BaseVM {

    /**
     * @var int 订单序号
     */
    private $_orderSeq;


    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $order_seq  订单序号
     * @return  static
     */
    public static function getInstance($order_seq = 0) {
        $instance = new static();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_seq' . $order_seq;
        $instance->_orderSeq = $order_seq;
        $instance->_enableCache = false; // 订单详情不使用缓存
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $order = tt_get_order_by_sequence($this->_orderSeq);
        $is_combine_order = $order->parent_id == -1;
        $pay_content = null;
        $sub_orders = array();
        if($is_combine_order) {
            $sub_orders = tt_get_sub_orders($order->id);
        }
//        if(!$is_combine_order && $order->product_id > 0){
//            $pay_content = tt_get_product_pay_content($order->product_id, false);
//        }

        $order_status = $order->order_status;
        if(!$is_combine_order && $order->parent_id > 0){
            // 子订单的支付状态由父级订单决定
            $parent_order = tt_get_order_by_sequence($order->parent_id);
            $order_status = $parent_order ? $parent_order->order_status : $order_status;
        }
        $address = $order->address_id ? tt_get_address($order->address_id) : null;

        return (object)array(
            'order' => $order,
            'order_status_text' => tt_get_order_status_text($order_status),
            'pay_method' => $order->order_currency == 'credit' ? __('Credit Payment', 'tt') : __('Cash Payment', 'tt'),
            'pay_amount' => $order->order_currency == 'credit' ? sprintf(__('%d Credits', 'tt'), $order->order_total_price) : sprintf(__('%0.2f YUAN', 'tt'), $order->order_total_price),
            'pay_content' => $pay_content,
            'is_combined' => $is_combine_order,
            'sub_orders' => $sub_orders,
            'address' => $address
        );
    }
}