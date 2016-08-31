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
    wp_register_script( 'tt_common', THEME_ASSET . '/js/main-b81c81c854.js', array(), null, true );

    $data = array(
        'apiRoot'       => esc_url_raw( get_rest_url() ),
        'nonce'         => wp_create_nonce( 'wp_rest' ),
        'home'          => esc_url_raw( home_url() )
    );
    wp_localize_script( 'tt_common', 'TT', $data );
    wp_enqueue_script( 'tt_common');
}
add_action( 'wp_enqueue_scripts', 'tt_register_scripts' );
