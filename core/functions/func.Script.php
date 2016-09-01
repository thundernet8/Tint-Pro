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
    wp_register_script( 'tt_common', THEME_ASSET . '/js/main-b244c002f0.js', array(), null, true );

    $data = array(
        'apiRoot'       => esc_url_raw( get_rest_url() ),
        'nonce'         => wp_create_nonce( 'wp_rest' ),
        'home'          => esc_url_raw( home_url() )
    );
    wp_localize_script( 'tt_common', 'TT', $data );
    wp_enqueue_script( 'tt_jquery' );
    wp_enqueue_script( 'tt_common' );
}
add_action( 'wp_enqueue_scripts', 'tt_register_scripts' );