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

echo 'Action - SignIn';

global $wp_query;

var_dump($wp_query);

// 引入页脚
tt_get_footer('simple');
