<?php
/**
 * Copyright (c) 2014-2017, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.4
 * @package Tint
 * @author Zhiyan
 * @date 2017/02/09 13:16
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class MgUserVM
 */
class MgUserVM extends BaseVM {

    /**
     * @var int 用户ID
     */
    private $_userId;


    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $user_id  用户ID
     * @return  static
     */
    public static function getInstance($user_id = 0) {
        $instance = new static();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_user' . $user_id;
        $instance->_userId = $user_id;
        $instance->_enableCache = false; // 用户管理不使用缓存
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $user_data = get_userdata($this->_userId);
        if(!$user_data) return null;

        $user_info = array();
        $user_info['ID'] = $this->_userId;
        $user_info['display_name'] = $user_data->display_name;
        $user_info['nickname'] = $user_data->nickname; //get_user_meta($author->ID, 'nickname', true);
        $user_info['email'] = $user_data->user_email;
        $user_info['member_since'] = mysql2date('Y/m/d H:i:s', $user_data->user_registered);
        $user_info['member_days'] = max(1, round(( strtotime(date('Y-m-d')) - strtotime( $user_data->user_registered ) ) /3600/24));
        $user_info['site'] = $user_data->user_url;
        $user_info['description'] = $user_data->description;

        //$user_info['avatar'] = tt_get_avatar($user_data->ID, 'medium');

        $user_info['latest_login'] = $user_data->tt_latest_login ? mysql2date('Y/m/d H:i:s', $user_data->tt_latest_login) : 'N/A';

        // 会员信息
        $member = new Member($this->_userId);
        $member_info = array(
            'is_vip' => $member->is_vip(),
            'member_type' => $member->vip_type,
            'member_status' => tt_get_member_status_string($member->vip_type),
            'join_time' => $member->get_vip_join_time(),
            'end_time' => $member->get_vip_expire_time()
        );

        // 积分信息
        $credit_info = array(
            'credit_balance' => tt_get_user_credit($this->_userId),
            'credit_consumed' => tt_get_user_consumed_credit($this->_userId)
        );

        // 用户的近期订单
        $latest_orders = tt_get_user_orders($this->_userId, 10);
        $orders = array();
        if($latest_orders) {
            foreach ($latest_orders as $latest_order) {
                $order = array();
                $order['time'] = $latest_order->order_time;
                $order['title'] = $latest_order->product_name ? $latest_order->product_name : __('Combined Orders', 'tt');
                $order['mgUrl'] = tt_url_for('manage_order', $latest_order->id);
                $orders[] = $order;
            }
        }
        $user_info['latest_orders'] = $orders;

        $user_info = array_merge($user_info, $member_info, $credit_info);

        return (object)$user_info;
    }
}