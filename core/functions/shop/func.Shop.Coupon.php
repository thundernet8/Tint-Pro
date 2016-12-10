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
    // - begin_date 开始时间
    // - expire_date 到期时间
    // - unavailable_products 不可用的商品ID(逗号分隔), 优先级高于available_products
    /// - available_products 可用的商品ID(逗号分隔)
    // - unavailable_product_cats 不可用的商品分类ID(逗号分隔)
    $create_coupons_sql = "CREATE TABLE $coupons_table (id int(11) NOT NULL auto_increment,coupon_code varchar(20) NOT NULL,coupon_type varchar(20) NOT NULL default 'once',coupon_status int(11) NOT NULL default 1,discount_value double(10,2) NOT NULL default 0.90,begin_date datetime NOT NULL default '0000-00-00 00:00:00',expire_date datetime NOT NULL default '0000-00-00 00:00:00',PRIMARY KEY (id),INDEX couponcode_index(coupon_code)) ENGINE = MyISAM $table_charset;";
    maybe_create_table($coupons_table,$create_coupons_sql);
}
add_action( 'admin_init', 'tt_install_coupons_table' );


/**
 * 添加coupon
 *
 * @since 2.0.0
 * @param $code
 * @param string $type
 * @param float $discount
 * @param $begin_date
 * @param $expire_date
 * @return bool|int|WP_Error
 */
function tt_add_coupon($code, $type = 'once', $discount = 0.90, $begin_date, $expire_date/*, $unavailable_products = '', $unavailable_product_cats = ''*/){
    if(!current_user_can('edit_users')){
        return new WP_Error('no_permission', __('You do not have the permission to add a coupon', 'tt'));
    }
    //检查code重复
    global $wpdb;
    $prefix = $wpdb->prefix;
    $coupons_table = $prefix . 'tt_coupons';
    $exist = $wpdb->get_row(sprintf("SELECT * FROM $coupons_table WHERE `coupon_code`=%s", $code));
    if($exist){
        return new WP_Error('exist_coupon', __('The coupon code is existed', 'tt'));
    }

    $begin_date = $begin_date ? : current_time('mysql');
    $expire_date = $expire_date ? : current_time('mysql'); //TODO 默认有效期天数
    //添加记录
    $insert = $wpdb->insert(
        $coupons_table,
        array(
            'coupon_code' => $code,
            'coupon_type' => $type,
            'discount_value' => $discount,
            'begin_date' => $begin_date,
            'expire_date' => $expire_date
            //'unavailable_products' => $unavailable_products,
            //'unavailable_product_cats' => $unavailable_product_cats
        ),
        array(
            '%s',
            '%s',
            '%f',
            '%s',
            '%s',
            //'%s',
            //'%s'
        )
    );
    if($insert) {
        return $wpdb->insert_id;
    }
    return false;
}


/**
 * 删除coupon记录
 *
 * @since 2.0.0
 * @param $id
 * @return bool
 */
function tt_delete_coupon($id){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $coupons_table = $prefix . 'tt_coupons';
    $delete = $wpdb->delete(
        $coupons_table,
        array('id' => $id),
        array('%d')
    );
    return !!$delete;
}


/**
 * 更新coupon
 *
 * @since 2.0.0
 * @param $id
 * @param $data
 * @param $format
 * @return bool
 */
function tt_update_coupon($id, $data, $format){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $coupons_table = $prefix . 'tt_coupons';
    $update = $wpdb->update(
        $coupons_table,
        $data,
        array('id' => $id),
        $format,
        array('%d')
    );
    return !($update===false);
}

/**
 * 获取多条coupons
 *
 * @since 2.0.0
 * @param int $limit
 * @param int $offset
 * @return array|null|object
 */
function tt_get_coupons($limit = 20, $offset = 0){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $coupons_table = $prefix . 'tt_coupons';
    $results = $wpdb->get_results(sprintf("SELECT * FROM $coupons_table ORDER BY id DESC LIMIT %d, OFFSET %d", $limit, $offset));
    return $results;
}


/**
 * 检查优惠码有效性
 *
 * @since 2.0.0
 * @param $code
 * @return object|WP_Error
 */
function tt_check_coupon($code){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $coupons_table = $prefix . 'tt_coupons';
    $coupon = $wpdb->get_row(sprintf("SELECT * FROM $coupons_table WHERE `coupon_code`=%s", $code));
    if(!$coupon){
        return new WP_Error('coupon_not_exist', __('The coupon is not existed', 'tt'), array( 'status' => 404 ));
    }
    if(!($coupon->coupon_status)){
        return new WP_Error('coupon_used', __('The coupon is used', 'tt'), array( 'status' => 404 ));
    }
    $timestamp = time();
    if($timestamp < strtotime($coupon->begin_date)){
        return new WP_Error('coupon_not_in_effect', __('The coupon have not taken in effect yet', 'tt'), array( 'status' => 404 ));
    }
    if($timestamp > strtotime($coupon->expire_date)){
        return new WP_Error('coupon_expired', __('The coupon is expired', 'tt'), array( 'status' => 404 ));
    }
    return $coupon;
}