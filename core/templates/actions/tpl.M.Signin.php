<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/28 15:45
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>

<?php

// 引入头部
tt_get_header('simple');

if ( !get_option('users_can_register') ) {
	wp_redirect( site_url('wp-login.php?registration=disabled') );
	exit();
}

// 引入页脚
tt_get_footer('simple');
