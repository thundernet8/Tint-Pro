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
 * @param int $limit
 * @param int $offset
 * @return array|null|object
 */
function tt_get_user_member_orders($user_id = 0, $limit = 20, $offset = 0){
    global $wpdb;
    $user_id = $user_id ? : get_current_user_id();
    $prefix = $wpdb->prefix;
    $table = $prefix . 'tt_orders';
    $vip_orders=$wpdb->get_results(sprintf("SELECT * FROM %s WHERE `user_id`=%d AND `product_id` IN (-1,-2,-3) ORDER BY `id` DESC LIMIT %d OFFSET %d", $table, $user_id, $limit, $offset));
    return $vip_orders;
}


/**
 * 统计用户会员订单数量
 *
 * @since 2.0.0
 * @param $user_id
 * @return int
 */
function tt_count_user_member_orders($user_id){
    global $wpdb;
    $user_id = $user_id ? : get_current_user_id();
    $prefix = $wpdb->prefix;
    $table = $prefix . 'tt_orders';
    $count=$wpdb->get_var(sprintf("SELECT COUNT(*) FROM %s WHERE `user_id`=%d AND `product_id` IN (-1,-2,-3)", $table, $user_id));
    return (int)$count;
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
        case Member::PERMANENT_VIP:
            $type = __('Permanent Membership', 'tt');
            break;
        case Member::ANNUAL_VIP:
            $type = __('Annual Membership', 'tt');
            break;
        case Member::MONTHLY_VIP:
            $type = __('Monthly Membership', 'tt');
            break;
        case Member::EXPIRED_VIP:
            $type = __('Expired Membership', 'tt');
            break;
        default:
            $type = __('None Membership', 'tt');
    }
    return $type;
}


/**
 * 获取用户会员状态文字(有效性)
 *
 * @since 2.0.0
 * @param $code
 * @return string|void
 */
function tt_get_member_status_string($code) {
    switch($code){
        case Member::PERMANENT_VIP:
        case Member::ANNUAL_VIP:
        case Member::MONTHLY_VIP:
            return __('In Effective', 'tt');
            break;
        case Member::EXPIRED_VIP:
            return __('Expired', 'tt');
            break;
        default:
            return __('N/A', 'tt');
    }
}


/**
 * 根据会员ID获取会员记录
 *
 * @since 2.0.0
 * @param $id
 * @return array|null|object|void
 */
function tt_get_member($id){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $members_table = $prefix . 'tt_members';
    $row = $wpdb->get_row(sprintf("SELECT * FROM $members_table WHERE `id`=%d", $id));
    return $row;
}


/**
 * 根据用户ID获取会员记录
 *
 * @since 2.0.0
 * @param $user_id
 * @return array|null|object|void
 */
function tt_get_member_row($user_id){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $members_table = $prefix . 'tt_members';
    $row = $wpdb->get_row(sprintf("SELECT * FROM $members_table WHERE `user_id`=%d", $user_id));
    return $row;
}


/**
 * 添加会员记录(如果已存在记录则更新)
 *
 * @since 2.0.0
 * @param $user_id
 * @param $vip_type
 * @param $start_time
 * @param $end_time
 * @param bool $admin_handle 是否管理员手动操作
 * @return bool|int
 */
function tt_add_or_update_member($user_id, $vip_type, $start_time = 0, $end_time = 0, $admin_handle = false){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $members_table = $prefix . 'tt_members';

    if(!in_array($vip_type, array(Member::NORMAL_MEMBER, Member::MONTHLY_VIP, Member::ANNUAL_VIP, Member::PERMANENT_VIP))){
        $vip_type = Member::NORMAL_MEMBER;
    }
    $duration = 0;
    switch ($vip_type){
        case Member::PERMANENT_VIP:
            $duration = Member::PERMANENT_VIP_PERIOD;
            break;
        case Member::ANNUAL_VIP:
            $duration = Member::ANNUAL_VIP_PERIOD;
            break;
        case Member::MONTHLY_VIP:
            $duration = Member::MONTHLY_VIP_PERIOD;
            break;
    }

    if(!$start_time) {
        $start_time = (int)current_time('timestamp');
    }elseif(is_string($start_time)){
        $start_time = strtotime($start_time);
    }

    if(is_string($end_time)){
        $end_time = strtotime($end_time);
    }
    $now = time();
    $row = tt_get_member_row($user_id);
    if($row) {
        $prev_end_time = strtotime($row->endTime);
        if($prev_end_time - $now > 100){ //尚未过期
            $start_time = strtotime($row->startTime); //使用之前的开始时间
            $end_time = $end_time ? : strtotime($row->endTime) + $duration;
        }else{ //已过期
            $start_time = $now;
            $end_time = $end_time ? : $now + $duration;
        }
        $update = $wpdb->update(
            $members_table,
            array(
                'user_type' => $vip_type,
                'startTime' => date('Y-m-d H:i:s', $start_time),
                'endTime' => date('Y-m-d H:i:s', $end_time),
                'endTimeStamp' => $end_time
            ),
            array('user_id' => $user_id),
            array('%d', '%s', '%s', '%d'),
            array('%d')
        );

        // 发送邮件
        $admin_handle ? tt_promote_vip_email($user_id, $vip_type, date('Y-m-d H:i:s', $start_time), date('Y-m-d H:i:s', $end_time)) : tt_open_vip_email($user_id, $vip_type, date('Y-m-d H:i:s', $start_time), date('Y-m-d H:i:s', $end_time));
        // 站内消息
        tt_create_message($user_id, 0, 'System', 'notification', __('你的会员状态发生了变化', 'tt'), sprintf( __('会员类型: %1$s, 到期时间: %2$s', 'tt'), tt_get_member_type_string($vip_type), date('Y-m-d H:i:s', $end_time) ));
        return $update !== false;
    }

    $end_time = $end_time ? : $now + $duration;
    $insert = $wpdb->insert(
        $members_table,
        array(
            'user_id' => $user_id,
            'user_type' => $vip_type,
            'startTime' => date('Y-m-d H:i:s', $start_time),
            'endTime' => date('Y-m-d H:i:s', $end_time),
            'endTimeStamp' => $end_time
        ),
        array('%d', '%d', '%s', '%s', '%d')
    );
    if($insert) {
        // 发送邮件
        $admin_handle ? tt_promote_vip_email($user_id, $vip_type, date('Y-m-d H:i:s', $start_time), date('Y-m-d H:i:s', $end_time)) : tt_open_vip_email($user_id, $vip_type, date('Y-m-d H:i:s', $start_time), date('Y-m-d H:i:s', $end_time));
        // 站内消息
        tt_create_message($user_id, 0, 'System', 'notification', __('你的会员状态发生了变化', 'tt'), sprintf( __('会员类型: %1$s, 到期时间: %2$s', 'tt'), tt_get_member_type_string($vip_type), date('Y-m-d H:i:s', $end_time) ));

        return $wpdb->insert_id;
    }
    return false;
}


/**
 * 删除会员记录
 *
 * @since 2.0.0
 * @param $user_id
 * @return bool
 */
function tt_delete_member($user_id){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $members_table = $prefix . 'tt_members';
    $delete = $wpdb->delete(
        $members_table,
        array('user_id' => $user_id),
        array('%d')
    ); //TODO deleted field
    return !!$delete;
}


/**
 * 删除会员记录
 *
 * @since 2.0.0
 * @param $id
 * @return bool
 */
function tt_delete_member_by_id($id){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $members_table = $prefix . 'tt_members';
    $delete = $wpdb->delete(
        $members_table,
        array('id' => $id),
        array('%d')
    ); //TODO deleted field
    return !!$delete;
}


/**
 * 获取所有指定类型会员
 *
 * @since 2.0.0
 * @param int $member_type // -1 代表all
 * @param int $limit
 * @param int $offset
 * @return array|null|object
 */
function tt_get_vip_members($member_type = -1, $limit = 20, $offset = 0){
    if($member_type != -1 && !in_array($member_type, array(Member::MONTHLY_VIP, Member::ANNUAL_VIP, Member::PERMANENT_VIP))){
        $member_type = -1;
    }

    global $wpdb;
    $prefix = $wpdb->prefix;
    $members_table = $prefix . 'tt_members';
    $now = time();

    if($member_type == -1){
        $sql = sprintf("SELECT * FROM $members_table WHERE `user_type`>0 AND `endTimeStamp`>=%d LIMIT %d OFFSET %d", $now, $limit, $offset);
    }else{
        $sql = sprintf("SELECT * FROM $members_table WHERE `user_type`=%d AND `endTimeStamp`>%d LIMIT %d OFFSET %d", $member_type, $now, $limit, $offset);
    }

    $results = $wpdb->get_results($sql);
    return $results;
}


/**
 * 统计指定类型会员数量
 *
 * @since 2.0.0
 * @param int $member_type
 * @return int
 */
function tt_count_vip_members($member_type = -1){
    if($member_type != -1 && !in_array($member_type, array(Member::MONTHLY_VIP, Member::ANNUAL_VIP, Member::PERMANENT_VIP))){
        $member_type = -1;
    }

    global $wpdb;
    $prefix = $wpdb->prefix;
    $members_table = $prefix . 'tt_members';
    $now = time();

    if($member_type == -1){
        $sql = sprintf("SELECT COUNT(*) FROM $members_table WHERE `user_type`>0 AND `endTimeStamp`>=%d", $now);
    }else{
        $sql = sprintf("SELECT COUNT(*) FROM $members_table WHERE `user_type`=%d AND `endTimeStamp`>%d", $member_type, $now);
    }

    $count = $wpdb->get_var($sql);
    return $count;
}


/**
 * 会员标识
 *
 * @since 2.0.0
 * @param $user_id
 * @return string
 */
function tt_get_member_icon($user_id){
    $member = new Member($user_id);
    //0代表已过期或非会员 1代表月费会员 2代表年费会员 3代表永久会员
    if($member->is_permanent_vip()){
        return '<i class="ico permanent_member"></i>';
    }elseif($member->is_annual_vip()){
        return '<i class="ico annual_member"></i>';
    }elseif($member->is_monthly_vip()){
        return '<i class="ico monthly_member"></i>';
    }
    return '<i class="ico normal_member"></i>';
}


/**
 * 获取充值VIP价格
 *
 * @since 2.0.0
 * @param int $vip_type
 * @return float
 */
function tt_get_vip_price($vip_type = Member::MONTHLY_VIP){
    switch ($vip_type){
        case Member::MONTHLY_VIP:
            $price = tt_get_option('tt_monthly_vip_price', 10);
            break;
        case Member::ANNUAL_VIP:
            $price = tt_get_option('tt_annual_vip_price', 100);
            break;
        case Member::PERMANENT_VIP:
            $price = tt_get_option('tt_permanent_vip_price', 199);
            break;
        default:
            $price = 0;
    }
    return sprintf('%0.2f', $price);
}

/**
 * 创建开通VIP的订单
 *
 * @since 2.0.0
 * @param $user_id
 * @param int $vip_type
 * @return array|bool
 */
function tt_create_vip_order($user_id, $vip_type = 1){
    if(!in_array($vip_type * (-1), array(Product::MONTHLY_VIP, Product::ANNUAL_VIP, Product::PERMANENT_VIP))){
        $vip_type = Product::PERMANENT_VIP;
    }

    $order_id = tt_generate_order_num();
    $order_time = current_time('mysql');
    $product_id = $vip_type * (-1);
    $currency = 'cash';
    $order_price = tt_get_vip_price($vip_type);
    $order_total_price = $order_price;

    switch ($vip_type * (-1)){
        case Product::MONTHLY_VIP:
            $product_name = Product::MONTHLY_VIP_NAME;
            break;
        case Product::ANNUAL_VIP:
            $product_name = Product::ANNUAL_VIP_NAME;
            break;
        case Product::PERMANENT_VIP:
            $product_name = Product::PERMANENT_VIP_NAME;
            break;
        default:
            $product_name = '';
    }

    global $wpdb;
    $prefix = $wpdb->prefix;
    $orders_table = $prefix . 'tt_orders';
    $insert = $wpdb->insert(
        $orders_table,
        array(
            'parent_id' => 0,
            'order_id' => $order_id,
            'product_id' => $product_id,
            'product_name' => $product_name,
            'order_time' => $order_time,
            'order_price' => $order_price,
            'order_currency' => $currency,
            'order_quantity' => 1,
            'order_total_price' => $order_total_price,
            'user_id' => $user_id
        ),
        array('%d', '%s', '%d', '%s', '%s', '%f', '%s', '%d', '%f', '%d')
    );
    if($insert) {
        return array(
            'insert_id' => $wpdb->insert_id,
            'order_id' => $order_id,
            'total_price' => $order_total_price
        );
    }
    return false;
}


/**
 * 根据订单产品ID获取对应VIP开通类型名
 *
 * @since 2.0.0
 * @param $product_id
 * @return string
 */
function tt_get_vip_product_name($product_id) {
    switch ($product_id){
        case Product::PERMANENT_VIP:
            return Product::PERMANENT_VIP_NAME;
        case Product::ANNUAL_VIP:
            return Product::ANNUAL_VIP_NAME;
        case Product::MONTHLY_VIP:
            return Product::MONTHLY_VIP_NAME;
        default:
            return "";
    }
}