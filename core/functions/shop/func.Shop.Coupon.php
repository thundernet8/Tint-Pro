<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/13 20:44
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
function tt_install_coupons_table() {
    global $wpdb;
    include_once(ABSPATH . '/wp-admin/includes/upgrade.php');
    $table_charset = '';
    $prefix = $wpdb->prefix;
    $coupons_table = $prefix . 'tt_coupons';
    if($wpdb->has_cap('collation')) {
        if(!empty($wpdb->charset)) {
            $table_charset = "DEFAULT CHARACTER SET $wpdb->charset";
        }
        if(!empty($wpdb->collate)) {
            $table_charset .= " COLLATE $wpdb->collate";
        }
    }

    // 优惠券数据表
    // - id 自增ID
    // - coupon_code 优惠券号码
    // - coupon_type 优惠券类型(once/multi)
    // - coupon_status 优惠券状态(1/0)
    // - discount_value 折扣值
    // - expire_date 到期时间
    // - unavailable_products 不可用的商品ID(逗号分隔), 优先级高于available_products
    // - available_products 可用的商品ID(逗号分隔)
    // - available_product_cats 不可用的商品分类ID(逗号分隔)
    $create_coupons_sql = "CREATE TABLE $coupons_table (id int(11) NOT NULL auto_increment,coupon_code varchar(20) NOT NULL,coupon_type varchar(20) NOT NULL default 'once',coupon_status int(11) NOT NULL default 1,discount_value double(10,2) NOT NULL default 0.90,expire_date datetime NOT NULL default '0000-00-00 00:00:00',unavailable_products text,available_products text,available_product_cats text,PRIMARY KEY (id),INDEX couponcode_index(coupon_code)) ENGINE = MyISAM $table_charset;";
    maybe_create_table($coupons_table,$create_coupons_sql);
}
add_action( 'admin_init', 'tt_install_coupons_table' );
