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

wp_logout();

if ( !empty( $_REQUEST['redirect_to'] ) || !empty( $_REQUEST['redirect'] ) ){
	$redirect_to = $_REQUEST['redirect_to'] || $_REQUEST['redirect'];
} else {
	$redirect_to = '/';
}

wp_safe_redirect( $redirect_to );

exit();
