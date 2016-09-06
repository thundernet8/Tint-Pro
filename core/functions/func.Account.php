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

    $key = tt_authdata($data, 'ENCODE', tt_get_option('tt_private_token'), 60*10); // 10分钟有效期

    $link = add_query_arg('token', $key, $base_url);
    return $link;
}


/**
 * 验证密码重置链接包含的key
 *
 * @since   2.0.0
 *
 * @param   string  $key
 * @return  bool
 */
function tt_verify_reset_password_link($key) {
    if(empty($key)) return false;
    $data = tt_authdata($key, 'DECODE', tt_get_option('tt_private_token'));
    if(!$data || !is_array($data) || !isset($data['id']) || !isset($data['email'])){
        return false;
    }

    return true;
}


/**
 * 通过key进行密码重置
 *
 * @since   2.0.0
 *
 * @param   string  $key
 * @param   string  $new_pass
 * @return  WP_User | WP_Error
 */
function tt_reset_password_by_key($key, $new_pass) {
    $data = tt_authdata($key, 'DECODE', tt_get_option('tt_private_token'));
    if(!$data || !is_array($data) || !isset($data['id']) || !isset($data['email'])){
        return new WP_Error( 'invalid_key', __( 'The key is invalid.', 'tt' ), array( 'status' => 400 ) );
    }

    $user = get_user_by('id', (int)$data['id']);
    if(!$user){
        return new WP_Error( 'user_not_found', __( 'Sorry, the user was not found.', 'tt' ), array( 'status' => 400 ) );
    }

    reset_password($user, $new_pass);
    return $user;
}


/**
 * 更改默认的登录链接
 *
 * @since   2.0.0
 *
 * @param   string  $login_url
 * @param   string  $redirect
 * @return  string
 */
function tt_filter_default_login_url($login_url, $redirect) {
    $login_url = tt_url_for('signin');

    if ( !empty($redirect) ) {
        $login_url = add_query_arg('redirect_to', urlencode($redirect), $login_url);
    }

    return $login_url;
}
add_filter('login_url', 'tt_filter_default_login_url', 10, 2);


/**
 * 更改默认的注销链接
 *
 * @since   2.0.0
 *
 * @param   string  $logout_url
 * @param   string  $redirect
 * @return  string
 */
function tt_filter_default_logout_url($logout_url, $redirect) {
    $logout_url = tt_url_for('signout');

    if ( !empty($redirect) ) {
        $logout_url = add_query_arg('redirect_to', urlencode($redirect), $logout_url);
    }

    return $logout_url;
}
add_filter('logout_url', 'tt_filter_default_logout_url', 10, 2);


/**
 * 更改默认的注册链接
 *
 * @since   2.0.0
 *
 * @return  string
 */
function tt_filter_default_register_url() {
    return tt_url_for('signup');
}
add_filter('register_url', 'tt_filter_default_register_url');
