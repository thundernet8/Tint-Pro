<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/28 19:44
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" >
    <meta name="robots" content="noindex,follow">
    <title><?php _e('Refresh Rewrite', 'tt'); ?></title>
</head>
<body>
<?php

/**
 * 此为私有模板，用于提供内部刷新固定链接缓存等
 * // 此页面只在开启了主题debug模式有效
 */

if(/*tt_get_option('tt_theme_debug') && */isset($_GET['token']) && trim($_GET['token']) == tt_get_option('tt_private_token')){
    if($ps = get_option('permalink_structure')){
        //刷新固定链接缓存
        tt_refresh_rewrite();
        echo sprintf(__('Rewrite rules refresh successfully, <a href="%1$s">back to home</a>', 'tt'), home_url());
    }else{
        echo __('Please customize your permalink structure, it\'s the basis of better theme functionality', 'tt');
    }
}else{
    echo __('Forbidden Access', 'tt');
    // 3秒后重定向至首页
    $home = home_url();
    header("refresh:3;url={$home}");
}
?>
</body>
</html>