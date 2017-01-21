<?php
/**
 * Copyright 2016, WebApproach.net
 * All right reserved.
 *
 * @author Zhiyan
 * @date 16/5/27 16:30
 * @license GPL v3 LICENSE
 */
?>
<?php

/* 安全检测 */
//defined( 'ABSPATH' ) || exit;
if (!defined('ABSPATH')){
    wp_die(__('Lack of WordPress environment', 'tt'), __('WordPress internal error', 'tt'), array('response'=>500));
}

/* 授权信息 */
//Tint Pro授权相关参数
global $tt_auth_config;
//订单号
$tt_auth_config['order'] = '这里填订单号';//在https://webapproach.net/shop购买Tint高级版主题后，可在订单列表中看到自己的订单号
//授权码
$tt_auth_config['sn'] = '这里填授权码';//购买后凭订单号至https://webapproach.net/tint/authorize.php授权域名获得唯一授权码

/* 引入加载器 */
require_once (get_template_directory() . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'func.Loader.php');


/* 请在下方添加你的自定义函数和功能 */
///////////////////////////////////////////////////
