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
 * @link https://webapproach.net/tint.html
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
    // - deleted
    // - deleted_by
    $create_orders_sql = "CREATE TABLE $orders_table (id int(11) NOT NULL auto_increment,parent_id int(11) NOT NULL DEFAULT 0,order_id varchar(30) NOT NULL,trade_no varchar(50) NOT NULL,product_id int(20) NOT NULL,product_name varchar(250),order_time datetime NOT NULL default '0000-00-00 00:00:00',order_success_time datetime NOT NULL default '0000-00-00 00:00:00',order_price double(10,2) NOT NULL,order_currency varchar(20) NOT NULL default 'credit',order_quantity int(11) NOT NULL default 1,order_total_price double(10,2) NOT NULL,order_status tinyint(4) NOT NULL default 1,coupon_id int(11) DEFAULT 0,user_id int(11) NOT NULL,address_id int(11) NOT NULL DEFAULT 0,user_message text,user_alipay varchar(100),deleted tinyint(4) NOT NULL default 0,deleted_by int(11),PRIMARY KEY (id),INDEX orderid_index(order_id),INDEX tradeno_index(trade_no),INDEX productid_index(product_id),INDEX uid_index(user_id)) ENGINE = MyISAM $table_charset;";
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
        case (OrderStatus::WAIT_PAYMENT):
            $status_text = __('Wait Payment', 'tt'); //等待买家付款
            break;
        case (OrderStatus::PAYED_AND_WAIT_DELIVERY):
            $status_text = __('Payed, Wait Delivery', 'tt'); //已付款，等待卖家发货
            break;
        case (OrderStatus::DELIVERED_AND_WAIT_CONFIRM):
            $status_text = __('Delivered, Wait Confirm', 'tt'); //已发货，等待买家确认
            break;
        case (OrderStatus::TRADE_SUCCESS):
            $status_text = __('Trade Succeed', 'tt'); //交易成功
            break;
        case (OrderStatus::TRADE_CLOSED):
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
 * 获取指定订单(通过订单序号)
 *
 * @since 2.0.0
 * @param $seq
 * @return array|null|object|void
 */
function tt_get_order_by_sequence($seq) {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $orders_table = $prefix . 'tt_orders';
    $order = $wpdb->get_row(sprintf("SELECT * FROM $orders_table WHERE `id`='%d'", $seq));
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
        $sql = sprintf("SELECT * FROM $orders_table WHERE `deleted`=0 ORDER BY `id` DESC LIMIT %d OFFSET %d", $limit, $offset);
    }else{
        $sql = sprintf("SELECT * FROM $orders_table WHERE `deleted`=0 AND `order_currency`='%s' ORDER BY `id` DESC LIMIT %d OFFSET %d", $currency_type, $limit, $offset);
    }
    $results = $wpdb->get_results($sql);
    return $results;
}


/**
 * 获取订单数量
 *
 * @since 2.0.0
 * @param string $currency_type
 * @return int
 */
function tt_count_orders($currency_type = 'all'){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $orders_table = $prefix . 'tt_orders';
    if($currency_type == 'all'){
        $sql = "SELECT COUNT(*) FROM $orders_table WHERE `deleted`=0";
    }else{
        $sql = sprintf("SELECT COUNT(*) FROM $orders_table WHERE `deleted`=0 AND `order_currency`='%s'", $currency_type);
    }
    $count = $wpdb->get_var($sql);
    return (int)$count;
}


/**
 * 获取用户的订单
 *
 * @since 2.0.0
 * @param int $user_id
 * @param int $limit
 * @param int $offset
 * @param string $currency_type
 * @return array|null|object
 */
function tt_get_user_orders($user_id = 0, $limit = 20, $offset = 0, $currency_type = 'all'){
    $user_id = $user_id ? : get_current_user_id();
    if(!$user_id){
        return null;
    }

    global $wpdb;
    $prefix = $wpdb->prefix;
    $orders_table = $prefix . 'tt_orders';
    if($currency_type == 'all'){
        $sql = sprintf("SELECT * FROM $orders_table WHERE `deleted`=0 AND `user_id`=%d ORDER BY `id` DESC LIMIT %d OFFSET %d", $user_id, $limit, $offset);
    }else{
        $sql = sprintf("SELECT * FROM $orders_table WHERE `deleted`=0 AND `user_id`=%d AND `order_currency`='%s' ORDER BY `id` DESC LIMIT %d OFFSET %d", $user_id, $currency_type, $limit, $offset);
    }
    $results = $wpdb->get_results($sql);

    return $results;
}


/**
 * 统计用户订单数量
 *
 * @since 2.0.0
 * @param int $user_id
 * @param string $currency_type
 * @return int
 */
function tt_count_user_orders($user_id = 0, $currency_type = 'all'){
    $user_id = $user_id ? : get_current_user_id();
    if(!$user_id){
        return 0;
    }

    global $wpdb;
    $prefix = $wpdb->prefix;
    $orders_table = $prefix . 'tt_orders';
    if($currency_type == 'all'){
        $sql = sprintf("SELECT COUNT(*) FROM $orders_table WHERE `deleted`=0 AND `user_id`=%d", $user_id);
    }else{
        $sql = sprintf("SELECT COUNT(*) FROM $orders_table WHERE `deleted`=0 AND `user_id`=%d AND `order_currency`='%s'", $user_id, $currency_type);
    }
    $count = $wpdb->get_var($sql);
    return (int)$count;
}


/**
 * 获取指定用户指定商品的订单
 *
 * @param $product_id
 * @param int $user_id
 * @return array|null|object
 */
function tt_get_specified_user_and_product_orders($product_id, $user_id = 0){
    $user_id = $user_id ? : get_current_user_id();
    if(!$user_id){
        return null;
    }

    global $wpdb;
    $prefix = $wpdb->prefix;
    $orders_table = $prefix . 'tt_orders';
    $sql = sprintf("SELECT * FROM $orders_table WHERE `deleted`=0 AND `user_id`=%d AND `product_id`=%d ORDER BY `id` DESC", $user_id, $product_id);
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
    $results = $wpdb->get_results(sprintf("SELECT * FROM $orders_table WHERE `deleted`=0 AND `parent_id`=%d ORDER BY `id` ASC", $parent_id));
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
    $discount_summary = tt_get_product_discount_array($product_id); // array 第1项为普通折扣, 第2项为会员(月付)折扣, 第3项为会员(年付)折扣, 第4项为会员(永久)折扣
    switch ($member->vip_type){
        case Member::MONTHLY_VIP:
            $discount = $discount_summary[1];
            break;
        case Member::ANNUAL_VIP:
            $discount = $discount_summary[2];
            break;
        case Member::PERMANENT_VIP:
            $discount = $discount_summary[3];
            break;
        default:
            $discount = $discount_summary[0];
            break;
    }
    $discount = min($discount_summary[0], $discount); // 会员的价格不能高于普通打折价
    $order_quantity = max(1, $order_quantity);
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
        // 新创建现金订单时邮件通知管理员
        if($currency == 'cash') {
            do_action('tt_order_status_change', $order_id);
        }

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
        ),
        array('%d')
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
        $format,
        array('%s')
    );
    if(!($update===false)){
        if(isset($data['order_status'])) { // 删除订单时不触发
            // 钩子 - 用于清理缓存等
            do_action('tt_order_status_change', $order_id);
        }
        return true;
    }
    return false;
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
        return new WP_Error('order_id_invalid', __('The order with the a order id you specified is not existed', 'tt'), array( 'status' => 404 ));
    }
    $total_price = $order->order_total_price;
    // 检验coupon
    $coupon = tt_check_coupon($coupon_code);
    if($coupon instanceof WP_Error) {
        return $coupon;
    }elseif(!$coupon){
        return new WP_Error('coupon_invalid', __('The coupon is invalid', 'tt'), array( 'status' => 404 ));
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
//    global $wpdb;
//    $prefix = $wpdb->prefix;
//    $orders_table = $prefix . 'tt_orders';
//    $delete = $wpdb->delete(
//        $orders_table,
//        array('id' => $id),
//        array('%d')
//    );
    $user_id = get_current_user_id();
    // 清理VM缓存
    tt_clear_cache_key_like('tt_cache_daily_vm_MeOrdersVM_user' . $user_id);

    $order = tt_get_order_by_sequence($id);
    return tt_update_order($order->order_id, array('deleted' => 1, 'deleted_by' => $user_id), array('%d', '%d'));
//    return !!$delete;
}


/**
 * 根据order_id字段删除指定订单
 *
 * @since 2.0.0
 * @param $order_id
 * @return bool|WP_Error
 */
function tt_delete_order_by_order_id($order_id){
//    global $wpdb;
//    $prefix = $wpdb->prefix;
//    $orders_table = $prefix . 'tt_orders';
//    $delete = $wpdb->delete(
//        $orders_table,
//        array('order_id' => $order_id),
//        array('%d')
//    );
//    return !!$delete;
    $user_id = get_current_user_id();
    $order = tt_get_order($order_id);
    if(!$order){
        return new WP_Error('order_not_exist', __('The order is not exist', 'tt'));
    }

    if($order->user_id != $user_id && !current_user_can('edit_users')){
        return new WP_Error('delete_order_denied', __('You are not permit to delete this order', 'tt'));
    }

    // 清理VM缓存
    tt_clear_cache_key_like('tt_cache_daily_vm_MeOrdersVM_user' . $user_id);

    return tt_update_order($order_id, array('deleted' => 1, 'deleted_by' => $user_id), array('%d', '%d'));
}


/**
 * 发送订单状态变化邮件
 *
 * @since 2.0.0
 * @param $order_id
 */
function tt_order_email($order_id) {
    if(!tt_get_option('tt_order_events_notify', true)) return;
    $order = tt_get_order($order_id);
    if(!$order) {
        return;
    }
    $user = get_user_by('id', $order->user_id);
    $order_url = tt_url_for('my_order', $order->id);

    $blog_name = get_bloginfo('name');
    $admin_email = get_option('admin_email');
    $order_status_text = tt_get_order_status_text($order->order_status);
    $subject = sprintf(__('%s 商店交易状态变更提醒', 'tt'), $blog_name);
    $args = array(
        'blogName' => $blog_name,
        'buyerName' => $user->display_name,
        'orderUrl' => $order_url,
        'adminEmail' => $admin_email,
        'productName' => $order->product_name, //TODO suborders
        'orderId' => $order_id,
        'orderTotalPrice' => $order->order_total_price,
        'orderTime' => $order->order_time,
        'orderStatusText' => $order_status_text
    );
    tt_async_mail('', $user->user_email, $subject, $args, 'order-status');  // 同一时间多封异步邮件只会发送第一封, 其他丢失
    //tt_mail('', $user->user_email, $subject, $args, 'order-status');

    // 如果有新订单创建或交易成功 发信通知管理员
    if($order->order_status == OrderStatus::WAIT_PAYMENT || $order->order_status == OrderStatus::TRADE_SUCCESS){
        $admin_subject = $order->order_status==OrderStatus::TRADE_SUCCESS ? sprintf(__('%s 商店新成功交易提醒', 'tt'), $blog_name) : sprintf(__('%s 商店新订单提醒', 'tt'), $blog_name);
        $admin_args = array(
            'blogName' => $blog_name,
            'buyerName' => $user->display_name,
            'orderUrl' => tt_url_for('manage_order', $order->id),
            'adminEmail' => $admin_email,
            'productName' => $order->product_name, //TODO suborders
            'orderId' => $order_id,
            'orderTotalPrice' => $order->order_total_price,
            'orderTime' => $order->order_time,
            'orderStatusText' => $order_status_text,
            'buyerUC' => get_author_posts_url($user->ID)
        );
        tt_mail('', $admin_email, $admin_subject, $admin_args, 'order-status-admin'); // 同一时间多封异步邮件只会发送第一封, 其他丢失
    }

    // TODO 站内消息
}
add_action('tt_order_status_change', 'tt_order_email');

/**
 * 根据订单更新商品销量和存量
 *
 * @since 2.0.0
 * @param $order_id
 */
function tt_update_order_product_quantity($order_id) {
    $order = tt_get_order($order_id);
    if(!$order || $order->order_status != OrderStatus::TRADE_SUCCESS){
        return;
    }
    $parent_id = $order->parent_id;
    if($parent_id == -1){ // 这是一个合并订单
        $sub_orders = tt_get_sub_orders($order->id);
        $product_ids = array();
        $buy_amounts = array();
        foreach ($sub_orders as $sub_order){
            $product_ids[] = $sub_order->product_id;
            $buy_amounts[] = $sub_order->order_quantity;
        }
    }else{
        $product_ids = array($order->product_id);
        $buy_amounts = array($order->order_quantity);
    }

    foreach ($product_ids as $key => $product_id){
        // 更新存量
        $quantity = (int)get_post_meta($product_id, 'tt_product_quantity', true);
        update_post_meta($product_id, 'tt_product_quantity', max(0, $quantity-$buy_amounts[$key]));
        // 更新销量
        $sales = (int)get_post_meta($product_id, 'tt_product_sales', true);
        update_post_meta($product_id, 'tt_product_sales', $sales+$buy_amounts[$key]);
    }
}
add_action('tt_order_status_change', 'tt_update_order_product_quantity');


/**
 * 根据订单发送相应内容(付费内容,开通会员,增加积分等)
 *
 * @since 2.0.0
 * @param $order_id
 */
function tt_send_order_goods($order_id){
    $order = tt_get_order($order_id);
    if(!$order || $order->order_status != OrderStatus::TRADE_SUCCESS){
        return;
    }

    $user_id = $order->user_id;
    $user = get_user_by('id', $user_id);
    $parent_id = $order->parent_id;
    if($parent_id == -1){ // 这是一个合并订单
        $sub_orders = tt_get_sub_orders($order->id);
        $product_ids = array();
        foreach ($sub_orders as $sub_order){
            $product_ids[] = $sub_order->product_id;
        }
    }else{
        $product_ids = array($order->product_id);
    }

    $blog_name = get_bloginfo('name');
    foreach ($product_ids as $product_id){
        if($product_id > 0) {
            $pay_content = get_post_meta($product_id, 'tt_product_pay_content', true);
            $download_content = tt_get_product_download_content($product_id);
//            if(!$pay_content || !$download_content){
//                continue;
//            }
            $subject = sprintf(__('The Resources You Bought in %s', 'tt'), $blog_name);
            $args = array(
                'blogName' => $blog_name,
                'totalPrice' => $order->order_currency == 'credit' ? sprintf(__('%d Credits', 'tt'), $order->order_total_price) : sprintf(__('%0.2f YUAN', 'tt'), $order->order_total_price),
                'payContent' => $download_content . PHP_EOL . $pay_content
            );
            // tt_async_mail('', $user->user_email, $subject, $args, 'order-pay-content');
            tt_mail('', $user->user_email, $subject, $args, 'order-pay-content');
        }elseif($product_id == Product::MONTHLY_VIP){
            tt_add_or_update_member($user_id, Member::MONTHLY_VIP);
        }elseif($product_id == Product::ANNUAL_VIP){
            tt_add_or_update_member($user_id, Member::ANNUAL_VIP);
        }elseif($product_id == Product::PERMANENT_VIP){
            tt_add_or_update_member($user_id, Member::PERMANENT_VIP);
        }elseif($product_id == Product::CREDIT_CHARGE){
            tt_add_credits_by_order($order_id);
        }else{
            // TODO more
        }
    }
}
add_action('tt_order_status_change', 'tt_send_order_goods');


/**
 * 继续完成未支付订单
 *
 * @since 2.0.0
 * @param $order_id
 * @return bool|WP_Error|WP_REST_Response
 */
function tt_continue_pay($order_id){
    $order = tt_get_order($order_id);
    if(!$order) {
        return new WP_Error('order_not_found', __('The order is not found', 'tt'), array('status' => 404));
    }

    // 如果是子订单则转向父级订单
    if($order->parent_id > 0){
        $parent_order = tt_get_order_by_sequence($order->parent_id);
        if(!$parent_order) {
            return new WP_Error('order_not_found', __('The order is not found', 'tt'), array('status' => 404));
        }
        return tt_continue_pay($parent_order->order_id);
    }

    if(in_array($order->order_status, [OrderStatus::PAYED_AND_WAIT_DELIVERY, OrderStatus::DELIVERED_AND_WAIT_CONFIRM, OrderStatus::TRADE_SUCCESS])) {
        return new WP_Error('invalid_order_status', __('The order has been payed', 'tt'), array('status' => 200));
    }

    if($order->order_status == OrderStatus::TRADE_CLOSED) {
        return new WP_Error('invalid_order_status', __('The order has been closed', 'tt'), array('status' => 404));
    }

    if($order->order_currency == 'credit'){
        $pay = tt_credit_pay($order->order_total_price, $order->product_name, true);
        if($pay instanceof WP_Error) return $pay;
        if($pay) {
            // 更新订单支付状态和支付完成时间
            tt_update_order($order_id, array('order_success_time' => current_time('mysql'), 'order_status' => 4), array('%s', '%d')); //TODO 确保成功
            return tt_api_success('', array('data' => array(
                'orderId' => $order_id,
                'url' => add_query_arg(array('oid' => $order_id, 'spm' => wp_create_nonce('pay_result')), tt_url_for('payresult'))
                //TODO 添加积分充值链接
            )));
        }

        return new WP_Error('continue_pay_failed', __('Some error happened when continue the payment', 'tt'), array('status' => 500));
    }else{ // 现金支付
        $pay_method = tt_get_cash_pay_method();
        switch ($pay_method){
            case 'alipay':
                return tt_api_success('', array('data' => array( // 返回payment gateway url
                    'orderId' => $order_id,
                    'url' => tt_get_alipay_gateway($order_id)
                )));
            default: //qrcode
                return tt_api_success('', array('data' => array( // 直接返回扫码支付url,后面手动修改订单
                    'orderId' => $order_id,
                    'url' => tt_get_qrpay_gateway($order_id)
                )));
        }
    }
}