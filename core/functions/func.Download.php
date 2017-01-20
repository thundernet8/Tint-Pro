<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/11 15:13
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php

/**
 * 检查用户是否购买了文章内付费资源
 *
 * @since 2.0.0
 * @param $post_id
 * @param $resource_seq
 * @return bool
 */
function tt_check_bought_post_resources($post_id, $resource_seq) {
    $user_id = get_current_user_id();
    if(!$user_id) {
        return false;
    }

    $user_bought = get_user_meta($user_id, 'tt_bought_posts', true);
    if(empty($user_bought)){
        return false;
    }
    $user_bought = maybe_unserialize($user_bought);
    if(!isset($user_bought['p_' . $post_id])) {
        return false;
    }

    $post_bought_resources = $user_bought['p_' . $post_id];
    if(isset($post_bought_resources[$resource_seq]) && $post_bought_resources[$resource_seq]) {
        return true;
    }

    return false;
}


/**
 * 购买文章内容资源
 *
 * @since 2.0.0
 * @param $post_id
 * @param $resource_seq
 * @return WP_Error|array
 */
function tt_bought_post_resource($post_id, $resource_seq) {
    $user = wp_get_current_user();
    $user_id = $user->ID;
    if(!$user_id) {
        return new WP_Error('user_not_signin', __('You must sign in to continue your purchase', 'tt'), array('status' => 401));
    }

    //检查文章资源是否存在
    $post_resources = explode(',', trim(get_post_meta($post_id, 'tt_sale_dl', true)));
    if(!isset($post_resources[$resource_seq - 1])) {
        return new WP_Error('post_resource_not_exist', __('The resource you willing to buy is not existed', 'tt'), array('status' => 404));
    }
    $the_post_resource = explode('|', $post_resources[$resource_seq - 1]);
    $price = isset($the_post_resource[2]) ? absint($the_post_resource[2]) : 1;
    $resource_name = $the_post_resource[0];
    $resource_link = $the_post_resource[1];
    $resource_pass = isset($the_post_resource[3]) ? trim($the_post_resource[3]) : __('None', 'tt');

    //检查是否已购买
    if(tt_check_bought_post_resources($post_id, $resource_seq)) {
        return new WP_Error('post_resource_bought', __('You have bought the resource yet, do not repeat a purchase', 'tt'), array('status' => 200));
    }

    // 先计算VIP价格优惠
    $member = new Member($user);
    $vip_price = $price;
    $vip_type = $member->vip_type;
    switch ($vip_type) {
        case Member::MONTHLY_VIP:
            $vip_price = absint(tt_get_option('tt_monthly_vip_discount', 100) * $price / 100);
            break;
        case Member::ANNUAL_VIP:
            $vip_price = absint(tt_get_option('tt_annual_vip_discount', 90) * $price / 100);
            break;
        case Member::PERMANENT_VIP:
            $vip_price = absint(tt_get_option('tt_permanent_vip_discount', 80) * $price / 100);
            break;
    }
    $vip_string = tt_get_member_type_string($vip_type);

    //检查用户积分是否足够
    $payment = tt_credit_pay($vip_price, $resource_name, true);
    if($payment instanceof WP_Error) {
        return $payment;
    }

    $user_bought = get_user_meta($user_id, 'tt_bought_posts', true);
    if(empty($user_bought)){
        $user_bought = array(
            'p_' . $post_id => array($resource_seq => true)
        );
    }else{
        $user_bought = maybe_unserialize($user_bought);
        if(!isset($user_bought['p_' . $post_id])) {
            $user_bought['p_' . $post_id] = array($resource_seq => true);
        }else{
            $buy_seqs = $user_bought['p_' . $post_id];
            $buy_seqs[$resource_seq] = true;
            $user_bought['p_' . $post_id] = $buy_seqs;
        }
    }
    $save = maybe_serialize($user_bought);
    $update = update_user_meta($user_id, 'tt_bought_posts', $save); //Meta ID if the key didn't exist; true on successful update; false on failure or if $meta_value is the same as the existing meta value in the database.
    if(!$update){ //TODO 返还扣除的积分
        return new WP_Error('post_resource_bought_failure', __('Failed to buy the resource, or maybe you have bought before', 'tt'), array('status' => 500));
    }

    // 发送邮件
    $subject = __('Payment for the resource finished successfully', 'tt');
    $balance = get_user_meta($user_id, 'tt_credits', true);
    $args = array(
        'adminEmail' => get_option('admin_email'),
        'resourceName' => $resource_name,
        'resourceLink' => $resource_link,
        'resourcePass' => $resource_pass,
        'spentCredits' => $price,
        'creditsBalance' => $balance
    );
    tt_async_mail('', $user->user_email, $subject, $args, 'buy-resource');

    if($price - $vip_price > 0) {
        $text = sprintf(__('消费积分: %1$d (%2$s优惠, 原价%3$d)<br>当前积分余额: %2$d', 'tt'), $vip_price, $vip_string, $price, $balance);
        $cost = $vip_price;
    }else{
        $text = sprintf(__('消费积分: %1$d<br>当前积分余额: %2$d', 'tt'), $price, $balance);
        $cost = $price;
    }
    return array(
        'cost' => $cost,
        'text' => $text,
        'vip_str' => $vip_string,
        'balance' => $balance
    );
}