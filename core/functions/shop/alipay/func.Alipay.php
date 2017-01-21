<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/03 17:00
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * 获取支付宝接口配置参数
 *
 * @since 2.0.0
 * @return array
 */
function tt_get_alipay_config() {
    $alipay_config = array();
    $alipay_config['partner'] = tt_get_option('tt_alipay_partner');;
    $alipay_config['key'] = tt_get_option('tt_alipay_key');;
    $alipay_config['sign_type'] = strtoupper('MD5');
    $alipay_config['input_charset'] = strtolower('utf-8');
    $alipay_config['cacert'] = getcwd().'/cacert.pem';
    $alipay_config['transport'] = is_ssl() ? 'https' : 'http';

    return $alipay_config;
}
