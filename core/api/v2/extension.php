<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/30 21:51
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>

<?php

/**
 * 拦截并重载默认的基于Cookies用户认证方式，采用OAuth的Access Token认证
 *
 * @since   x.x.x
 *
 * @param   int | false    $user_id     用户ID
 * @return  int | false
 */
function tt_install_token_authentication($user_id){
    // TODO: token verify and find the user_id
    return false;
}
add_filter('determine_current_user', 'tt_install_token_authentication', 5, 1);

remove_filter( 'determine_current_user', 'wp_validate_auth_cookie' );
remove_filter( 'determine_current_user', 'wp_validate_logged_in_cookie', 20 );