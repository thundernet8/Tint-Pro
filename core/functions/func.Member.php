<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/04 22:57
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?

/**
 * 获取用户开通会员订单记录
 *
 * @since 2.0.0
 * @param int $user_id
 * @return array|null|object
 */
function get_user_member_orders($user_id = 0){
    global $wpdb;
    $user_id = $user_id ? : get_current_user_id();
    $prefix = $wpdb->prefix;
    $table = $prefix . 'tt_orders';
    $vip_orders=$wpdb->get_results(sprintf("select * from %s where `user_id`=%d and `product_id` in (-1,-2,-3)", $table, $user_id));
    return $vip_orders;
}


/**
 * 获取会员类型描述文字
 *
 * @since 2.0.0
 * @param $code
 * @return string|void
 */
function tt_get_member_type_string($code){
    switch($code){
        case 3:
            $type = __('Permanent Membership', 'tt');
            break;
        case 2:
            $type = __('Annual Membership', 'tt');
            break;
        case 1:
            $type = __('Monthly Membership', 'tt');
            break;
        default:
            $type = __('None Membership', 'tt');
    }
    return $type;
}
