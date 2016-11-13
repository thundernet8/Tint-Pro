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
    // - order_note 订单备注
    // - user_id 用户ID
    // - user_name 用户名
    // - user_email 用户邮箱
    // - user_address 用户地址
    // - user_zip 用户邮编
    // - user_phone 用户电话
    // - user_cellphone 用户手机
    // - user_message 用户备注留言
    // - user_alipay 用户支付宝账户
    $create_orders_sql = "CREATE TABLE $orders_table (id int(11) NOT NULL auto_increment,order_id varchar(30) NOT NULL,trade_no varchar(30) NOT NULL,product_id int(20) NOT NULL,product_name varchar(250),order_time datetime NOT NULL default '0000-00-00 00:00:00',order_success_time datetime NOT NULL default '0000-00-00 00:00:00',order_price double(10,2) NOT NULL,order_currency varchar(20) NOT NULL default 'credit',order_quantity int(11) NOT NULL,order_total_price double(10,2) NOT NULL,order_status tinyint(4) NOT NULL default 0,order_note text,user_id int(11) NOT NULL,user_name varchar(60),user_email varchar(100),user_address varchar(250),user_zip varchar(10),user_phone varchar(20),user_cellphone varchar(20),user_message text,user_alipay varchar(100),PRIMARY KEY (id),INDEX orderid_index(order_id),INDEX tradeno_index(trade_no),INDEX productid_index(product_id),INDEX uid_index(user_id)) ENGINE = MyISAM $table_charset;";
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
