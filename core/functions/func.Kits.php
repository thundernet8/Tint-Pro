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
    }
    if($endpoint){
        return $relative ? '/' . $endpoint : home_url('/' . $endpoint);
    }
    return false;
}


/**
 * 登录的url
 *
 * @since   2.0.0
 *
 * @param   string  $redirect  重定向链接，未url encode
 * @return  string
 */
function tt_signin_url($redirect) {
    return tt_filter_default_login_url('', $redirect);
}


/**
 * 注册的url
 *
 * @since   2.0.0
 *
 * @param   string  $redirect  重定向链接，未url encode
 * @return  string
 */
function tt_signup_url($redirect) {
    $signup_url = tt_url_for('signup');

    if ( !empty($redirect) ) {
        $signup_url = add_query_arg('redirect_to', urlencode($redirect), $signup_url);
    }
    return $signup_url;
}


/**
 * 注销的url
 *
 * @since   2.0.0
 *
 * @param   string  $redirect  重定向链接，未url encode
 * @return  string
 */
function tt_signout_url($redirect) {
    return tt_filter_default_logout_url('', $redirect);
}


/**
 * 可逆加密
 *
 * @since   2.0.0
 *
 * @param   mixed   $data   待加密数据
 * @param   string  $key    加密密钥
 * @return  string
 */
function tt_encrypt($data, $key) {
    $data = maybe_serialize($data);
    $key = md5($key);
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);
    $char = $str = '';
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) {
            $x = 0;
        }
        $char .= $key{$x};
        $x++;
    }
    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
    }
    return base64_encode($str);
}

/**
 * 解密
 *
 * @since   2.0.0
 *
 * @param   string  $data   待解密数据
 * @param   string  $key    密钥
 * @return  mixed
 */
function tt_decrypt($data, $key) {
    $key = md5($key);
    $x = 0;
    $data = base64_decode($data);
    $len = strlen($data);
    $l = strlen($key);
    $char = $str = '';
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) {
            $x = 0;
        }
        $char .= substr($key, $x, 1);
        $x++;
    }
    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        }else{
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return maybe_unserialize($str);
}


/**
 * 加密解密数据
 *
 * @since   2.0.0
 *
 * @param   mixed   $data   待加密数据
 * @param   string  $operation  操作(加密|解密)
 * @param   string  $key    密钥
 * @param   int     $expire     过期时间
 * @return  string
 */
function tt_authdata($data, $operation = 'DECODE', $key = '', $expire = 0) {
    if($operation != 'DECODE'){
        $data = maybe_serialize($data);
    }
    $ckey_length = 4;
    $key = md5($key ? $key : 'null');
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($data, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);
    $data = $operation == 'DECODE' ? base64_decode(substr($data, $ckey_length)) : sprintf('%010d', $expire ? $expire + time() : 0) . substr(md5($data . $keyb), 0, 16) . $data;
    $string_length = strlen($data);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($data[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if($operation == 'DECODE') {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return maybe_unserialize(substr($result, 26));
        } else {
            return false;
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}


/**
 * 替换默认的wp_die处理函数
 *
 * @since   2.0.0
 *
 * @param   string | WP_Error  $message    错误消息
 * @param   string  $title      错误标题
 * @param   array   $args       其他参数
 */
function tt_wp_die_handler($message, $title = '', $args = array()) {
    $defaults = array( 'response' => 500 );
    $r = wp_parse_args($args, $defaults);

    if ( function_exists( 'is_wp_error' ) && is_wp_error( $message ) ) {
        if ( empty( $title ) ) {
            $error_data = $message->get_error_data();
            if ( is_array( $error_data ) && isset( $error_data['title'] ) )
                $title = $error_data['title'];
        }
        $errors = $message->get_error_messages();
        switch ( count( $errors ) ) {
            case 0 :
                $message = '';
                break;
            case 1 :
                $message = "{$errors[0]}";
                break;
            default :
                $message = "<ul>\n\t\t<li>" . join( "</li>\n\t\t<li>", $errors ) . "</li>\n\t</ul>";
                break;
        }
    }

    if ( ! did_action( 'admin_head' ) ) :
        if ( !headers_sent() ) {
            status_header( $r['response'] );
            nocache_headers();
            header( 'Content-Type: text/html; charset=utf-8' );
        }

        if ( empty($title) )
            $title = __('WordPress &rsaquo; Error');

        $text_direction = 'ltr';
        if ( isset($r['text_direction']) && 'rtl' == $r['text_direction'] )
            $text_direction = 'rtl';
        elseif ( function_exists( 'is_rtl' ) && is_rtl() )
            $text_direction = 'rtl';

        // 引入自定义模板
        global $wp_query;
        $wp_query->query_vars['die_title'] = $title;
        $wp_query->query_vars['die_msg'] = $message;
        include_once THEME_TPL . '/tpl.Error.php';
    endif;

    die();
}
function tt_wp_die_handler_switch(){
    return 'tt_wp_die_handler';
}
add_filter('wp_die_handler', 'tt_wp_die_handler_switch');
