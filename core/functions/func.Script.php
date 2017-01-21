<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/30 23:21
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * 注册Scripts
 *
 * @since   2.0.0
 *
 * @return  void
 */
function tt_register_scripts() {
    // TODO: if debug mode, use `min.js`
    $jquery_url = json_decode(JQUERY_SOURCES)->{tt_get_option('tt_jquery', 'local_1')};
    wp_register_script( 'tt_jquery', $jquery_url, array(), null, tt_get_option('tt_foot_jquery', false) );
    //wp_register_script( 'tt_common', THEME_ASSET . '/js/' . JS_COMMON, array(), null, true );
    wp_register_script( 'tt_home', THEME_ASSET . '/js/' . JS_HOME, array(), null, true );
    wp_register_script( 'tt_front_page', THEME_ASSET . '/js/' . JS_FRONT_PAGE, array(), null, true );
    wp_register_script( 'tt_single_post', THEME_ASSET . '/js/' . JS_SINGLE, array(), null, true );
    wp_register_script( 'tt_single_page', THEME_ASSET . '/js/' . JS_PAGE, array(), null, true );
    wp_register_script( 'tt_archive_page', THEME_ASSET . '/js/' . JS_ARCHIVE, array(), null, true );
    wp_register_script( 'tt_product_page', THEME_ASSET . '/js/' . JS_PRODUCT, array(), null, true );
    wp_register_script( 'tt_products_page', THEME_ASSET . '/js/' . JS_PRODUCT_ARCHIVE, array(), null, true );
    wp_register_script( 'tt_uc_page', THEME_ASSET . '/js/' . JS_UC, array(), null, true );
    wp_register_script( 'tt_me_page', THEME_ASSET . '/js/' . JS_ME, array(), null, true );
    wp_register_script( 'tt_action_page', THEME_ASSET . '/js/' . JS_ACTION, array(), null, true );
    wp_register_script( 'tt_404_page', THEME_ASSET . '/js/' . JS_404, array(), null, true );
    wp_register_script( 'tt_site_utils', THEME_ASSET . '/js/' . JS_SITE_UTILS, array(), null, true);
    wp_register_script( 'tt_oauth_page', THEME_ASSET . '/js/' . JS_OAUTH, array(), null, true);
    wp_register_script( 'tt_manage_page', THEME_ASSET . '/js/' . JS_MANAGE, array(), null, true);

    $data = array(
        'debug'             => tt_get_option('tt_theme_debug', false),
        'uid'               => get_current_user_id(),
        'language'          => get_option('WPLANG', 'zh_CN'),
        'apiRoot'           => esc_url_raw( get_rest_url() ),
        '_wpnonce'          => wp_create_nonce( 'wp_rest' ), // REST_API服务验证该nonce, 如果不提供将清除登录用户信息  @see rest-api.php `rest_cookie_check_errors`
        'home'              => esc_url_raw( home_url() ),
        'themeRoot'         => THEME_URI,
        'isHome'            => is_home(),
        'commentsPerPage'   => tt_get_option('tt_comments_per_page', 20)
    );
    if(is_single()) {
        $data['isSingle'] = true;
        $data['pid'] = get_queried_object_id();
    }
    //wp_localize_script( 'tt_common', 'TT', $data );
    wp_enqueue_script( 'tt_jquery' );
    //wp_enqueue_script( 'tt_common' );
    $script = '';
    if(is_home()) {
        $script = 'tt_home';
    }elseif(is_single()) {
        $script = get_post_type()==='product' ? 'tt_product_page' : 'tt_single_post';
    }elseif((is_archive() && !is_author()) || (is_search() && isset($_GET['in_shop']) && $_GET['in_shop'] == 1)) {
        $script = get_post_type()==='product' || (is_search() && isset($_GET['in_shop']) && $_GET['in_shop'] == 1) ? 'tt_products_page' : 'tt_archive_page';
    }elseif(is_author()) {
        $script = 'tt_uc_page';
    }elseif(is_404()) {
        $script = 'tt_404_page';
    }elseif(get_query_var('is_me_route')) {
        $script = 'tt_me_page';
    }elseif(get_query_var('action')) {
        $script = 'tt_action_page';
    }elseif(is_front_page()) {
        $script = 'tt_front_page';
    }elseif(get_query_var('site_util')){
        $script = 'tt_site_utils';
    }elseif(get_query_var('oauth')){
        $script = 'tt_oauth_page';
    }elseif(get_query_var('is_manage_route')){
        $script = 'tt_manage_page';
    }else{
        // is_page() ?
        $script = 'tt_single_page';
    }

    if($script) {
        wp_localize_script( $script, 'TT', $data );
        wp_enqueue_script( $script );
    }
}
add_action( 'wp_enqueue_scripts', 'tt_register_scripts' );
