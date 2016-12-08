<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/5/27 17:33
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/* 后台编辑器预览样式 */
add_editor_style(THEME_ASSET.'/dash/css/editor-preview.css');

/* 后台编辑器强化 */
function tt_add_more_buttons($buttons){
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'styleselect';
	$buttons[] = 'fontselect';
	$buttons[] = 'hr';
	$buttons[] = 'sub';
	$buttons[] = 'sup';
	$buttons[] = 'cleanup';
	$buttons[] = 'image';
	$buttons[] = 'code';
	$buttons[] = 'media';
	$buttons[] = 'backcolor';
	$buttons[] = 'visualaid';
	return $buttons;
}
add_filter("mce_buttons_3", "tt_add_more_buttons");


/**
 * 后台编辑器文本模式添加短代码快捷输入按钮
 */
function tt_editor_quicktags() {
    wp_enqueue_script('my_quicktags', THEME_ASSET . '/dash/js/my_quicktags.js', array('quicktags'), '2.0.0');
}
add_action('admin_print_scripts', 'tt_editor_quicktags');

/**
 * 添加Admin bar项目
 *
 * @since   2.0.0
 * @param   WP_Admin_Bar  $wp_admin_bar
 */
function tt_clear_cache_on_admin_menu_bar( $wp_admin_bar ) {
    $args = array(
        'id'    => 'tt_admin_menu_bar_clear_cache',
        'title' => __('Clear Cache', 'tt'),
        'parent' => false,
        'href'  => wp_nonce_url( admin_url( 'admin.php?page=options-framework&tint_cache_empty=1' ), 'tt_clear_cache', 'tt_clear_cache_nonce' ),
        'meta'  => array( 'class' => 'tt-clear-cache' )
    );
    $wp_admin_bar->add_node( $args );
}
add_action( 'admin_bar_menu', 'tt_clear_cache_on_admin_menu_bar', 999 );

function tt_clear_cache_callback_on_admin_menu_bar(){
    if(isset($_GET['tt_clear_cache_nonce']) && wp_verify_nonce($_GET['tt_clear_cache_nonce'], 'tt_clear_cache')) {
        if(isset($_GET['tint_cache_empty']) && $_GET['tint_cache_empty']==1) {
            tt_clear_all_cache();
            add_settings_error( 'options-framework', 'tt_clear_cache', __( 'All Cache Clear.', 'tt' ), 'updated fade' );
        }
    }
}
//add_action('optionsframework_after', 'tt_clear_cache_callback_on_admin_menu_bar', 999);
add_action('admin_init', 'tt_clear_cache_callback_on_admin_menu_bar', 999);


/**
 * 后台用户列表显示昵称
 *
 * @since 2.0.0
 * @param $columns
 * @return mixed
 */
function tt_display_name_column( $columns ) {
    $columns['tt_display_name'] = __('Display Name', 'tt');
    unset($columns['name']);
    return $columns;
}
add_filter( 'manage_users_columns', 'tt_display_name_column' );

function tt_display_name_column_callback( $value, $column_name, $user_id ) {

    if( 'tt_display_name' == $column_name ){
        $user = get_user_by( 'id', $user_id );
        $value = ( $user->display_name ) ? $user->display_name : '';
    }

    return $value;
}
add_action( 'manage_users_custom_column', 'tt_display_name_column_callback', 10, 3 );


/**
 * 后台用户列表显示最近登录时间
 *
 * @since 2.0.0
 * @param $columns
 * @return mixed
 */
function tt_latest_login_column( $columns ) {
    $columns['tt_latest_login'] = __('Last Login', 'tt');
    return $columns;
}
add_filter( 'manage_users_columns', 'tt_latest_login_column' );

function tt_latest_login_column_callback( $value, $column_name, $user_id ) {
    if('tt_latest_login' == $column_name){
        $value = get_user_meta($user_id, 'tt_latest_login', true) ? : __('No Record','tt');
    }
    return $value;
}
add_action( 'manage_users_custom_column', 'tt_latest_login_column_callback', 10, 3 );


/**
 * 后台页脚
 *
 * @since 2.0.0
 * @param $text
 * @return string
 */
function left_admin_footer_text($text) {
    $text = sprintf(__('<span id="footer-thankyou">Thanks for using %s to help your creation, %s theme style your website</span>', 'tt'), '<a href=http://cn.wordpress.org/ >WordPress</a>', '<a href="https://www.webapproach.net/tint.html">Tint</a>');
    return $text;
}
add_filter('admin_footer_text','left_admin_footer_text');


/**
 * 增加用户资料字段
 *
 * @since 2.0.0
 * @param array $contactmethods
 * @return array
 */
function tt_add_contact_fields($contactmethods){
    $contactmethods['tt_qq'] = 'QQ';
    $contactmethods['tt_weibo'] = __('Sina Weibo','tt');
    $contactmethods['tt_weixin'] = __('Wechat','tt');
    $contactmethods['tt_twitter'] = __('Twitter','tt');
    $contactmethods['tt_facebook'] = 'Facebook';
    $contactmethods['tt_googleplus'] = 'Google+';
    $contactmethods['tt_alipay_email'] = __('Alipay Account','tt');
    $contactmethods['tt_alipay_pay_qr'] = __('Alipay Pay Qrcode','tt');
    $contactmethods['tt_wechat_pay_qr'] = __('Wechat Pay Qrcode','tt');

    // 删除无用字段
    unset($contactmethods['yim']);
    unset($contactmethods['aim']);
    unset($contactmethods['jabber']);

    return $contactmethods;
}
add_filter('user_contactmethods', 'tt_add_contact_fields');