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
 * @link https://www.webapproach.net/tint.html
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
    wp_register_script( 'tt_common', THEME_ASSET . '/js/' . JS_COMMON, array(), null, true );
    wp_register_script( 'tt_home', THEME_ASSET . '/js/' . JS_HOME, array(), null, true );
    wp_register_script( 'tt_front_page', THEME_ASSET . '/js/' . JS_FRONT_PAGE, array(), null, true );
    wp_register_script( 'tt_single_page', THEME_ASSET . '/js/' . JS_SINGLE, array(), null, true );
    wp_register_script( 'tt_archive_page', THEME_ASSET . '/js/' . JS_ARCHIVE, array(), null, true );
    wp_register_script( 'tt_product_page', THEME_ASSET . '/js/' . JS_PRODUCT, array(), null, true );
    wp_register_script( 'tt_products_page', THEME_ASSET . '/js/' . JS_PRODUCT_ARCHIVE, array(), null, true );
    wp_register_script( 'tt_uc_page', THEME_ASSET . '/js/' . JS_UC, array(), null, true );
    wp_register_script( 'tt_me_page', THEME_ASSET . '/js/' . JS_ME, array(), null, true );
    wp_register_script( 'tt_action_page', THEME_ASSET . '/js/' . JS_ACTION, array(), null, true );
    wp_register_script( 'tt_404_page', THEME_ASSET . '/js/' . JS_404, array(), null, true );

    $data = array(
        'uid'           => get_current_user_id(),
        'language'      => get_option('WPLANG', 'zh_CN'),
        'apiRoot'       => esc_url_raw( get_rest_url() ),
        'nonce'         => wp_create_nonce( 'wp_rest' ),
        'home'          => esc_url_raw( home_url() ),
        'themeRoot'     => THEME_URI,
        'isHome'        => is_home(),
        'isSingle'      => is_single()
    );
    wp_localize_script( 'tt_common', 'TT', $data );
    wp_enqueue_script( 'tt_jquery' );
    wp_enqueue_script( 'tt_common' );
    if(is_home()) {
        wp_enqueue_script( 'tt_home' );
    }elseif(is_single()) {
        wp_enqueue_script( get_post_type()==='product' ? 'tt_product_page' : 'tt_single_page' );
    }elseif(is_archive()) {
        wp_enqueue_script( get_post_type()==='product' ? 'tt_products_page' : 'tt_archive_page' );
    }elseif(is_author()) {
        wp_enqueue_script( 'tt_uc_page' );
    }elseif(is_404()) {
        wp_enqueue_script( 'tt_404_page' );
    }elseif(get_query_var('is_me_route')) {
        wp_enqueue_script( 'tt_me_page' );
    }elseif(get_query_var('action')) {
        wp_enqueue_script( 'tt_action_page' );
    }elseif(is_front_page()) {
        wp_enqueue_script( 'tt_front_page' );
    }
}
add_action( 'wp_enqueue_scripts', 'tt_register_scripts' );
