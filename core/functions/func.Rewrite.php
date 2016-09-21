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
    // 短链接前缀, 如https://www.webapproach.net/go/xxx中的go，为了便于识别短链接
    $prefix = tt_get_option('tt_short_link_prefix', 'go');
    //$url = Utils::getCurrentUrl(); //该方法需要利用wp的query
    $url = Utils::getPHPCurrentUrl();
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


/* Route : UCenter - e.g /@nickname/latest */

/**
 * 用户页路由(非默认作者页)
 *
 * @since   2.0.0
 *
 * @param   object  $wp_rewrite  WP_Rewrite
 * @return  void
 */
function tt_set_user_page_rewrite_rules($wp_rewrite){
    if($ps = get_option('permalink_structure')){
        // TODO: 用户链接前缀 `u` 是否可以自定义
        // Note: 用户名必须数字或字母组成，不区分大小写
        if(stripos($ps, '%postname%') !== false){
            // 默认为profile tab，但是链接不显示profile
            $new_rules['@([一-龥a-zA-Z0-9]+)$'] = 'index.php?author_name=$matches[1]&uc=1';
            // ucenter tabs
            $new_rules['@([一-龥a-zA-Z0-9]+)/([A-Za-z]+)$'] = 'index.php?author_name=$matches[1]&uctab=$matches[2]&uc=1';
            // 分页
            $new_rules['@([一-龥a-zA-Z0-9]+)/([A-Za-z]+)/page/([0-9]{1,})$'] = 'index.php?author_name=$matches[1]&uctab=$matches[2]&uc=1&paged=$matches[3]';
        }else{
            $new_rules['u/([0-9]{1,})$'] = 'index.php?author=$matches[1]&uc=1';
            $new_rules['u/([0-9]{1,})/([A-Za-z]+)$'] = 'index.php?author=$matches[1]&uctab=$matches[2]&uc=1';
            $new_rules['u/([0-9]{1,})/([A-Za-z]+)/page/([0-9]{1,})$'] = 'index.php?author_name=$matches[1]&uctab=$matches[2]&uc=1&paged=$matches[3]';
        }
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }
}
add_action('generate_rewrite_rules', 'tt_set_user_page_rewrite_rules'); // filter `rewrite_rules_array` 也可用.


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
        $public_query_vars[] = 'uctab'; // 添加参数白名单uc，代表是用户中心页，采用用户模板而非作者模板
    }
    return $public_query_vars;
}
add_filter('query_vars', 'tt_add_user_page_query_vars');


/**
 * 自定义作者页链接
 *
 * @since   2.0.0
 *
 * @param   string  $link   原始链接
 * @param   int     $author_id  作者ID
 * @return  string
 */
function tt_custom_author_link($link, $author_id){
    $ps = get_option('permalink_structure');
    if(!$ps){
        return $link;
    }
    if(stripos($ps, '%postname%') !== false){
        $nickname = get_user_meta($author_id, 'nickname', true);
        // TODO: 解决nickname重复问题，用户保存资料时发出消息要求更改重复的名字，否则改为login_name，使用 `profile_update` action
        return home_url('/@' . $nickname);
    }
    return home_url('/u/' . strval($author_id));
}
add_filter('author_link', 'tt_custom_author_link', 10, 2);


/**
 * 用户链接解析Rewrite规则时正确匹配字段
 * // author_name传递的实际是nickname，而wp默认将其做login_name处理，需要修复
 * 同时对使用原始默认作者页链接的重定向至新的自定义链接
 *
 * @since   2.0.0
 *
 * @param   array   $query_vars   全局查询变量
 * @return  array
 */
function tt_match_author_link_field($query_vars){
    if(array_key_exists('author_name', $query_vars)){
        $nickname = $query_vars['author_name'];
        // 如果是原始author链接访问，重定向至新的自定义链接 /author/nickname -> /@nickname
        if(!array_key_exists('uc', $query_vars)){
            wp_redirect(home_url('/@' . $nickname), 301);
            exit;
        }

        // 对不不合法的/@nickname/xxx子路由，直接drop `author_name` 变量以引向404
        if(array_key_exists('uctab', $query_vars) && $uc_tab = $query_vars['uctab']){
            if($uc_tab === 'profile'){
                // @see func.Template.php - tt_get_user_template
                wp_redirect(home_url('/@' . $nickname), 301);
                exit;
            }elseif(!in_array($uc_tab, (array)json_decode(ALLOWED_UC_TABS))){
                unset($query_vars['author_name']);
                unset($query_vars['uctab']);
                unset($query_vars['uc']);
                $query_vars['error'] = '404';
                return $query_vars;
            }
        }

        // 新链接访问时 /@nickname
        global $wpdb;
        $author_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM {$wpdb->usermeta} WHERE `meta_key` = 'nickname' AND `meta_value` = %s ORDER BY user_id ASC LIMIT 1", sanitize_text_field($nickname)));
        if($author_id){
            $query_vars['author'] = $author_id;
            unset($query_vars['author_name']);
        }
        // 找不对匹配nickname的用户id则将nickname当作display_name解析 // TODO: 是否需要按此解析，可能导致不可预见的错误
        return $query_vars;
    }
    return $query_vars;
}
add_filter('request', 'tt_match_author_link_field', 10, 1);


/* Route : Me - e.g /me/notifications/all */

/**
 * /me主路由处理
 *
 * @since   2.0.0
 *
 * @return  void
 */
function tt_redirect_me_main_route(){
    if(is_user_logged_in() && preg_match('/^\/me([^\/]*)$/i', $_SERVER['REQUEST_URI'])){
        $nickname = get_user_meta(get_current_user_id(), 'nickname', true);
        wp_redirect(home_url('/@' . $nickname), 302);
        exit;
    }
}
add_action('init', 'tt_redirect_me_main_route'); //the `init` hook is typically used by plugins to initialize. The current user is already authenticated by this time.


/**
 * /me子路由处理 - Rewrite
 *
 * @since   2.0.0
 *
 * @param   object   $wp_rewrite   WP_Rewrite
 * @return  object
 */
function tt_handle_me_child_routes_rewrite($wp_rewrite){
    if(get_option('permalink_structure')){
        // Note: me子路由与孙路由必须字母组成，不区分大小写
        $new_rules['me/([a-zA-Z]+)$'] = 'index.php?me_child_route=$matches[1]&is_me_route=1';
        $new_rules['me/([a-zA-Z]+)/([a-zA-Z]+)$'] = 'index.php?me_child_route=$matches[1]&me_grandchild_route=$matches[2]&is_me_route=1';
        $new_rules['me/order/([0-9]{1,})$'] = 'index.php?me_child_route=order&me_grandchild_route=$matches[1]&is_me_route=1'; // 我的单个订单详情
        // 分页
        $new_rules['me/([a-zA-Z]+)/page/([0-9]{1,})$'] = 'index.php?me_child_route=$matches[1]&is_me_route=1&paged=$matches[2]';
        $new_rules['me/([a-zA-Z]+)/([a-zA-Z]+)/page/([0-9]{1,})$'] = 'index.php?me_child_route=$matches[1]&me_grandchild_route=$matches[2]&is_me_route=1&paged=$matches[3]';
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }
    return $wp_rewrite;
}
add_filter('generate_rewrite_rules', 'tt_handle_me_child_routes_rewrite');


/**
 * /me子路由处理 - Template
 *
 * @since   2.0.0
 *
 * @return  void
 */
function tt_handle_me_child_routes_template(){
    $is_me_route = strtolower(get_query_var('is_me_route'));
    $me_child_route = strtolower(get_query_var('me_child_route'));
    $me_grandchild_route = strtolower(get_query_var('me_grandchild_route'));
    if($is_me_route && $me_child_route){
        $allow_routes = (array)json_decode(ALLOWED_ME_ROUTES);
        $allow_child = array_keys($allow_routes);
        // 非法的子路由处理
        if(!in_array($me_child_route, $allow_child)){
            Utils::set404();
            return;
        }
        // 对于order/8单个我的订单详情路由，孙路由必须是数字
        if($me_child_route === 'order' && (!$me_grandchild_route || !preg_match('/([0-9]{1,})/', $me_grandchild_route))) return;
        if($me_child_route !== 'order'){
            $allow_grandchild = $allow_routes[$me_child_route];
            // 对于可以有孙路由的一般不允许直接子路由，必须访问孙路由，比如/me/notifications 必须跳转至/me/notifications/all
            if(empty($me_grandchild_route) && is_array($allow_grandchild)){
                wp_redirect(home_url('/me/' . $me_child_route . '/' . $allow_grandchild[0]), 301);
                exit;
            }
            // 非法孙路由处理
            if(!in_array($me_grandchild_route, $allow_grandchild)) {
                Utils::set404();
                return;
            }
        };
        $template = THEME_TPL . '/me/tpl.Me.' . ucfirst($me_child_route) . '.php';
        load_template($template);
        exit;
    }
}
add_action('template_redirect', 'tt_handle_me_child_routes_template', 5);


/**
 * 为自定义的当前用户页(Me)添加query_var白名单
 *
 * @since   2.0.0
 *
 * @param   object  $public_query_vars  公共全局query_vars
 * @return  object
 */
function tt_add_me_page_query_vars($public_query_vars) {
    if(!is_admin()){
        $public_query_vars[] = 'is_me_route';
        $public_query_vars[] = 'me_child_route';
        $public_query_vars[] = 'me_grandchild_route';
    }
    return $public_query_vars;
}
add_filter('query_vars', 'tt_add_me_page_query_vars');


/* Route : Action - e.g /m/signin */

/**
 * 登录/注册/注销等动作页路由(/m)
 *
 * @since   2.0.0
 *
 * @param   object  $wp_rewrite  WP_Rewrite
 * @return  void
 */
function tt_handle_action_page_rewrite_rules($wp_rewrite){
    if($ps = get_option('permalink_structure')){
        //action (signin|signup|signout|refresh)
        // m->move(action)
        $new_rules['m/([A-Za-z_-]+)$'] = 'index.php?action=$matches[1]';
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }
}
add_action('generate_rewrite_rules', 'tt_handle_action_page_rewrite_rules');


/**
 * 为自定义的Action页添加query_var白名单
 *
 * @since   2.0.0
 *
 * @param   object  $public_query_vars  公共全局query_vars
 * @return  object
 */
function tt_add_action_page_query_vars($public_query_vars) {
    if(!is_admin()){
        $public_query_vars[] = 'action'; // 添加参数白名单action，代表是各种动作页
    }
    return $public_query_vars;
}
add_filter('query_vars', 'tt_add_action_page_query_vars');


/**
 * 登录/注册/注销等动作页模板
 *
 * @since   2.0.0
 *
 * @return  void
 */
function tt_handle_action_page_template(){
    $action = strtolower(get_query_var('action'));
    $allowed_actions = (array)json_decode(ALLOWED_M_ACTIONS);
    if($action && in_array($action, array_keys($allowed_actions))){
        global $wp_query;
        $wp_query->is_home = false;
        $wp_query->is_page = true; //将该模板改为页面属性，而非首页
        $template = THEME_TPL . '/actions/tpl.M.' . ucfirst($allowed_actions[$action]) . '.php';
        load_template($template);
        exit;
    }elseif($action && !in_array($action, array_keys($allowed_actions))){
        // 非法路由处理
        Utils::set404();
        return;
    }
}
add_action('template_redirect', 'tt_handle_action_page_template', 5);


/* Route : OAuth - e.g /oauth/qq */

/**
 * OAuth登录处理页路由(/oauth)
 *
 * @since   2.0.0
 *
 * @param   object  $wp_rewrite  WP_Rewrite
 * @return  void
 */
function tt_handle_oauth_page_rewrite_rules($wp_rewrite){
    if($ps = get_option('permalink_structure')){
        //oauth (qq|weibo|weixin|...)
        $new_rules['oauth/([A-Za-z]+)$'] = 'index.php?oauth=$matches[1]';
        $new_rules['oauth/([A-Za-z]+)/last$'] = 'index.php?oauth=$matches[1]&oauth_last=1';
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }
}
add_action('generate_rewrite_rules', 'tt_handle_oauth_page_rewrite_rules');


/**
 * 为自定义的Action页添加query_var白名单
 *
 * @since   2.0.0
 *
 * @param   object  $public_query_vars  公共全局query_vars
 * @return  object
 */
function tt_add_oauth_page_query_vars($public_query_vars) {
    if(!is_admin()){
        $public_query_vars[] = 'oauth'; // 添加参数白名单oauth，代表是各种OAuth登录处理页
        $public_query_vars[] = 'oauth_last'; // OAuth登录最后一步，整合WP账户，自定义用户名
    }
    return $public_query_vars;
}
add_filter('query_vars', 'tt_add_oauth_page_query_vars');


/**
 * OAuth登录处理页模板
 *
 * @since   2.0.0
 *
 * @return  void
 */
function tt_handle_oauth_page_template(){
    $oauth = strtolower(get_query_var('oauth'));
    $oauth_last = get_query_var('oauth_last');
    if($oauth){
        if(in_array($oauth, (array)json_decode(ALLOWED_OAUTH_TYPES))):
            global $wp_query;
            $wp_query->is_home = false;
            $wp_query->is_page = true; //将该模板改为页面属性，而非首页
            $template = $oauth_last ? THEME_TPL . '/oauth/tpl.OAuth.Last.php' : THEME_TPL . '/oauth/tpl.OAuth.php';
            load_template($template);
            exit;
        else:
            // 非法路由处理
            Utils::set404();
            return;
        endif;
    }
}
add_action('template_redirect', 'tt_handle_oauth_page_template', 5);


/* Route : Site - e.g /site/upgradebrowser */

/**
 * 网站级工具页路由(如浏览器升级提示、全站通告等)(/site)
 *
 * @since   2.0.0
 *
 * @param   object  $wp_rewrite  WP_Rewrite
 * @return  void
 */
function tt_handle_site_util_page_rewrite_rules($wp_rewrite){
    if($ps = get_option('permalink_structure')){
        //site_util (upgradeBrowser)
        $new_rules['site/([A-Za-z_-]+)$'] = 'index.php?site_util=$matches[1]';
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }
}
add_action('generate_rewrite_rules', 'tt_handle_site_util_page_rewrite_rules');


/**
 * 为自定义的Site Util页添加query_var白名单
 *
 * @since   2.0.0
 *
 * @param   object  $public_query_vars  公共全局query_vars
 * @return  object
 */
function tt_add_site_util_page_query_vars($public_query_vars) {
    if(!is_admin()){
        $public_query_vars[] = 'site_util'; // site_util，代表是网站级别的工具页面
    }
    return $public_query_vars;
}
add_filter('query_vars', 'tt_add_site_util_page_query_vars');


/**
 * 网站级工具页模板
 *
 * @since   2.0.0
 *
 * @return  void
 */
function tt_handle_site_util_page_template(){
    $util = get_query_var('site_util');
    $allowed_utils = (array)json_decode(ALLOWED_SITE_UTILS);
    if($util && in_array(strtolower($util), array_keys($allowed_utils))){
        global $wp_query;
        $wp_query->is_home = false;
        $wp_query->is_page = true; //将该模板改为页面属性，而非首页
        $template = THEME_TPL . '/site/tpl.' . ucfirst(strtolower($allowed_utils[$util])) . '.php';
        load_template($template);
        exit;
    }
}
add_action('template_redirect', 'tt_handle_site_util_page_template', 5);


/* Route : Static - e.g /static/css/main.css */

/**
 * 静态路由，去除静态文件链接中的wp-content等字样(/static)
 *
 * @since   2.0.0
 *
 * @param   object  $wp_rewrite  WP_Rewrite
 * @return  void
 */
function tt_handle_static_file_rewrite_rules($wp_rewrite){
    if($ps = get_option('permalink_structure')){
        $explode_path = explode('/themes/', THEME_DIR);
        $theme_name = next($explode_path);
        //static files route
        $new_rules = array(
            'static/(.*)' => 'wp-content/themes/' . $theme_name . '/assets/$1'
        );
        $wp_rewrite->non_wp_rules = $new_rules + $wp_rewrite->non_wp_rules;
    }
}
//add_action('generate_rewrite_rules', 'tt_handle_static_file_rewrite_rules');  // TODO: 需要Apache支持，或者同样Nginx对应方法


/* Route : API - e.g /api/post/1 */

/**
 * REST API路由，wp-json路由别名(/api)
 *
 * @since   2.0.0
 *
 * @param   object  $wp_rewrite  WP_Rewrite
 * @return  void
 */
function tt_handle_api_rewrite_rules($wp_rewrite){
    if($ps = get_option('permalink_structure')){
        $new_rules = array();
        $new_rules['^api/?$'] = 'index.php?rest_route=/';
        $new_rules['^api/(.*)?'] = 'index.php?rest_route=/$matches[1]';
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }
}
//add_action('generate_rewrite_rules', 'tt_handle_api_rewrite_rules'); //直接用 `rest_url_prefix` 更改wp-json至api @see core/api/api.Config.php


/**
 * 刷新固定链接缓存
 *
 * @since   2.0.0
 *
 * @return  void
 */
function tt_refresh_rewrite(){
    // 如果启用了memcache等对象缓存，固定链接的重写规则缓存对应清除
    wp_cache_flush();

    // 刷新固定链接
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}