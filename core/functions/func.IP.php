<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/06 22:45
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php

/**
 * 查询IP地址
 *
 * @since 2.0.0
 * @param $ip
 * @return array|mixed|object
 */
function tt_query_ip_addr($ip) {
    $url = 'http://freeapi.ipip.net/' . $ip;
    $body = wp_remote_retrieve_body(wp_remote_get($url));
//    "中国",                // 国家
//    "天津",                // 省会或直辖市（国内）
//    "天津",                // 地区或城市 （国内）
//    "",                   // 学校或单位 （国内）
//    "鹏博士",              // 运营商字段（只有购买了带有运营商版本的数据库才会有）
    //return json_decode($body);
    $arr = json_decode($body);
    if($arr[1] == $arr[2]){
        array_splice($arr, 2, 1);
    }
    return implode($arr);
}
