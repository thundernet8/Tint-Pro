<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/06/23 18:43
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/**
 * 不可归类工具
 */


/**
 * 根据name获取主题设置(of_get_option别名函数)
 *
 * @since   2.0.0
 *
 * @access  global
 * @param   string  $name     设置ID
 * @param   mixed   $default    默认值
 * @return  mixed   具体设置值
 */
function tt_get_option( $name, $default='' ){
    return of_get_option( $name, $default );
}

// TODO: Utils::function_name -> tt_function_name

// TODO: tt_url_for
/**
 * 获取各种Url
 *
 * @since   2.0.0
 *
 * @param   string  $key    待查找路径的关键字
 * @param   mixed   $arg    接受一个参数，用于动态链接(如一个订单号，一个用户昵称，一个用户id或者一个用户对象)
 * @param   bool    $relative   是否使用相对路径
 * @return  string | false
 */
function tt_url_for($key, $arg = null, $relative = false){
    $routes = (array)json_decode(SITE_ROUTES);
    if(array_key_exists($key, $routes)){
        return $relative ? '/' . $routes[$key] : home_url('/' . $routes[$key]);
    }
    $endpoint = null;
    $uc_func = function($arg){
        $nickname = null;
        if(is_string($arg)){
            $nickname = $arg;
        }elseif(is_int($arg) && !!$arg){
            $nickname = get_user_meta($arg, 'nickname', true);
        }elseif($arg instanceof WP_User){
            $nickname = get_user_meta($arg->ID, 'nickname', true);
        }
        return $nickname;
    };
    switch ($key){
        case 'my_order':
            $endpoint = 'order/' . (int)$arg;
            break;
        case 'uc_comments':
            $nickname = call_user_func($uc_func, $arg);
            if($nickname) $endpoint = '@' . $nickname . '/comments';
            break;
        case 'uc_profile':
            $nickname = call_user_func($uc_func, $arg);
            if($nickname) $endpoint = '@' . $nickname;
            break;
        case 'uc_me':
            $nickname = get_user_meta(get_current_user_id(), 'nickname', true);
            if($nickname) $endpoint = '@' . $nickname;
            break;
        case 'uc_latest':
            $nickname = call_user_func($uc_func, $arg);
            if($nickname) $endpoint = '@' . $nickname . '/latest';
            break;
        case 'uc_recommend':
            $nickname = call_user_func($uc_func, $arg);
            if($nickname) $endpoint = '@' . $nickname . '/recommendations';
            break;
        case 'oauth_qq_last':
            $endpoint = $routes['oauth_qq'] . '/last';
            break;
        case 'oauth_weibo_last':
            $endpoint = $routes['oauth_weibo'] . '/last';
            break;
        case 'oauth_weixin_last':
            $endpoint = $routes['oauth_weixin'] . '/last';
            break;
    }
    if($endpoint){
        return $relative ? '/' . $endpoint : home_url('/' . $endpoint);
    }
    return false;
}