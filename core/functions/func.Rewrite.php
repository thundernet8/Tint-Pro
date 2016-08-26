<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/26 21:07
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>

<?php

/**
 * Rewrite/Permalink/Routes
 */

/**
 * 强制使用伪静态
 *
 * @since   2.0.0
 *
 * @return  void
 */
function tt_force_permalink(){
    if(!get_option('permalink_structure')){
        update_option('permalink_structure', '/%postname%.html');
        // TODO: 添加后台消息提示已更改默认固定链接，并请配置伪静态(伪静态教程等)
    }
}
add_action('load-themes.php', 'tt_force_permalink');


/**
 * 短链接
 *
 * @since   2.0.0
 *
 * @return  void | false
 */
function tt_rewrite_short_link(){
    // 短链接前缀, 如https://www.webapproach.net/go/xxx中的go，为了区分短链接
    $prefix = tt_get_option('tt_short_link_prefix', 'go');
    $url = Utils::getCurrentUrl();
    preg_match('/\/' . $prefix . '\/([0-9A-Za-z]*)/i', $url, $matches);
    if(!$matches){
        return false;
    }
    $token = strtolower($matches[1]);
    $target_url = '';
    $records = tt_get_option('tt_short_link_records');
    $records = explode(PHP_EOL, $records);
    foreach ($records as $record){
        $record = explode('|', $record);
        if(count($record) < 2) continue;
        if(strtolower(trim($record[0])) === $token){
            $target_url = trim($record[1]);
            break;
        }
    }

    if($target_url){
        wp_redirect(esc_url($target_url), 302);
        exit;
    }

    return false;
}
add_action('template_redirect','tt_rewrite_short_link');


/**
 * 用户页路由(不同于作者页)
 *
 * @since   2.0.0
 *
 * @param   object  $wp_rewrite  Rewrite Rules
 * @return  void
 */
function tt_set_user_page_rewrite_rules($wp_rewrite){
    if($ps = get_option('permalink_structure')){
        // TODO: 用户链接前缀 `u` 是否可以自定义
        if(stripos('%postname', $ps) !== false){
            $new_rules['u/([0-9]+)$']	= 'index.php?author=$matches[1]&uc=1';
        }else{
            $new_rules['u/([0-9A-Za-z_-]+)$']	= 'index.php?author_name=$matches[1]&uc=1';
        }
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }
}
add_action('generate_rewrite_rules', 'tt_set_user_page_rewrite_rules');


/**
 * 为自定义的用户页添加query_var白名单，用于识别和区分用户页及作者页
 *
 * @since   2.0.0
 *
 * @param   object  $public_query_vars  公共全局query_vars
 * @return  object
 */
function tt_add_user_page_query_vars($public_query_vars) {
    if(!is_admin()){
        $public_query_vars[] = 'uc'; // 添加参数白名单uc，代表是用户中心页，采用用户模板而非作者模板
    }
    return $public_query_vars;
}
add_filter('query_vars', 'tt_add_user_page_query_vars');