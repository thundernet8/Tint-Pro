<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/13 20:39
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php

/**
 * 创建商品系统必须的数据表
 *
 * @since 2.0.0
 * @return void
 */
function tt_install_orders_table() {
    global $wpdb;
    include_once(ABSPATH . '/wp-admin/includes/upgrade.php');
    $table_charset = '';
    $prefix = $wpdb->prefix;
    $orders_table = $prefix . 'tt_orders';
    if($wpdb->has_cap('collation')) {
        if(!empty($wpdb->charset)) {
            $table_charset = "DEFAULT CHARACTER SET $wpdb->charset";
        }
        if(!empty($wpdb->collate)) {
            $table_charset .= " COLLATE $wpdb->collate";
        }
    }
    // 订单数据表
    // - id 自增ID
    // - parent_id 父级订单ID, 默认0(购物车合并支付时需要创建合并订单)
    // - order_id 一定规律生成的唯一订单号
    // - trade_no 支付系统(支付宝)交易号
    // - product_id 商品ID
    // - product_name 商品名称
    // - order_time 订单创建时间
    // - order_success_time 订单交易成功时间
    // - order_price 订单单价
    // - order_currency 支付类型(积分或现金)
    // - order_quantity 购买数量
    // - order_total_price 订单总价
    // - order_status 订单状态 0/1/2/3/4/9
    // - coupon_id 使用的优惠码ID
    // - user_id 用户ID
    // - address_id 使用的地址ID
    // - user_message 用户备注留言
    // - user_alipay 用户支付宝账户
    $create_orders_sql = "CREATE TABLE $orders_table (id int(11) NOT NULL auto_increment,parent_id int(11) NOT NULL DEFAULT 0,order_id varchar(30) NOT NULL,trade_no varchar(30) NOT NULL,product_id int(20) NOT NULL,product_name varchar(250),order_time datetime NOT NULL default '0000-00-00 00:00:00',order_success_time datetime NOT NULL default '0000-00-00 00:00:00',order_price double(10,2) NOT NULL,order_currency varchar(20) NOT NULL default 'credit',order_quantity int(11) NOT NULL,order_total_price double(10,2) NOT NULL,order_status tinyint(4) NOT NULL default 1,coupon_id int(11) DEFAULT 0,user_id int(11) NOT NULL,address_id int(11) NOT NULL DEFAULT 0,user_message text,user_alipay varchar(100),PRIMARY KEY (id),INDEX orderid_index(order_id),INDEX tradeno_index(trade_no),INDEX productid_index(product_id),INDEX uid_index(user_id)) ENGINE = MyISAM $table_charset;";
    maybe_create_table($orders_table,$create_orders_sql);
}
add_action( 'admin_init', 'tt_install_orders_table' );

/**
 * 生成随机订单号
 *
 * @since 2.0.0
 * @return string
 */
function tt_generate_order_num(){
    $orderNum = mt_rand(10,25) . time() . mt_rand(1000,9999);
    return strval($orderNum);
}

/**
 * 获取订单状态文字
 *
 * @since 2.0.0
 * @param $code
 * @return string
 */
function tt_get_order_status_text($code){
    switch($code){
        case 1:
            $status_text = __('Wait Payment'); //等待买家付款
            break;
        case 2:
            $status_text = __('Payed, Wait Delivery'); //已付款，等待卖家发货
            break;
        case 3:
            $status_text = __('Delivered, Wait Confirm'); //已发货，等待买家确认
            break;
        case 4:
            $status_text = __('Trade Succeed'); //交易成功
            break;
        case 9:
            $status_text = __('Trade Closed', 'tt'); //交易关闭
            break;
        default:
            $status_text = __('Order Created', 'tt'); //订单创建成功
    }
    return $status_text;
}


/**
 * 获取指定订单
 *
 * @since 2.0.0
 * @param $order_id
 * @return array|null|object|void
 */
function tt_get_order($order_id) {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $orders_table = $prefix . 'tt_orders';
    $order = $wpdb->get_row(sprintf("SELECT * FROM $orders_table WHERE `order_id`='%s'", $order_id));
    return $order;
}


/**
 * 获取多条订单记录
 *
 * @since 2.0.0
 * @param $limit
 * @param $offset
 * @param string $currency_type
 * @return array|null|object
 */
function tt_get_orders($limit, $offset, $currency_type = 'all'){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $orders_table = $prefix . 'tt_orders';
    if($currency_type == 'all'){
        $sql = sprintf("SELECT * FROM $orders_table ORDER BY id DESC LIMIT %d, OFFSET %d", $limit, $offset);
    }else{
        $sql = sprintf("SELECT * FROM $orders_table WHERE `order_currency`='%s' ORDER BY id DESC LIMIT %d, OFFSET %d", $currency_type, $limit, $offset);
    }
    $results = $wpdb->get_results($sql);
    return $results;
}


/**
 * 获取子订单
 *
 * @since 2.0.0
 * @param $parent_id
 * @return array|null|object
 */
function tt_get_sub_orders($parent_id){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $orders_table = $prefix . 'tt_orders';
    $results = $wpdb->get_results(sprintf("SELECT * FROM $orders_table WHERE `parent_id`=%d ORDER BY id ASC", $parent_id));
    return $results;
}


/**
 * 创建单个订单
 *
 * @since 2.0.0
 * @param $product_id
 * @param string $product_name
 * @param int $order_quantity
 * @param int $parent_id
 * @return bool|array
 */
function tt_create_order($product_id, $product_name = '', $order_quantity = 1, $parent_id = 0){
    $user_id = get_current_user_id();
    $member = new Member($user_id);
    $order_id = tt_generate_order_num();
    $order_time = current_time('mysql');
    $currency = get_post_meta( $product_id, 'tt_pay_currency', true) ? 'cash' : 'credit';
    $order_price = $currency == 'cash' ? sprintf('%0.2f', get_post_meta($product_id, 'tt_product_price', true)) : (int)get_post_meta($product_id, 'tt_product_price', true);
    // 折扣
    $discount_summary = maybe_unserialize(get_post_meta($product_id, 'tt_product_discount', true)); // array 第1项为普通折扣, 第2项为会员(月付)折扣, 第3项为会员(年付)折扣, 第4项为会员(终身)折扣
    switch ($member->vip_type){
        case 'monthly':
            $discount = isset($discount_summary[1]) ? $discount_summary[1] : 100;
            break;
        case 'yearly':
            $discount = isset($discount_summary[2]) ? $discount_summary[2] : 100;
            break;
        case 'forever':
            $discount = isset($discount_summary[3]) ? $discount_summary[3] : 100;
            break;
        default:
            $discount = isset($discount_summary[0]) ? $discount_summary[0] : 100;
            break;
    }
    $order_total_price = $currency == 'cash' ? $order_price * absint($order_quantity) * $discount / 100 : absint($order_price * $order_quantity);

    $product_name = $product_name ? : get_the_title($product_id);

    global $wpdb;
    $prefix = $wpdb->prefix;
    $orders_table = $prefix . 'tt_orders';
    $insert = $wpdb->insert(
        $orders_table,
        array(
            'parent_id' => $parent_id,
            'order_id' => $order_id,
            'product_id' => $product_id,
            'product_name' => $product_name,
            'order_time' => $order_time,
            'order_price' => $order_price,
            'order_currency' => $currency,
            'order_quantity' => $order_quantity,
            'order_total_price' => $order_total_price,
            'user_id' => $user_id

        ),
        array(
            '%d',
            '%s',
            '%d',
            '%s',
            '%s',
            '%f',
            '%s',
            '%d',
            '%f',
            '%d'
        )
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
 * 创建合并订单(购物车结算)
 *
 * @since 2.0.0
 * @param $product_ids
 * @param $order_quantities
 * @return array|WP_Error
 */
function tt_create_combine_orders($product_ids, $order_quantities){
    $product_names = array();
    foreach ($product_ids as $product_id){
        $product_names[] = get_the_title($product_id);
    }
    $user_id = get_current_user_id();
    $order_id = tt_generate_order_num();
    $order_time = current_time('mysql');
    global $wpdb;
    $prefix = $wpdb->prefix;
    $orders_table = $prefix . 'tt_orders';
    // 创建父级订单
    $insert = $wpdb->insert(
        $orders_table,
        array(
            'parent_id' => -1, // -1表示有子订单
            'order_id' => $order_id,
            'product_id' => 0,
            'product_name' => implode('|', $product_names),
            'order_time' => $order_time,
            'order_price' => 0,
            'order_currency' => 'cash',
            'order_quantity' => 0,
            'order_total_price' => 0,
            'user_id' => $user_id

        ),
        array(
            '%d',
            '%s',
            '%d',
            '%s',
            '%s',
            '%f',
            '%s',
            '%d',
            '%f',
            '%d'
        )
    );
    if(!$insert) {
        return new WP_Error('create_order_failed', __('Create combine order failed', 'tt'));
    }
    $insert_id = $wpdb->insert_id;
    // 创建子订单
    $total_price = 0;
    foreach ($product_ids as $key => $product_id){
        $sub_order = tt_create_order($product_id, $product_names[$key], $order_quantities[$key], $insert_id);
        if(!$sub_order) {
            return new WP_Error('create_order_failed', __('Create combine order failed', 'tt')); //TODO distinguish
        }
        $total_price += $sub_order['total_price'];
    }
    // 更新父级订单
    $update = $wpdb->update(
        $orders_table,
        array(
           'order_total_price' => $total_price
        ),
        array('id' => $insert_id),
        array(
            '%f'
        )
    );
    if(!$update){
        return new WP_Error('create_order_failed', __('Create combine order failed', 'tt')); //TODO distinguish
    }
    return array(
        'insert_id' => $insert_id,
        'order_id' => $order_id,
        'total_price' => $total_price
    );
}


/**
 * 更新订单内容
 *
 * @since 2.0.0
 * @param $order_id
 * @param $data
 * @param $format
 * @return bool
 */
function tt_update_order($order_id, $data, $format){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $orders_table = $prefix . 'tt_orders';
    $update = $wpdb->update(
        $orders_table,
        $data,
        array('order_id' => $order_id),
        $format
    );
    return !($update===false);
}


/**
 * 使用优惠码更新订单总价
 *
 * @param $order_id
 * @param $coupon_code
 * @return bool|object|WP_Error
 */
function tt_update_order_by_coupon($order_id, $coupon_code){
    // 检验order
    $order = tt_get_order($order_id);
    if(!$order) {
        return new WP_Error('order_id_invalid', __('The order with the a order id you specified is not existed', 'tt'));
    }
    $total_price = $order->order_total_price;
    // 检验coupon
    $coupon = tt_check_coupon($coupon_code);
    if($coupon instanceof WP_Error) {
        return $coupon;
    }elseif(!$coupon){
        return new WP_Error('coupon_invalid', __('The coupon is invalid', 'tt'));
    }
    $discount = $coupon->discount_value;
    // 标记一次性coupon为已使用
    if($coupon->coupon_type == 'once'){
        $mark_used = tt_update_coupon($coupon->id, array('coupon_status' => 0), array('%d'));
    }
    // 更新订单
    $update = tt_update_order($order_id, array('order_total_price' => abs($total_price * $discount), 'coupon_id' => $coupon->id), array('%f', '%d'));
    return !($update === false);
}


/**
 * 根据自增id删除指定订单
 *
 * @since 2.0.0
 * @param $id
 * @return bool
 */
function tt_delete_order($id){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $orders_table = $prefix . 'tt_orders';
    $delete = $wpdb->delete(
        $orders_table,
        array('id' => $id),
        array('%d')
    );
    return !!$delete;
}


/**
 * 根据order_id字段删除指定订单
 *
 * @since 2.0.0
 * @param $order_id
 * @return bool
 */
function tt_delete_order_by_order_id($order_id){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $orders_table = $prefix . 'tt_orders';
    $delete = $wpdb->delete(
        $orders_table,
        array('order_id' => $order_id),
        array('%d')
    );
    return !!$delete;
}