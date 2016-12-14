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
 * 生成包含注册信息的激活链接
 *
 * @since   2.0.0
 * @param   string  $username
 * @param   string  $email
 * @param   string  $password
 * @return  string
 */
function tt_generate_registration_activation_link ($username, $email, $password) {
    $base_url = tt_url_for('activate');

    $data = array(
        'username' => $username,
        'email' =>  $email,
        'password' => $password
    );

    $key = base64_encode(tt_authdata($data, 'ENCODE', tt_get_option('tt_private_token'), 60*10)); // 10分钟有效期

    $link = add_query_arg('key', $key, $base_url);

    return $link;
}


/**
 * 验证并激活注册信息的链接中包含的key
 *
 * @since   2.0.0
 *
 * @param   string  $key
 * @return  array | WP_Error
 */
function tt_activate_registration_from_link($key) {
    if(empty($key)) {
        return new WP_Error( 'invalid_key', __( 'The registration activation key is invalid.', 'tt' ), array( 'status' => 400 ) );
    }
    $data = tt_authdata(base64_decode($key), 'DECODE', tt_get_option('tt_private_token'));
    if(!$data || !is_array($data) || !isset($data['username']) || !isset($data['email']) || !isset($data['password'])){
        return new WP_Error( 'invalid_key', __( 'The registration activation key is invalid.', 'tt' ), array( 'status' => 400 ) );
    }

    // 开始激活(实际上在激活之前用户信息并没有插入到数据库中，为了防止恶意注册)
    $userdata = array(
        'user_login' => $data['username'],
        'user_email' => $data['email'],
        'user_pass' => $data['password']
    );
    $user_id = wp_insert_user($userdata);
    if(is_wp_error($user_id)) {
        return $user_id;
    }

    $result = array(
        'success' => 1,
        'message' => __('Activate the registration successfully', 'tt'),
        'data' => array(
            'username' => $data['username'],
            'email' => $data['email'],
            'id' => $user_id
        )
    );

    // 发送激活成功与注册欢迎信
    $blogname = get_bloginfo('name');
    // 给注册用户
    tt_async_mail('', $data['email'], sprintf(__('欢迎加入[%s]', 'tt'), $blogname), array('loginName' => $data['username'], 'password' => $data['password'], 'loginLink' => tt_url_for('signin')), 'register');
    // 给管理员
    tt_async_mail('', get_option('admin_email'), sprintf(__('您的站点「%s」有新用户注册 :', 'tt'), $blogname), array('loginName' => $data['username'], 'email' => $data['email'], 'ip' => $_SERVER['REMOTE_ADDR']), 'register-admin');

    return $result;
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


/**
 * 更改找回密码邮件中的内容
 *
 * @since 2.0.0
 * @param $message
 * @param $key
 * @return string
 */
function tt_reset_password_message( $message, $key ) {
    if ( strpos($_POST['user_login'], '@') ) {
        $user_data = get_user_by('email', trim($_POST['user_login']));
    } else {
        $login = trim($_POST['user_login']);
        $user_data = get_user_by('login', $login);
    }
    $user_login = $user_data->user_login;
    $reset_link = network_site_url('wp-login.php?action=rp&key=' . $key . '&login=' . rawurlencode($user_login), 'login') ;

    $templates = new League\Plates\Engine(THEME_TPL . '/plates/emails');
    return $templates->render('findpass', ['userLogin' => $user_login, 'resetPassLink' => $reset_link]);
}
add_filter('retrieve_password_message', 'tt_reset_password_message', null, 2);