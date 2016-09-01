<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/01 20:06
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>

<?php

/**
 * 对于部分链接，拒绝搜索引擎索引
 *
 * @since   2.0.0
 *
 * @param   string  $output    Robots.txt内容
 * @param   bool    $public
 * @return  string
 */
function tt_robots_modification( $output, $public ){
    $output .= "\nDisallow: /oauth";
    $output .= "\nDisallow: /m";
    $output .= "\nDisallow: /me";
    return $output;
}
add_filter( 'robots_txt', 'tt_robots_modification', 10, 2 );


/**
 * 为部分页面添加noindex的meta标签
 *
 * @since   2.0.0
 *
 * @return  void
 */
function tt_add_noindex_meta(){
    if(get_query_var('is_uc') || get_query_var('action') || get_query_var('site_util') || get_query_var('is_me_route')){
        wp_no_robots();
    }
}
//add_action('wp_head', 'tt_add_noindex_meta');  // TODO