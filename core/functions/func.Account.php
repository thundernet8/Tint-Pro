<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/05 21:10
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>

<?php

/**
 * 生成密码重置链接
 *
 * @since   2.0.0
 *
 * @param   string  $email
 * @param   int     $user_id
 * @return  string
 */
function tt_generate_reset_password_link($email, $user_id = 0) {
    $base_url = tt_url_for('resetpass');

    if(!$user_id){
        $user_id = get_user_by('email', $email)->ID;
    }

    $data = array(
        'id' => $user_id,
        'email' =>  $email
    );

    $cache_key_prefix = 'tt_reset_pass_temp_' . $user_id;
    $token = tt_authdata($data, 'ENCODE', tt_get_option('tt_private_token'));

    // 先删除可能存在的之前的重置密码信息缓存
    global $wpdb;
    $wpdb->query( $wpdb->prepare("DELETE FROM $wpdb->options WHERE `option_name` LIKE %s", $cache_key_prefix . '%') );

    $cache_key = $cache_key_prefix . '_' .$token;
    set_transient($cache_key, maybe_serialize($data), 60*10); // 链接10分钟有效期

    $link = add_query_arg('token', $token, $base_url);
    return $link;
}


/**
 * 验证密码重置链接包含的token
 *
 * @since   2.0.0
 *
 * @param   string  $token
 * @return  bool
 */
function tt_verify_reset_password_link($token) {
    $data = tt_authdata($token, 'DECODE', tt_get_option('tt_private_token'));
    if(!$data || !is_array($data) || !isset($data['id'])){
        return false;
    }
    $user_id = $data['id'];
    $cache_key_prefix = 'tt_reset_pass_temp_' . $user_id;
    $cache_key = $cache_key_prefix . '_' .$token;
    if(get_transient($cache_key)){
        delete_transient($cache_key);
        return true;
    }

    return false;
}
