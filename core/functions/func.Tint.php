<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/25 16:19
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php

defined('TT_CHECK_HOME') || define('TT_CHECK_HOME', 'https://old.webapproach.net');

/**
 * 启动主题时清理版本检查任务
 *
 * @since 2.0.0
 */
function tt_clear_version_check(){
    global $pagenow;
    if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ){
        wp_clear_scheduled_hook( 'tt_check_version_daily_event' );
    }
}
add_action( 'load-themes.php', 'tt_clear_version_check' );

/**
 * 每天00:00检查主题版本
 *
 * @since 2.0.0
 */
function tt_check_version_setup_schedule() {
    if ( ! wp_next_scheduled( 'tt_check_version_daily_event' ) ) {
        wp_schedule_event( '1193875200', 'daily', 'tt_check_version_daily_event');
    }
}
add_action( 'wp', 'tt_check_version_setup_schedule' );

/**
 * 检查主题版本回调函数
 *
 * @since 2.0.0
 */
function tt_check_version_do_this_daily() {
    $url = TT_CHECK_HOME . '/tint/version.json';
    if(tt_get_http_response_code($url) == '200'){
        $check = 0;
        $ttVersion = wp_get_theme()->get( 'Version' );
        $key = TT_PRO ? 'proversion' : 'version';
        $data = json_decode(wp_remote_retrieve_body(wp_remote_get($url)), true);
        if ( $data[$key] != $ttVersion && !empty($data[$key]) ) $check = $data[$key];
        update_option('tt_tint_upgrade', $check);
        update_option('tt_tint_url', $data['url']);
    }
}
add_action( 'tt_check_version_daily_event', 'tt_check_version_do_this_daily' );

/**
 * 新版本提示
 *
 * @since 2.0.0
 */
function tin_update_alert_callback(){
    $tt_upgrade = get_option('tt_tint_upgrade', 0);
    $tt_url = get_option('tt_tint_url', 'https://www.webapproach.net/tint');
    $theme = wp_get_theme();
    if($tt_upgrade){
        echo '<div class="updated fade"><p>' . sprintf(__('Tint主题已更新至<a style="color:red;">%1$s</a>(当前%2$s)，请访问<a href="' . $tt_url . '" target="_blank">WebApproach Tint</a>查看！', 'tt'), $tt_upgrade, $theme->get('Version')) . '</p></div>';
    }
}
add_action( 'admin_notices', 'tt_update_alert_callback' );

/**
 * 新用户统计
 *
 * @since 2.0.0
 */
function tt_new_user(){
    global $pagenow;
    if(tt_get_http_response_code(TT_CHECK_HOME . '/tint/version.json')=='200'):
    endif;
    if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ){
        $url = get_bloginfo('url');
        $name = get_bloginfo('name');
        $email = get_bloginfo('admin_email');
        $theme = wp_get_theme();
        $ip = $_SERVER['REMOTE_ADDR'];
        $ip_addr = tt_query_ip_addr($ip);
        $data = array(
            'url'       =>  $url,
            'name'      =>  $name,
            'email'     =>  $email,
            'version'   =>  $theme->get('Version'),
            'is_pro'    =>  defined('TT_PRO') ? TT_PRO : !!preg_match('/([0-9-\.]+)PRO/i', $theme->get('Version')),
            'ip'        =>  $ip,
            'addr'      =>  $ip_addr
        );
        $response = wp_remote_post( TT_CHECK_HOME . '/tint/new-user.php', array(
                'method' => 'POST',
                'timeout' => 30,
                'blocking'  => false,
                'sslverify' => false,
                'body' => $data,
            )
        );
    }
}
add_action( 'load-themes.php', 'tt_new_user' );


/**
 * 检查主题授权
 *
 * @since 2.0.0
 */
function tt_tint_authorize() {
    $post = 1;
    $active = 0;
    $key = '';
    $server = strtolower($_SERVER['SERVER_NAME']);
    $free_servers = array('webapproach.net', 'old.webapproach.net', 'www.webapproach.net', 'zhiyanblog.com', 'www.zhiyanblog.com');
    if(in_array($server, $free_servers)){
        return;
    }
    $server_arr = explode('.', $server);
    if(count($server_arr)==3){
        $server = $server_arr[1] . '.' . $server_arr[2];
    }
    if($info = get_option('_wp_tt_authorize')){
        $info_arr = json_decode($info);
        if(($info_arr->active==1) && ($info_arr->time+3600*24*7>time())){
            $post = 0;
            $active = 1;
            $key = $info_arr->key;
        }
    }
    if($post==1){
        global $tt_auth_config;
        $data = array(
            'domain' => $server,
            'order' => $tt_auth_config['order'],
            'sn' => $tt_auth_config['sn']
        );
        $post_args = array(
            'method' => 'POST',
            'timeout' => 30,
            'blocking'  => false,
            'sslverify' => false,
            'body' => $data,
        );
        if($response = wp_remote_post(TT_CHECK_HOME . '/tint/ping.php', $post_args)){
            $body = wp_remote_retrieve_body($response);
            $results = json_decode($body);
            $active = $results->success;
            $key = $results->key;
        }else{
            $active = 0;
        }
    }
    if($active!=1){
        wp_die(sprintf(__('主题未授权本域名，如果你购买了本主题，请先<a href="%1$s" target="_blank" title="验证授权">验证授权</a>，并配置主题根目录functions.php中的相关授权参数。 如果没有购买，请访问<a href="%2$s" target="_blank" title="购买Tint主题">WebApproach商店</a>购买。'), TT_CHECK_HOME . '/tint/authorize.php', 'https://old.webapproach.net/shop'), '域名未授权');
    }else{
        $arr = array(
            'time' => time(),
            'active' => 1,
            'key' => $key
        );
        update_option('_wp_tt_authorize', json_encode($arr));
    }
}
tt_tint_authorize();