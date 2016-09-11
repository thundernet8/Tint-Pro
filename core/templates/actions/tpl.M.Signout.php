<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/28 15:46
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

// wp_logout(); 包含 do_action( 'wp_logout' )，为了自定义跳转，需删除这个action，因此不使用wp_logout()
wp_destroy_current_session();
wp_clear_auth_cookie();

if ( !empty( $_REQUEST['redirect'] ) || !empty( $_REQUEST['redirect_to'] ) ){
	$redirect_to = $_REQUEST['redirect'] ?  $_REQUEST['redirect'] : $_REQUEST['redirect_to'];
} else {
	$redirect_to = '/';
}

wp_safe_redirect( $redirect_to );

exit();
