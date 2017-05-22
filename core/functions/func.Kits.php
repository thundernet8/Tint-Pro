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
 * @link https://webapproach.net/tint.html
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

    // 输入参数$arg为user时获取其ID使用
    $get_uid = function($var){
        if($var instanceof WP_User){
            return $var->ID;
        }else{
            return intval($var);
        }
    };

    $endpoint = null;
    switch ($key){
        case 'my_order':
            $endpoint = 'me/order/' . (int)$arg;
            break;
        case 'uc_comments':
            $endpoint = 'u/' . call_user_func($get_uid, $arg) . '/comments';
            break;
        case 'uc_profile':
            $endpoint = 'u/' . call_user_func($get_uid, $arg);
            break;
        case 'uc_me':
            $endpoint = 'u/' . get_current_user_id();
            break;
        case 'uc_latest':
            $endpoint = 'u/' . call_user_func($get_uid, $arg) . '/latest';
            break;
        case 'uc_stars':
            $endpoint = 'u/' . call_user_func($get_uid, $arg) . '/stars';
            break;
        case 'uc_followers':
            $endpoint = 'u/' . call_user_func($get_uid, $arg) . '/followers';
            break;
        case 'uc_following':
            $endpoint = 'u/' . call_user_func($get_uid, $arg) . '/following';
            break;
        case 'uc_activities':
            $endpoint = 'u/' . call_user_func($get_uid, $arg) . '/activities';
            break;
        case 'uc_chat':
            $endpoint = 'u/' . call_user_func($get_uid, $arg) . '/chat';
            break;
        case 'manage_user':
            $endpoint = 'management/users/' . intval($arg);
            break;
        case 'manage_order':
            $endpoint = 'management/orders/' . intval($arg);
            break;
        case 'shop_archive':
            $endpoint = tt_get_option('tt_product_archives_slug', 'shop');
            break;
        case 'edit_post':
            $endpoint = 'me/editpost/' . absint($arg);
            break;
        case 'download':
            $endpoint = 'site/download?_=' . urlencode(rtrim(tt_encrypt($arg, tt_get_option('tt_private_token')), '='));
            break;
    }
    if($endpoint){
        return $relative ? '/' . $endpoint : home_url('/' . $endpoint);
    }
    return false;
}


/**
 * 获取当前页面url
 *
 * @since   2.0.0
 * @param   string  $method    获取方法，分别为PHP的$_SERVER对象获取(php)和WordPress的全局wp_query对象获取(wp)
 * @return  string
 */
function tt_get_current_url($method = 'php') {
    if($method === 'wp') {
        return Utils::getCurrentUrl();
    }
    return Utils::getPHPCurrentUrl();
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
function tt_signout_url($redirect = '') {
    if(empty($redirect)) {
        $redirect = home_url();
    }
    return tt_filter_default_logout_url('', $redirect);
}


/**
 * 为链接添加重定向链接
 *
 * @since   2.0.0
 * @param   string  $url
 * @param   string  $redirect
 * @return  string
 */
function tt_add_redirect($url, $redirect = '') {
    if($redirect) {
        return add_query_arg('redirect_to', urlencode($redirect), $url);
    }elseif(isset($_GET['redirect_to'])){
        return add_query_arg('redirect_to', urlencode(esc_url_raw($_GET['redirect_to'])), $url);
    }elseif(isset($_GET['redirect'])){
        return add_query_arg('redirect_to', urlencode(esc_url_raw($_GET['redirect'])), $url);
    }
    return add_query_arg('redirect_to', urlencode(home_url()), $url);
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
    if(is_numeric($data)){
        $data = strval($data);
    }else{
        $data = maybe_serialize($data);
    }
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


/**
 * 获取当前页面需要应用的样式链接
 *
 * @since   2.0.0
 * @param   string  $filename  文件名
 * @return  string
 */
function tt_get_css($filename = '') {
    if($filename) {
        return THEME_CDN_ASSET . '/css/' . $filename;
    }

    if(is_home()) {
        $filename = CSS_HOME;
    }elseif(is_single()) {
        $filename = get_post_type()==='product' ? CSS_PRODUCT : (get_post_type()==='bulletin' ? CSS_PAGE : CSS_SINGLE);
    }elseif((is_archive() && !is_author()) || (is_search() && isset($_GET['in_shop']) && $_GET['in_shop'] == 1)) {
        $filename = get_post_type()==='product' || (is_search() && isset($_GET['in_shop']) && $_GET['in_shop'] == 1) ? CSS_PRODUCT_ARCHIVE : CSS_ARCHIVE;
    }elseif(is_author()) {
        $filename = CSS_UC;
    }elseif(is_404()) {
        $filename = CSS_404;
    }elseif(get_query_var('is_me_route')) {
        $filename = CSS_ME;
    }elseif(get_query_var('action')) {
        $filename = CSS_ACTION;
    }elseif(is_front_page()) {
        $filename = CSS_FRONT_PAGE;
    }elseif(get_query_var('site_util')){
        $filename = CSS_SITE_UTILS;
    }elseif(get_query_var('oauth')){
        $filename = CSS_OAUTH;
    }elseif(get_query_var('is_manage_route')){
        $filename = CSS_MANAGE;
    }else{
        // is_page() ?
        $filename = CSS_PAGE;
    }
    return THEME_CDN_ASSET . '/css/' . $filename;
}


/**
 * 条件判断类名
 *
 * @param $base_class
 * @param $condition
 * @param string $active_class
 * @return string
 */
function tt_conditional_class($base_class, $condition, $active_class = 'active') {
    if($condition) {
        return $base_class . ' ' . $active_class;
    }
    return $base_class;
}


/**
 * 二维码API
 *
 * @since 2.0.0
 * @param $text
 * @param $size
 * @return string
 */
function tt_qrcode($text, $size) {
    //TODO size
    return tt_url_for('qr') . '?text=' . $text;
}

/**
 * 页脚年份
 *
 * @since 2.0.0
 * @return string
 */
function tt_copyright_year(){
    $now_year = date('Y');
    $open_date = tt_get_option('tt_site_open_date', $now_year);
    $open_year = substr($open_date, 0, 4);

    return $open_year . '-' . $now_year . '&nbsp;&nbsp;';
}


/**
 * 生成推广链接
 *
 * @param int $user_id
 * @param string $base_link
 * @return string
 */
function tt_get_referral_link($user_id = 0, $base_link = ''){
    if(!$base_link) $base_link = home_url();
    if(!$user_id) $user_id = get_current_user_id();

    return add_query_arg(array('ref' => $user_id), $base_link);
}


/**
 * 获取GET方法http响应状态代码
 *
 * @since 2.0.0
 * @param $theURL
 * @return string
 */
function tt_get_http_response_code($theURL) {
    @$headers = get_headers($theURL);
    return substr($headers[0], 9, 3);
}


/**
 * 过滤multicheck选项的设置值
 *
 * @since 2.0.5
 * @param $option
 * @return array
 */
function tt_filter_of_multicheck_option($option) {
    // 主题选项框架获得multicheck类型选项的值为 array(id => bool), 而我们需要的是bool为true的array(id)
    if(!is_array($option)) {
        return $option;
    }

    $new_option = array();
    foreach ($option as $key => $value) {
        if($value) {
            $new_option[] = $key;
        }
    }
    return $new_option;
}


/**
 * 分页
 *
 * @param $base
 * @param $current
 * @param $max
 */
function tt_default_pagination($base, $current, $max) {
?>
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php $pagination = paginate_links(array(
                'base' => $base,
                'format' => '?paged=%#%',
                'current' => $current,
                'total' => $max,
                'type' => 'array',
                'prev_next' => true,
                'prev_text' => '<i class="tico tico-angle-left"></i>',
                'next_text' => '<i class="tico tico-angle-right"></i>'
            )); ?>
            <?php foreach ($pagination as $page_item) {
                echo '<li class="page-item">' . $page_item . '</li>';
            } ?>
        </ul>
        <div class="page-nums">
            <span class="current-page"><?php printf(__('Current Page %d', 'tt'), $current); ?></span>
            <span class="separator">/</span>
            <span class="max-page"><?php printf(__('Total %d Pages', 'tt'), $max); ?></span>
        </div>
    </nav>
<?php
}


/**
 * 分页
 *
 * @param $base
 * @param $current
 * @param $max
 */
function tt_pagination($base, $current, $max) {
    ?>
    <nav class="pagination-new">
        <ul>
            <?php $pagination = paginate_links(array(
                'base' => $base,
                'format' => '?paged=%#%',
                'current' => $current,
                'total' => $max,
                'type' => 'array',
                'prev_next' => true,
                'prev_text' => '<span class="prev">' . __('PREV PAGE', 'tt') . '</span>',
                'next_text' => '<span class="next">' . __('NEXT PAGE', 'tt') . '</span>'
            )); ?>
            <?php foreach ($pagination as $page_item) {
                echo '<li class="page-item">' . $page_item . '</li>';
            } ?>
            <li class="page-item"><span class="max-page"><?php printf(__('Total %d Pages', 'tt'), $max); ?></span></li>
        </ul>
    </nav>
    <?php
}