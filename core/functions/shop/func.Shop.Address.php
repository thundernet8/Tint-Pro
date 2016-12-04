<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/26 20:24
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php

/**
 * 创建管理维护用户地址联系信息的数据表
 *
 * @since 2.0.0
 * @return void
 */
function tt_install_addresses_table() {
    global $wpdb;
    include_once(ABSPATH . '/wp-admin/includes/upgrade.php');
    $table_charset = '';
    $prefix = $wpdb->prefix;
    $addresses_table = $prefix . 'tt_addresses';
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
    // - user_id 用户ID
    /// - user_name 用户名
    /// - user_email 用户邮箱
    /// - user_address 用户地址
    /// - user_zip 用户邮编
    //// - user_phone 用户电话
    /// - user_cellphone 用户手机
    $create_orders_sql = "CREATE TABLE $addresses_table (id int(11) NOT NULL auto_increment,user_id int(11) NOT NULL DEFAULT 0,user_name varchar(60),user_email varchar(100),user_address varchar(250),user_zip varchar(10),user_cellphone varchar(20),PRIMARY KEY (id),INDEX uid_index(user_id)) ENGINE = MyISAM $table_charset;";
    maybe_create_table($addresses_table, $create_orders_sql);
}
add_action( 'admin_init', 'tt_install_addresses_table' );


/**
 * 根据地址ID获取地址记录
 *
 * @since 2.0.0
 * @param $address_id
 * @return array|null|object|void
 */
function tt_get_address($address_id){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $addresses_table = $prefix . 'tt_addresses';
    $row = $wpdb->get_row(sprintf("SELECT * FROM $addresses_table WHERE `id`=%d", $address_id));
    return $row;
}


/**
 * 添加地址记录
 *
 * @since 2.0.0
 * @param $name
 * @param $address
 * @param $cellphone
 * @param string $zip
 * @param string $email
 * @param int $user_id
 * @return bool|int
 */
function tt_add_address($name, $address, $cellphone, $zip = '', $email = '', $user_id = 0){
    $user = $user_id ? get_user_by('ID', $user_id) : wp_get_current_user();
    if(!$user->ID){
        return false;
    }
    $email = $email ? : $user->user_email;
    $name = $name ? : $user->display_name;
    global $wpdb;
    $prefix = $wpdb->prefix;
    $addresses_table = $prefix . 'tt_addresses';
    //$query = $wpdb->query(sprintf("INSERT INTO %s (user_id, user_name, user_email, user_address, user_zip, user_cellphone) VALUES (%d, %s, %s, %s, %s, %s)", $addresses_table, $user->ID, $name, $email, $address, $zip, $cellphone));
    $insert = $wpdb->insert(
        $addresses_table,
        array(
            'user_id' => $user->ID,
            'user_name' => $name,
            'user_email' => $email,
            'user_address' => $address,
            'user_zip' => $zip,
            'user_cellphone' => $cellphone
        ),
        array(
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s'
        )
    );
    if($insert) {
        return $wpdb->insert_id;
    }
    return false;
}


/**
 * 删除地址记录
 *
 * @since 2.0.0
 * @param $id
 * @return bool
 */
function tt_delete_address($id){
    global $wpdb;
    $prefix = $wpdb->prefix;
    $addresses_table = $prefix . 'tt_addresses';
    $delete = $wpdb->delete(
        $addresses_table,
        array('id' => $id),
        array('%d')
    );
    return !!$delete;
}


/**
 * 更新地址记录
 *
 * @since 2.0.0
 * @param $id
 * @param $data
 * @return bool
 */
function tt_update_address($id, $data){ // $data must be array( 'column1' => 'value1', 'column2' => 'value2') type
    $count = count($data);
    $format = array();
    for ($i=0; $i<$count; $i++){
        $format[] = '%s';
    }
    global $wpdb;
    $prefix = $wpdb->prefix;
    $addresses_table = $prefix . 'tt_addresses';
    $update = $wpdb->update(
        $addresses_table,
        $data,
        array('id' => $id),
        $format,
        array('%d')
    );
    return !($update===false);
}


/**
 * 获取用户的所有地址信息
 *
 * @since 2.0.0
 * @param int $user_id
 * @return array|null|object
 */
function tt_get_addresses($user_id = 0){
    $user_id = $user_id ? : get_current_user_id();
    global $wpdb;
    $prefix = $wpdb->prefix;
    $addresses_table = $prefix . 'tt_addresses';
    $results = $wpdb->get_results(sprintf("SELECT * FROM $addresses_table WHERE `user_id`=%d", $user_id));
    return $results;
}


/**
 * 获取默认地址
 *
 * @since 2.0.0
 * @param int $user_id
 * @return array|null|object|void
 */
function tt_get_default_address($user_id = 0){
    $user_id = $user_id ? : get_current_user_id();
    $default_address_id = (int)get_user_meta($user_id, 'tt_default_address_id', true);
    global $wpdb;
    $prefix = $wpdb->prefix;
    $addresses_table = $prefix . 'tt_addresses';
    if($default_address_id){
        $row = $wpdb->get_row(sprintf("SELECT * FROM $addresses_table WHERE `id`=%d", $default_address_id));
    }else{
        $row = $wpdb->get_row(sprintf("SELECT * FROM $addresses_table WHERE `user_id`=%d ORDER BY `id` DESC LIMIT 1 OFFSET 0", $user_id));
    }
    return $row;
}