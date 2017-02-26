<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/01 20:17
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class Open 开放平台登录
 */
abstract class Open{

    /**
     * 存储重定向链接的cookie名
     *
     * @since   2.0.0
     *
     * @static
     * @var string
     */
    protected static $_redirect_cookie_name = 'tt_oauth_redirect';


    /**
     * 保存oauth获取的token等信息的缓存key
     *
     * @since 2.0.0
     * @var string
     */
    protected $_data_transient_key;

    /**
     * 是否启用该平台登录
     *
     * @since   2.0.0
     *
     * @var bool|mixed
     */
    protected $_open_enabled = false;

    /**
     * 开放平台申请的应用ID
     *
     * @since   2.0.0
     *
     * @var mixed|null
     */
    protected $_openkey = null; // QQ的是openid

    /**
     * 开放平台申请的应用密钥
     *
     * @since   2.0.0
     *
     * @var mixed|null
     */
    protected $_opensecret = null; // QQ的是openkey

    /**
     * @var string 开放平台类型
     */
    protected static $_type = '';

    /**
     * @var string 用于获取开放平台开启状态的主题选项key
     */
    protected static $_status_option_name = '';

    /**
     * @var string 用于获取开放平台open key的主题选项key
     */
    protected static $_openkey_option_name = '';

    /**
     * @var string 用于获取开放平台open secret的主题选项key
     */
    protected static $_opensecret_option_name = '';

    /**
     * @var string 用于获取和保存开放平台openid的meta key
     */
    protected static $_openid_meta_key = '';

    /**
     * @var string 用于获取和保存开放平台access token的meta key
     */
    protected static $_access_token_meta_key = '';

    /**
     * @var string 用于获取和保存开放平台refresh token的meta key
     */
    protected static $_refresh_token_meta_key = '';

    /**
     * @var string 用于获取和保存开放平台token expiration的meta key
     */
    protected static $_token_expiration_meta_key = '';

    /**
     * @var string 用于储存state的cookie名
     */
    protected static $_state_cookie_name = '';

    /**
     * @var string 用于储存回调地址的key
     */
    protected static $_callback_url_key = '';

    /**
     * @var string oauth最后一步url key
     */
    protected static $_oauth_last_url_key = '';

    /**
     * WP用户实例
     *
     * @since   2.0.0
     *
     * @var WP_User
     */
    protected $_user;

    /**
     * 错误对象
     *
     * @since   2.0.0
     *
     * @var object
     */
    protected $_error;

    /**
     * Open constructor.
     *
     * @since   2.0.0
     *
     * @param int|WP_User $user_or_id
     */
    public function __construct($user_or_id = null){

        $this->_open_enabled = tt_get_option(static::$_status_option_name, false);
        $this->_openkey = tt_get_option(static::$_openkey_option_name);
        $this->_opensecret = tt_get_option(static::$_opensecret_option_name);

        if($user_or_id){
            $this->_user = ($user_or_id instanceof WP_User) ? $user_or_id : get_user_by('id', (int)$user_or_id);
        }else{
            $this->_user = wp_get_current_user();
        }
    }

    /**
     * 获取对应开放平台的名称
     *
     * @since 2.0.0
     *
     * @return string
     */
    protected function getTypeString(){
        switch (static::$_type){
            case 'qq':
                return __('QQ', 'tt');
            case 'weibo':
                return __('Weibo', 'tt');
            case 'weixin':
                return __('Weixin', 'tt');
            default:
                return '';
        }
    }

    /**
     * 返回错误对象
     *
     * @since   2.0.0
     *
     * @return object
     */
    public function getError(){
        return $this->_error;
    }

    /**
     * 获取重定向链接
     *
     * @since   2.0.0
     *
     * @return string
     */
    protected static function getRedirect(){
        if( isset($_GET['redirect']) ) return urldecode($_GET['redirect']);
        if( isset($_GET['redirect_uri']) ) return urldecode($_GET['redirect_uri']);
        if( isset($_GET['redirect_to']) ) return urldecode($_GET['redirect_to']);
        if( isset($_COOKIE[self::$_redirect_cookie_name]) ) return urldecode($_COOKIE[self::$_redirect_cookie_name]);
        if( isset($_SERVER['HTTP_REFERER']) ) return urldecode($_SERVER['HTTP_REFERER']);
        return home_url();
    }

    /**
     * 设置重定向链接的cookie
     *
     * @since   2.0.0
     * @return string
     */
    protected static function setRedirectCookie(){
        $redirect = self::getRedirect();
        setcookie(self::$_redirect_cookie_name, urlencode($redirect), time()+60*10);
        return $redirect;
    }

    /**
     * 设置state的cookie，state是用于oauth请求防止CSRF攻击的状态码
     *
     * @since   2.0.0
     *
     * @param string $schema 区分码
     * @return string
     */
    protected static function setStateCookie($schema = null){
        $schema = $schema ? $schema : strval(rand());
        $state = md5(uniqid($schema, true));
        setcookie(static::$_state_cookie_name, $state, time()+60*10);
        return $state;
    }

    /**
     * 获取回调地址
     *
     * @since   2.0.0
     *
     * @param   array  $args   额外的Redirect URL参数，键值对数组
     * @return string
     */
    protected static function getCallbackUrl($args = null){

        if(is_array($args)){
            $redirect = add_query_arg($args, self::getRedirect());
        }else{
            $redirect = self::getRedirect();
        }
        return esc_url( add_query_arg('redirect', urlencode($redirect), tt_url_for(static::$_callback_url_key)) );
    }

    /**
     * 判断该开放平台登录是否可用
     *
     * @since   2.0.0
     *
     * @return bool
     */
    protected function isOpenAvailable(){
        return $this->_open_enabled && $this->_openkey && $this->_opensecret;
    }

    /**
     * 判断当前已登录用户是否已经连接过该开放平台
     *
     * @since   2.0.0
     *
     * @param   int    $user_id    用户id
     * @return bool
     */
    public function isOpenConnected($user_id = 0){
        // 判断该第三方账号是否已经授权过博客的登录(初始只能判断当前已登录的用户，对于开放平台连接了WP系统内其他用户的暂时无法判断，需要获取openid之后判断)
        $user = $this->_user;
        if(!$user){
            // 未登录用户时，不用管开放平台openid已被系统内任一用户使用
            return false;
        }elseif($user_id && $user->ID !== $user_id){
            return true;
        }
        return get_user_meta($user->ID, static::$_openid_meta_key, true) && get_user_meta($user->ID, static::$_access_token_meta_key, true);
    }

    /**
     * 开放平台登录前检查并输出错误
     *
     * @since   2.0.0
     *
     * @param string $schema 检查类别
     * @param int   $user_id    用户id
     * @return bool
     */
    protected function checkOpen($schema = 'enable_check', $user_id = 0){
        $openTypeString = $this->getTypeString();
        switch ($schema){
            case 'duplication_check':

                if($this->isOpenConnected()){
                    $this->_error = (object)array(
                        'title' => sprintf(__('Can Not Bind %s Again', 'tt'), $openTypeString),
                        'message' => sprintf(__('You have connected with %s before, can not do it again, please unbind it before if need', 'tt'), $openTypeString),
                        'code'  => 'duplicated_connect'
                    );
                    return false;
                }
                return true;
                break;
            case 'enable_check':

                if( !($this->isOpenAvailable()) ){
                    $this->_error = (object)array(
                        'title' => sprintf(__('%s Login Disabled', 'tt'), $openTypeString),
                        'message' => sprintf(__('You have not enabled %s login, or the required information e.g OpenID, OpenKey missed', 'tt'), $openTypeString),
                        'code'  => 'disabled_connect'
                    );
                    return false;
                }
                return true;
                break;
            case 'occupation_check':

                if($this->isOpenConnected($user_id)){
                    $this->_error = (object)array(
                        'title' => sprintf(__('%s connected by other one', 'tt'), $openTypeString),
                        'message' => sprintf(__('Someone in your WordPress user system have connected with this %s before, please unbind it before if need', 'tt'), $openTypeString),
                        'code'  => 'occupied_connect'
                    );
                    return false;
                }
                return true;
                break;
            default:
                return true;
                break;
        }
    }

    /**
     * 检查state状态码
     *
     * @since   2.0.0
     *
     * @return bool
     */
    protected function checkState(){
        if(!isset($_GET['state'])){
            $this->_error = (object)array(
                'title' => __('State Missing', 'tt'),
                'message' => __('The state parameter was not found in the url', 'tt'),
                'code'  => 'state_missing'
            );
            return false;
        }
        if(!isset($_COOKIE[static::$_state_cookie_name]) || $_COOKIE[static::$_state_cookie_name] !== $_GET['state']){
            $this->_error = (object)array(
                'title' => __('State Incorrect', 'tt'),
                'message' => __('The state parameter in the url is incorrect', 'tt'),
                'code'  => 'state_incorrect'
            );
            return false;
        }

        return true;
    }

    /**
     * 鉴权，获取code
     *
     * @since   2.0.0
     *
     * @return bool
     */
    abstract protected function authenticate();

    /**
     * 认证，获取access token，抓取用户信息并尝试接入登录
     *
     * @since   2.0.0
     *
     * @param string $code  鉴权阶段成功后返回的code，用于认证步骤
     * @param string $state 状态码，加强CSRF防护
     * @return bool
     */
    abstract protected function authorize($code, $state);

    /**
     * 抓取开放平台用户信息
     *
     * @since   2.0.0
     *
     * @param   string  $access_token   Access Token
     * @param   string  $openid     用户在开放平台的openid(QQ的openid此时没有，微信可以提供)
     * @return array|bool|mixed|object
     */
    abstract protected function getOpenUser($access_token, $openid);

    /**
     * 刷新Access Token
     *
     * @since   2.0.0
     *
     * @return mixed
     */
    abstract protected function refreshToken();


    /**
     * 开放平台连接将要成功时将一些token/openID以及可用的资料信息保存到用户的meta
     *
     * @since 2.0.0
     *
     * @param $user_id
     * @param $data
     */
    protected function saveOpenInfoAndProfile($user_id, $data){
        // 将Open数据存储到对应用户meta
        update_user_meta($user_id, static::$_openid_meta_key, $data['openid']);
        update_user_meta($user_id, static::$_access_token_meta_key, $data['access_token']);
        update_user_meta($user_id, static::$_refresh_token_meta_key, $data['refresh_token']);
        update_user_meta($user_id, static::$_token_expiration_meta_key, $data['expiration']);

        if(static::$_type === 'weixin'){
            update_user_meta($user_id, 'tt_weixin_avatar', $data['headimgurl']);
            update_user_meta($user_id, 'tt_weixin_unionid', $data['unionid']);
            update_user_meta($user_id, 'tt_user_country', $data['country']); // 国家，如中国为CN
            update_user_meta($user_id, 'tt_user_province', $data['province']); // 普通用户个人资料填写的省份
            update_user_meta($user_id, 'tt_user_city', $data['city']); // 普通用户个人资料填写的城市
            update_user_meta($user_id, 'tt_user_sex', $data['sex']==2 ? 'female' : 'male'); // 普通用户性别，1为男性，2为女性
        }

        if(static::$_type === 'weibo'){
            update_user_meta($user_id, 'tt_weibo_avatar', $data['avatar_large']);
            update_user_meta($user_id, 'tt_weibo_profile_img', $data['profile_image_url']);
            update_user_meta($user_id, 'tt_weibo_id', $data['id']);
            update_user_meta($user_id, 'tt_user_description', $data['description']);
            update_user_meta($user_id, 'tt_user_location', $data['location']);
            update_user_meta($user_id, 'tt_user_sex', $data['sex']!='m' ? 'female' : 'male'); // 普通用户性别，m为男性，f为女性
        }

        // 使用开放平台头像
        tt_update_user_avatar_by_oauth($user_id, static::$_type);
    }

    /**
     * 获取必要的信息完成后尝试登入并连接WP用户系统
     * @param string $openid 用户openid
     * @param string $access_token Access Token
     * @param string $refresh_token Refresh Token
     * @param int $expiration 过期时间戳
     * @param object $info  获取的用户信息
     * @return bool
     */
    protected function openSignIn($openid, $access_token, $refresh_token, $expiration, $info){
        setcookie(static::$_state_cookie_name, null); // 删除state的cookie

        $data = (array)$info;
        $data['openid'] = $openid;
        $data['access_token'] = $access_token;
        $data['refresh_token'] = $refresh_token;
        $data['expiration'] = $expiration;
        $data['name'] = $info->name;

        global $wpdb;
        $user_exist = $wpdb->get_var( $wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key=%s AND meta_value=%s", static::$_openid_meta_key, $openid) );

        // 对于已登录了原WP系统的用户，在鉴权阶段就检查了是否存在openid，所以这里$user_exist不会为已登录用户id，无需为当前用户更新已获取的openid等信息
        // 如果当前用户已登录，而$user_exist存在，即该开放平台账号连接被其他用户占用了，不能再重复绑定了
        $current_user_id = get_current_user_id();
        if( $current_user_id != 0 && isset($user_exist) && $current_user_id != $user_exist && !($this->checkOpen('occupation_check', $user_exist)) ) return false;

        if( isset($user_exist) && (int)$user_exist>0 ){
            // 该开放平台账号已连接过WP系统，再次使用它登录并更新相关信息
            $user_exist = (int)$user_exist;
            $this->saveOpenInfoAndProfile($user_exist, $data);

            //update_user_meta( $user_exist, 'tt_latest_login', current_time( 'mysql' ) ); // 由wp_login钩子去做
            wp_set_current_user( $user_exist );
            wp_set_auth_cookie( $user_exist );

            $user_login = get_userdata($user_exist)->user_login;
            do_action( 'wp_login', $user_login );  // 保证挂载的action执行

            wp_safe_redirect(self::getRedirect());
            exit;
        }elseif($current_user_id){
            // Open 连接未被占用且当前已登录了本地账号, 那么直接绑定信息到该账号 case: 从个人资料设置中点击了绑定社交账号等操作
            $this->saveOpenInfoAndProfile($current_user_id, $data);

            wp_safe_redirect(self::getRedirect());
            exit;
        }else{
            // 该开放平台账号未连接过WP系统，使用它登录并分配和绑定一个WP本地新用户
            // 为了方便用户自主定义一些用户信息，需跳转至/oauth/[type]/last页面提示用户输入必要信息(GET跳转)
            // 安全起见， 数据序列化后保存在WP缓存中，由下个页面取出
            $_data_transient_key = md5('tt_oauth_temp_data_' . strtolower(Utils::generateRandomStr(10, 'letter')));
            set_transient($_data_transient_key, maybe_serialize($data), 60*10); // 10分钟缓存过期时间

            wp_safe_redirect(add_query_arg(array('redirect' => urlencode(self::getRedirect()), 'key' => $_data_transient_key), tt_url_for(static::$_oauth_last_url_key)));
            exit;
        }

    }

    /**
     * 开放平台链接需要新建本地用户的，收集必要的信息(从之前步骤设置的缓存中)
     *
     * @since   2.0.0
     *
     * @param bool $delete_cache    是否删除缓存
     * @return array|bool
     */
    protected function openSignUpPrepare($delete_cache = false){
        // 尝试获取存放在缓存的OAuth数据，保证流程正确
        $_data_transient_key = $this->_data_transient_key;
        if($_data_transient_key && $cache_data = get_transient($_data_transient_key)){
            $data = (array)maybe_unserialize($cache_data);
            // $user_login = strtoupper(static::$_type) . $openid; // 有暴露openid风险
            $user_login = strtoupper(static::$_type) . Utils::generateRandomStr(6, 'letter'); // 使用随机字符
            $data['user_login'] = $user_login;

            if($delete_cache){
                // 删除OAuth数据缓存
                //delete_transient($_data_transient_key); //TODO uncomment
            }

            // 返回随机生成的用户名填充输入框默认值
            return $data;
        }
        $this->_error = (object)array(
            'title' => __('OAuth Data Not Exist', 'tt'),
            'message' => __('Can not get oauth data, please retry the oauth steps', 'tt'),
            'code' => 'oauth_cache_data_miss'
        );

        return false;
    }

    /**
     * 处理开放平台连接需要新建本地用户请求
     *
     * @since   2.0.0
     *
     * @param   string  $user_login  用户登录名(以邮箱做用户名)
     * @param   string  $password   用户密码
     * @return  bool|string|WP_Error
     */
    protected function openSignUp($user_login, $password){
        // 获取缓存的OAuth数据
        $data = $this->openSignUpPrepare(true);
        if(!$data) return false;

        // 判断对应用户名是否已使用, 已使用则要求提供正确的密码作为登录凭据, 并将该开放平台账号绑定到该账户
        // 账户可为普通用户名或邮箱
        $is_new = true;
        if(is_email($user_login)){
            $user = get_user_by('email', $user_login);
        }else{
            $user = get_user_by('login', $user_login);
        }
        if($user) {
            if(!wp_check_password( $password, $user->data->user_pass, $user->ID)) {
                return new WP_Error('unmatch_login_pass', __('The username is exist, please provide correct password for it if owned by you or choose another username', 'tt'));
            }
            // 更新用户数据
            $insert_user_id = wp_update_user( array(
                'ID'  => $user->ID,
                'nickname'  => $data['name'],
                'display_name'  => $data['name']
            ) ) ;
            $is_new = false;
        }else{
            // 开放平台连接并需要新建一个本地用户绑定
            $insert_user_id = wp_insert_user( array(
                'user_login'  => $user_login,
                'user_email' => $user_login,
                'nickname'  => $data['name'],
                'display_name'  => $data['name'],
                'user_pass' => $password
            ) ) ;
        }


        if( is_wp_error($insert_user_id) ) {
            $this->_error = (object)array(
                'title' => __('Create New User Failed', 'tt'),
                'message' => $insert_user_id->get_error_message(),
                'code' => $insert_user_id->get_error_code()
            );

            //return false;
            return $insert_user_id; // for rest request
        }else{

            // 将Open数据存储到对应用户meta
            $this->saveOpenInfoAndProfile($insert_user_id, $data);

            // 新用户直接使用开放平台头像
            //update_user_meta($insert_user_id, 'tt_avatar_type', static::$_type);  // TODO: 是否判断已有其他开放平台的头像

            // 新用户角色
            if($is_new) wp_update_user( array ('ID' => $insert_user_id, 'role' => tt_get_option('tt_open_role', 'contributor') ) );

            // 发送消息
            if($is_new) {
                $msg_title = __('请完善账号信息','tt');
                $msg_content = sprintf(__('欢迎来到%1$s, 请<a href="%2$s">完善资料</a>','tt'), get_bloginfo('name'), tt_url_for('my_settings'));
                tt_create_message( $insert_user_id, 0, 'System', 'notification', $msg_title, $msg_content, MsgReadStatus::UNREAD, 'publish');
            }


            // 更新最新登录时间
            // update_user_meta( $insert_user_id, 'tt_latest_login', current_time( 'mysql' ) ); // Note: 由wp_login钩子去做

            // 设置当前用户
            wp_set_current_user( $insert_user_id, $user_login );
            wp_set_auth_cookie( $insert_user_id );
            do_action( 'wp_login', $user_login, get_user_by('ID', $insert_user_id) ); // TODO: 通过email发送欢迎邮件

            //return wp_safe_redirect(self::getRedirect());
            return true; // TODO
        }

    }


    // 针对 /oauth/[type]/last的公开handler，配合JS请求
    /**
     * 处理连接开放平台后新建本地用户请求(由客户端JS发送用户自定义的用户名、密码等，然后由该函数尝试新建本地关联用户，所以需要将openSignUp封装为public)
     *
     * @since   2.0.0
     * @param   string  $user_login  用户名
     * @param   string  $password   用户密码
     * @param   bool    $is_get_data    是否仅获取缓存的OAuth信息
     * @param   string  $data_cache_key 缓存OAuth信息的Key
     * @return  bool|string|WP_Error
     */
    public function openHandleLast($user_login, $password, $is_get_data = true, $data_cache_key = ''){
        $this->_data_transient_key = $data_cache_key;
        if($is_get_data){
            // 仅获取缓存的OAuth信息，用于填充/oauth/[type]/last页面表单默认值, 此事用户名是随机生成的, 不推荐
            return $this->openSignUpPrepare();
        }
        return $this->openSignUp($user_login, $password);
    }


    // 针对 /oauth/[type]的公开handler(发生错误只会给出error对象，处理由页面模板选择，如wp_die的方式)
    /**
     * 链接/解绑开放平台/刷新Token(开放平台handler)
     * // 发生的错误处理交由页面决定，这里不使用wp_die处理
     *
     * @since   2.0.0
     *
     * @return  mixed
     */
    public function openHandle(){
        // Route /oauth/[type]?act=connect&redirect=xxx
        $oauth = strtolower(get_query_var('oauth'));
        if(!$oauth || !in_array($oauth, (array)json_decode(ALLOWED_OAUTH_TYPES))){
            $this->_error = (object)array(
                'title' => __('Unallowed Open Type', 'tt'),
                'message' => __('The open connect type is not allowed', 'tt'),
                'code' => 'unallowed_open_type'
            );
            return false;
        }

        if( !isset($_GET['act']) || !in_array(strtolower(trim($_GET['act'])), (array)json_decode(ALLOWED_OAUTH_ACTIONS)) ){
            $act = 'connect';
        }else{
            $act = strtolower(trim($_GET['act']));
        }

        switch ($act){
            case 'connect':
                return $this->openConnect();
                break;
            case 'disconnect':
                return $this->openDisconnect();
                break;
            case 'refresh':
                return $this->openRefresh();
                break;
        }

        return true;
    }

    protected function openConnect(){
        // Case 1. 初始请求
        if(!isset($_GET['code']) && !isset($_GET['state'])){
            // 首先鉴权
            if(!($ret = $this->authenticate())){
                // $error = $this->getError();
                // wp_die($error->message, $error->title, array('back_link'=>true));
                return false;
            }

            // 鉴权成功跳转至开放平台的登录授权页面，完成后会回调，即进入Case 2环节
        }

        // Case 2.1 开放平台的回调请求(用户不同意授权，不会返回code，只会返回state)
        if(!isset($_GET['code']) && isset($_GET['state'])){
            $this->_error = (object)array(
                'title' => __('User Authorization Canceled', 'tt'),
                'message' => __('You should agree to authorize our application connecting your open account', 'tt'),
                'code' => 'user_cancel_authorization'
            );

            return false;
        }

        // Case 2.2 开放平台的回调请求(用户同意授权)
        $code = trim($_GET['code']);
        $state = isset($_GET['state']) ? trim($_GET['state']) : '';

        if(!($ret = $this->authorize($code, $state))){
            // $error = $this->getError();
            // wp_die($error->message, $error->title, array('back_link'=>true));
            return false;
        }
        // authorize成功的条件下会完成数据请求和收集，并尝试登入
        // 如果是老用户登入，则重定向至redirect标记的地址
        // 如果是新建用户则重定向至oauth请求的最后一步页面，填写必要的用户名、密码等，这后面需要字段合法性验证，需要JS辅助完成，代码见对应的template文件
        return true;
    }

    protected function openDisconnect(){
        // Route /oauth/[type]?act=disconnect

        self::setRedirectCookie();

        if(!($this->_user)){
            $this->_error = (object)array(
                'title' => __('User Not Logged In', 'tt'),
                'message' => __('You must be logged in and then log out', 'tt'),
                'code' => 'unidentified_user'
            );

            return false;
        }

        update_user_meta($this->_user->ID, static::$_openid_meta_key, '');
        update_user_meta($this->_user->ID, static::$_access_token_meta_key, '');
        update_user_meta($this->_user->ID, static::$_refresh_token_meta_key, '');
        update_user_meta($this->_user->ID, static::$_token_expiration_meta_key, '');

        // 更换默认头像类型
        update_user_meta($this->_user->ID, 'tt_avatar_type', '');

        wp_safe_redirect(self::getRedirect());
        exit;
    }

    protected function openRefresh(){
        return $this->refreshToken();
    }

}

/**
 * Class OpenQQ QQ开放平台登录
 */
class OpenQQ extends Open{

    protected static $_type = 'qq';

    protected static $_status_option_name = 'tt_enable_qq_login';

    protected static $_openkey_option_name = 'tt_qq_openid';

    protected static $_opensecret_option_name = 'tt_qq_openkey';

    protected static $_openid_meta_key = 'tt_qq_openid';

    protected static $_access_token_meta_key = 'tt_qq_access_token';

    protected static $_refresh_token_meta_key = 'tt_qq_refresh_token';

    protected static $_token_expiration_meta_key = 'tt_qq_token_expiration';

    protected static $_state_cookie_name = 'tt_qq_state';

    protected static $_callback_url_key = 'oauth_qq';

    protected static $_oauth_last_url_key = 'oauth_qq_last';

    /**
     * 鉴权，获取code
     *
     * @since   2.0.0
     *
     * @return bool
     */
    protected function authenticate(){
        if( !($this->checkOpen('duplication_check')) || !($this->checkOpen('enable_check')) ) return false;

        self::setRedirectCookie();

        $params = array(
            'response_type' => 'code',
            'client_id' => $this->_openkey,
            'state' =>  self::setStateCookie(),
            'scope'=>'get_user_info,get_info,add_t,del_t,add_pic_t,get_repost_list,get_other_info,get_fanslist,get_idollist,add_idol,del_idol',
            'redirect_uri' => self::getCallbackUrl()
        );

        $auth_url = 'https://graph.qq.com/oauth2.0/authorize?' . http_build_query($params);
        wp_redirect($auth_url);
        exit;
    }

    /**
     * 认证，获取access token，抓取用户信息并尝试接入登录
     *
     * @since   2.0.0
     *
     * @param string $code  鉴权阶段成功后返回的code，用于认证步骤
     * @param string $state 状态码，加强CSRF防护
     * @return bool
     */
    protected function authorize($code, $state){
        if( !($this->checkState()) ) return false;

        $params = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $this->_openkey,
            'client_secret' => $this->_opensecret,
            'redirect_uri' => self::getCallbackUrl()
        );

        $url = 'https://graph.qq.com/oauth2.0/token?' . http_build_query($params);

        $response = wp_remote_get($url);

        $body = wp_remote_retrieve_body($response);
        // e.g callback( {"error":100009,"error_description":"client secret is illegal"} ); json
        // e.g access_token=xxx&expires_in=7776000&refresh_token=xxx    text/plain

        if(preg_match('/callback\((.*)\)/', $body, $matches)){
            $msg = json_decode(trim($matches[1]));
            if(isset($msg->error)){
                $this->_error = (object)array(
                    'title' => __('Grant QQ Access Token Failed', 'tt'),
                    'message' => $msg->error_description,
                    'code'  => 'grant_access_token_error'
                );
            }
            return false;
        }

        $params = array();
        parse_str($body, $params);

        // 获取到的access_token等参数
        $access_token = $params['access_token']; // 有效期2个月
        $expire_in = $params['expires_in'];
        $refresh_token = $params['refresh_token'];

        // 获取用户openid以及昵称等
        $info = $this->getOpenUser($access_token);
        if(!$info) return false;

        // 将QQ用户信息接入WP，尝试登入
        $expiration = time() + $expire_in - 60*10;
        return $this->openSignIn($info->openid, $access_token, $refresh_token, $expiration, $info);

    }

    /**
     * 抓取开放平台用户信息
     *
     * @since   2.0.0
     *
     * @param   string  $access_token   Access Token
     * @param   string  $openid     用户在开放平台的openid
     * @return  array|bool|mixed|object
     */
    protected function getOpenUser($access_token, $openid = null){

        $graph_url = 'https://graph.qq.com/oauth2.0/me?access_token=' . $access_token;

        $response = wp_remote_get($graph_url);

        $body = wp_remote_retrieve_body($response);

        // @see http://wiki.open.qq.com/wiki/website/%E8%8E%B7%E5%8F%96%E7%94%A8%E6%88%B7OpenID_OAuth2.0
        // PC网站接入时，获取到用户OpenID，返回包如下：
        // callback( {"client_id":"YOUR_APPID","openid":"YOUR_OPENID"} );
        // WAP网站接入时，返回如下字符串：
        // client_id=100222222&openid=1704************************878C
        $msg = null;
        if(preg_match('/callback\((.*)\)/', $body, $matches)){
            $msg = json_decode(trim($matches[1]));
            if(isset($msg->error)){
                $this->_error = (object)array(
                    'title' => __('Grant QQ OpenID Failed', 'tt'),
                    'message' => $msg->error_description,
                    'code'  => 'grant_openid_error'
                );
                return false;
            }
        }

        if(!$msg){
            parse_str($body, $params);
            $msg = (object)$params;
        }

        $openid = $msg->openid;

        // 利用openid获取对应用户信息
        $info_url = 'https://graph.qq.com/user/get_user_info?access_token=' . $access_token . '&oauth_consumer_key=' . $this->_openkey . '&openid=' . $openid;

        $info = json_decode(wp_remote_retrieve_body(wp_remote_get($info_url)));
        // {
        //   "ret":0,
        //   "msg":"",
        //   "nickname":"YOUR_NICK_NAME",
        //   ...
        // }

        if ($info->ret){
            $this->_error = (object)array(
                'title' => __('Grant QQ User Info Failed', 'tt'),
                'message' => $info->msg,
                'code'  => 'grant_user_info_error'
            );
            return false;
        }

        $info->openid = $openid;

        return $info;
    }

    /**
     * 刷新Access Token
     *
     * @since   2.0.0
     *
     * @return bool
     */
    protected function refreshToken(){
        // Route /oauth/[type]?act=refresh

        self::setRedirectCookie();

        if( !($this->_user) ){
            $this->_error = (object)array(
                'title' => __('User Not Logged In', 'tt'),
                'message' => __('You must log in to refresh your token', 'tt'),
                'code' => 'unidentified_user'
            );

            return false;
        }

        $refresh_token = get_user_meta($this->_user->ID, self::$_refresh_token_meta_key, true);

        if(!$refresh_token){
            $this->_error = (object)array(
                'title' => __('Refresh Token Miss', 'tt'),
                'message' => __('A refresh token is required to get new access token', 'tt'),
                'code' => 'refresh_token_miss'
            );

            return false;
        }

        $params = array(
            'grant_type' => 'refresh_token',
            'client_id' => $this->_openkey,
            'client_secret' => $this->_opensecret,
            'refresh_token' => $refresh_token
        );

        $url = 'https://graph.qq.com/oauth2.0/token?' . http_build_query($params);

        $response = wp_remote_get($url);

        $body = wp_remote_retrieve_body($response);
        // e.g access_token=xxx&expires_in=7776000&refresh_token=xxx    text/plain

        $params = array();
        parse_str($body, $params);

        $access_token = $params['access_token'];
        $expire_in = $params['expires_in'];
        $refresh_token = $params['refresh_token'];
        $expiration = time() + $expire_in - 60*10;

        update_user_meta($this->_user->ID, static::$_access_token_meta_key, $access_token);
        update_user_meta($this->_user->ID, static::$_refresh_token_meta_key, $refresh_token);
        update_user_meta($this->_user->ID, static::$_token_expiration_meta_key, $expiration);

        wp_safe_redirect(self::getRedirect());
        exit;

    }

}


/**
 * Class OpenWeiXin 微信开放平台登录
 */
class OpenWeiXin extends Open{

    protected static $_type = 'weixin';

    protected static $_status_option_name = 'tt_enable_weixin_login';

    protected static $_openkey_option_name = 'tt_weixin_openid';

    protected static $_opensecret_option_name = 'tt_weixin_openkey';

    protected static $_openid_meta_key = 'tt_weixin_openid';

    protected static $_access_token_meta_key = 'tt_weixin_access_token';

    protected static $_refresh_token_meta_key = 'tt_weixin_refresh_token';

    protected static $_token_expiration_meta_key = 'tt_weixin_token_expiration';

    protected static $_state_cookie_name = 'tt_weixin_state';

    protected static $_callback_url_key = 'oauth_weixin';

    protected static $_oauth_last_url_key = 'oauth_weixin_last';


    /**
     * 鉴权，获取code
     *
     * @since   2.0.0
     *
     * @return bool
     */
    protected function authenticate(){
        // DOC https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1419316505&token=&lang=zh_CN
        if( !($this->checkOpen('duplication_check')) || !($this->checkOpen('enable_check')) ) return false;

        self::setRedirectCookie();

        $params = array(
            'response_type' => 'code',
            'appid' => $this->_openkey,
            'state' =>  self::setStateCookie(),
            'scope'=>'snsapi_login',
            'redirect_uri' => self::getCallbackUrl()
        );

        $auth_url = 'https://open.weixin.qq.com/connect/qrconnect?' . http_build_query($params);
        wp_redirect($auth_url);
        exit;
    }

    /**
     * 认证，获取access token，抓取用户信息并尝试接入登录
     *
     * @since   2.0.0
     *
     * @param string $code  鉴权阶段成功后返回的code，用于认证步骤
     * @param string $state 状态码，加强CSRF防护
     * @return bool
     */
    protected function authorize($code, $state){
        if( !($this->checkState()) ) return false;

        $params = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'appid' => $this->_openkey,
            'secret' => $this->_opensecret
        );

        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?' . http_build_query($params);

        $response = wp_remote_get($url);

        $body = wp_remote_retrieve_body($response);
        // {
            // "access_token":"ACCESS_TOKEN",
            // "expires_in":7200,
            // "refresh_token":"REFRESH_TOKEN",
            // "openid":"OPENID",
            // "scope":"SCOPE",
            // "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
        // }

        // {"errcode":40029,"errmsg":"invalid code"}

        if(preg_match('/\{(.*)\}/', $body, $matches)){
            $msg = json_decode(trim($matches[0]));
            if(isset($msg->errcode)){
                $this->_error = (object)array(
                    'title' => 'Grant WeiXin Access Token Failed',
                    'message' => $msg->errmsg,
                    'code'  => 'grant_access_token_error_' . $msg->errcode
                );
                return false;
            }
        }else{
            $this->_error = (object)array(
                'title' => 'Grant WeiXin Access Token Failed',
                'message' => __('The open server returned with a incorrect response', 'tt'),
                'code'  => 'grant_access_token_error'
            );
            return false;
        }

        // 获取的openid等
        $openid = $msg->openid;
        $access_token = $msg->access_token; // 有效期2小时
        $refresh_token = $msg->refresh_token; // 有效期30天
        $expire_in = $msg->expires_in;
        // $unionid = $msg->unionid; // 开发者最好保存用户unionID信息，以便以后在不同应用中进行用户信息互通

        // 获取用户信息
        $info = $this->getOpenUser($access_token, $openid);
        if(!$info) return false;

        // 将微信用户信息接入WP，尝试登入
        $expiration = time() + $expire_in - 60*10;
        return $this->openSignIn($info->openid, $access_token, $refresh_token, $expiration, $info);

    }

    /**
     * 抓取开放平台用户信息
     *
     * @since   2.0.0
     *
     * @param   string  $access_token   Access Token
     * @param   string  $openid     用户在开放平台的openid
     * @return  array|bool|mixed|object
     */
    protected function getOpenUser($access_token, $openid){
        // DOC https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1419316518&lang=zh_CN

        $params = array(
            'access_token' => $access_token,
            'openid' => $openid
        );

        $graph_url = 'https://api.weixin.qq.com/sns/userinfo?' . http_build_query($params);

        $response = wp_remote_get($graph_url);

        $body = wp_remote_retrieve_body($response);

        // {
        //     "openid":"OPENID",
        //     "nickname":"NICKNAME",
        //     "sex":1,
        //     "province":"PROVINCE",
        //     "city":"CITY",
        //     "country":"COUNTRY",
        //     "headimgurl": "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0",
        //     "privilege":[
        //         "PRIVILEGE1",
        //         "PRIVILEGE2"
        //     ],
        //    "unionid": " o6_bmasdasdsad6_2sgVt7hMZOPfL"
        //
        //}

        // {
            // "errcode":40003,"errmsg":"invalid openid"
        // }
        $msg = null;
        if(preg_match('/\{(.*)\}/', $body, $matches)){
            $msg = json_decode(trim($matches[0]));
            if(isset($msg->errcode)){
                $this->_error = (object)array(
                    'title' => __('Get WeiXin Userinfo Failed', 'tt'),
                    'message' => $msg->errmsg,
                    'code'  => 'grant_userinfo_error_' . $msg->errcode
                );
                return false;
            }
        }else{
            $this->_error = (object)array(
                'title' => __('Get WeiXin Userinfo Failed', 'tt'),
                'message' => __('The open server returned with a incorrect response', 'tt'),
                'code'  => 'grant_userinfo_error'
            );
            return false;
        }

        // 正确的情况$msg就是用户信息
        $info = $msg;
        $info->name = $msg->nickname;  // 为了多个开放平台统一名称的key

        return $info;
    }

    /**
     * 刷新Access Token
     *
     * @since   2.0.0
     *
     * @return bool
     */
    protected function refreshToken(){
        // Route /oauth/[type]?act=refresh

        self::setRedirectCookie();

        if( !($this->_user) ){
            $this->_error = (object)array(
                'title' => __('User Not Logged In', 'tt'),
                'message' => __('You must log in to refresh your token', 'tt'),
                'code' => 'unidentified_user'
            );

            return false;
        }

        $refresh_token = get_user_meta($this->_user->ID, self::$_refresh_token_meta_key, true);

        if(!$refresh_token){
            $this->_error = (object)array(
                'title' => __('Refresh Token Miss', 'tt'),
                'message' => __('A refresh token is required to get new access token', 'tt'),
                'code' => 'refresh_token_miss'
            );

            return false;
        }

        $params = array(
            'grant_type' => 'refresh_token',
            'appid' => $this->_openkey,
            'refresh_token' => $refresh_token
        );

        $url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?' . http_build_query($params);
        $response = wp_remote_get($url);

        $body = wp_remote_retrieve_body($response);

        // {
        //    "access_token":"ACCESS_TOKEN",
        //    "expires_in":7200,
        //    "refresh_token":"REFRESH_TOKEN",
        //    "openid":"OPENID",
        //    "scope":"SCOPE"
        // }

        // {
        //    "errcode": 40030,
        //    "errmsg": "invalid refresh_token, hints: [ req_id: 0HmTcA0011ssz2 ]"
        // }

        $msg = null;
        if(preg_match('/\{(.*)\}/', $body, $matches)){
            $msg = json_decode(trim($matches[0]));
            if(isset($msg->errcode)){
                $this->_error = (object)array(
                    'title' => __('Refresh WeiXin Token Failed', 'tt'),
                    'message' => $msg->errmsg,
                    'code'  => 'refresh_token_error_' . $msg->errcode
                );
                return false;
            }
        }else{
            $this->_error = (object)array(
                'title' => __('Refresh WeiXin Token Failed', 'tt'),
                'message' => __('The open server returned with a incorrect response', 'tt'),
                'code'  => 'refresh_token_error'
            );
            return false;
        }

        $access_token = $params['access_token'];
        $expire_in = $params['expires_in'];
        $refresh_token = $params['refresh_token'];
        $expiration = time() + $expire_in - 60*10;

        update_user_meta($this->_user->ID, static::$_access_token_meta_key, $access_token);
        update_user_meta($this->_user->ID, static::$_refresh_token_meta_key, $refresh_token);
        update_user_meta($this->_user->ID, static::$_token_expiration_meta_key, $expiration);

        wp_safe_redirect(self::getRedirect());
        exit;

    }

}


/**
 * Class OpenWeibo 微博开放平台登录
 */
class OpenWeibo extends Open{

    protected static $_type = 'weibo';

    protected static $_status_option_name = 'tt_enable_weibo_login';

    protected static $_openkey_option_name = 'tt_weibo_openkey';

    protected static $_opensecret_option_name = 'tt_weibo_opensecret';

    protected static $_openid_meta_key = 'tt_weibo_openid';

    protected static $_access_token_meta_key = 'tt_weibo_access_token';

    protected static $_refresh_token_meta_key = 'tt_weibo_refresh_token';

    protected static $_token_expiration_meta_key = 'tt_weibo_token_expiration';

    protected static $_state_cookie_name = 'tt_weibo_state';

    protected static $_callback_url_key = 'oauth_weibo';

    protected static $_oauth_last_url_key = 'oauth_weibo_last';


    /**
     * 鉴权，获取code
     *
     * @since   2.0.0
     *
     * @return bool
     */
    protected function authenticate(){
        // DOC http://open.weibo.com/wiki/%E6%8E%88%E6%9D%83%E6%9C%BA%E5%88%B6%E8%AF%B4%E6%98%8E
        if( !($this->checkOpen('duplication_check')) || !($this->checkOpen('enable_check')) ) return false;

        self::setRedirectCookie();

        $state = self::setStateCookie();
        $params = array(
            'response_type' => 'code',
            'client_id' => $this->_openkey,
            'redirect_uri' => self::getCallbackUrl(array('state' => $state))  // 由于微博不需要state参数，也不会回调时返回state，为了统一，在生成回调地址上附上state参数
        );

        $auth_url = 'https://api.weibo.com/oauth2/authorize?' . http_build_query($params);
        wp_redirect($auth_url);
        exit;
    }

    /**
     * 认证，获取access token，抓取用户信息并尝试接入登录
     *
     * @since   2.0.0
     *
     * @param string $code  鉴权阶段成功后返回的code，用于认证步骤
     * @param string $state 状态码，加强CSRF防护
     * @return bool
     */
    protected function authorize($code, $state){
        // if( !($this->checkState()) ) return false; // 没有必要

        $params = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $this->_openkey,
            'client_secret' => $this->_opensecret,
            'redirect_uri' => self::getCallbackUrl()
        );

        $url = 'https://api.weibo.com/oauth2/access_token?' . http_build_query($params);

        $response = wp_remote_post($url, null);

        $body = wp_remote_retrieve_body($response);
        // {
        //    "access_token": "SlAV32hkKG",
        //    "remind_in": 3600,
        //    "expires_in": 3600
        // }

        // {
        //    "error": "unsupport_protocol",
        //    "error_code": 21320,
        //    "request": "/oauth2/access_token",
        //    "error_uri": "/oauth2/access_token",
        //    "error_description": "oauth2 must use https protocol." // optional
        // }

        if(preg_match('/\{(.*)\}/', $body, $matches)){
            $msg = json_decode(trim($matches[0]));
            if(isset($msg->error)){
                $this->_error = (object)array(
                    'title' => __('Grant Weibo Access Token Failed', 'tt'),
                    'message' => isset($msg->error_description) ? $msg->error_description : $msg->error,
                    'code'  => 'grant_access_token_error'
                );
                return false;
            }
        }else{
            $this->_error = (object)array(
                'title' => 'Grant Weibo Access Token Failed',
                'message' => __('The open server returned with a incorrect response', 'tt'),
                'code'  => 'grant_access_token_error'
            );
            return false;
        }

        // 获取到的access_token等参数
        $access_token = $msg->access_token; // 通常过期时间为7天
        $expire_in = $msg->expires_in;
        $refresh_token = isset($msg->refresh_token) ? $msg->refresh_token : ''; // 仅对使用了微博移动SDK的移动应用有效

        // 获取用户openid以及昵称等
        $info = $this->getOpenUser($access_token);
        if(!$info) return false;

        // 将微博用户信息接入WP，尝试登入
        $expiration = time() + $expire_in - 60*10;
        return $this->openSignIn($info->id, $access_token, $refresh_token, $expiration, $info); // 微博没有openid， 以id替代

    }

    /**
     * 抓取开放平台用户信息
     *
     * @since   2.0.0
     *
     * @param   string  $access_token   Access Token
     * @param   string  $openid     用户在开放平台的openid
     * @return  array|bool|mixed|object
     */
    protected function getOpenUser($access_token, $openid = null){

        // Step 1. http://open.weibo.com/wiki/Oauth2/get_token_info
        $graph_url = 'https://api.weibo.com/oauth2/get_token_info?' . http_build_query(array('access_token' => $access_token));  // POST请求
        $response = wp_remote_post($graph_url, array('access_token' => $access_token));
        $body = wp_remote_retrieve_body($response);

        // {
        //    "uid": 1073880650,
        //    "appkey": 1352222456,
        //    "scope": null,
        //    "create_at": 1352267591,
        //    "expire_in": 157679471
        // }

        // {
        //    "error": "HTTP METHOD is not suported for this request!",
        //    "error_code": 10021,
        //    "request": "/oauth2/get_token_info"
        // }
        if(preg_match('/\{(.*)\}/', $body, $matches)){
            $msg = json_decode(trim($matches[0]));
            if(isset($msg->error)){
                $this->_error = (object)array(
                    'title' => __('Grant Weibo Token Info Failed', 'tt'),
                    'message' => isset($msg->error_description) ? $msg->error_description : $msg->error,
                    'code'  => 'grant_token_info_error_' . $msg->error_code
                );
                return false;
            }
        }else{
            $this->_error = (object)array(
                'title' => 'Grant Weibo Token Info Failed',
                'message' => __('The open server returned with a incorrect response', 'tt'),
                'code'  => 'grant_token_info_error'
            );
            return false;
        }

        $uid = $msg->uid;

        // Step 2. http://open.weibo.com/wiki/2/users/show
        $params = array(
            'access_token' => $access_token,
            'uid' => $uid
        );
        $graph_url = 'https://api.weibo.com/2/users/show.json?' . http_build_query($params);  // GET请求
        $response = wp_remote_get($graph_url);
        $body = wp_remote_retrieve_body($response);

        // {
        //    "id": 1404376560,
        //    "screen_name": "zaku",
        //    "name": "zaku",
        //    "province": "11",
        //    "city": "5",
        //    "location": "北京 朝阳区",
        //    "description": "人生五十年，乃如梦如幻；有生斯有死，壮士复何憾。",
        //    "url": "http://blog.sina.com.cn/zaku",
        //    "profile_image_url": "http://tp1.sinaimg.cn/1404376560/50/0/1",
        //    "domain": "zaku",
        //    "gender": "m",
        //    "followers_count": 1204,
        //    "friends_count": 447,
        //    "statuses_count": 2908,
        //    "favourites_count": 0,
        //    "created_at": "Fri Aug 28 00:00:00 +0800 2009",
        //    "following": false,
        //    "allow_all_act_msg": false,
        //    "geo_enabled": true,
        //    "verified": false,
        //    "status": {
        //        "created_at": "Tue May 24 18:04:53 +0800 2011",
        //        "id": 11142488790,
        //        "text": "我的相机到了。",
        //        "source": "<a href="http://weibo.com" rel="nofollow">新浪微博</a>",
        //        "favorited": false,
        //        "truncated": false,
        //        "in_reply_to_status_id": "",
        //        "in_reply_to_user_id": "",
        //        "in_reply_to_screen_name": "",
        //        "geo": null,
        //        "mid": "5610221544300749636",
        //        "annotations": [],
        //        "reposts_count": 5,
        //        "comments_count": 8
        //    },
        //    "allow_all_comment": true,
        //    "avatar_large": "http://tp1.sinaimg.cn/1404376560/180/0/1",
        //    "verified_reason": "",
        //    "follow_me": false,
        //    "online_status": 0,
        //    "bi_followers_count": 215
        // }

        // {
        //    "error": "source paramter(appkey) is missing",
        //    "error_code": 10006,
        //    "request": "/2/users/show.json"
        // }
        if(preg_match('/\{(.*)\}/', $body, $matches)){
            $msg = json_decode(trim($matches[0]));
            if(isset($msg->error)){
                $this->_error = (object)array(
                    'title' => __('Grant Weibo User Show Info Failed', 'tt'),
                    'message' => isset($msg->error_description) ? $msg->error_description : $msg->error,
                    'code'  => 'grant_user_show_info_error_' . $msg->error_code
                );
                return false;
            }
        }else{
            $this->_error = (object)array(
                'title' => 'Grant Weibo User Show Info Failed',
                'message' => __('The open server returned with a incorrect response', 'tt'),
                'code'  => 'grant_user_show_info_error'
            );
            return false;
        }

        $info = $msg;

        return $info;
    }

    /**
     * 刷新Access Token(当前仅对使用官方SDK的移动应用有效，其他的不提供refresh_token用于刷新)
     *
     * @since   2.0.0
     *
     * @return bool
     */
    protected function refreshToken(){
        // Route /oauth/[type]?act=refresh

        self::setRedirectCookie();

        if( !($this->_user) ){
            $this->_error = (object)array(
                'title' => __('User Not Logged In', 'tt'),
                'message' => __('You must log in to refresh your token', 'tt'),
                'code' => 'unidentified_user'
            );

            return false;
        }

        $refresh_token = get_user_meta($this->_user->ID, self::$_refresh_token_meta_key, true);

        if(!$refresh_token){
            $this->_error = (object)array(
                'title' => __('Refresh Token Miss', 'tt'),
                'message' => __('A refresh token is required to get new access token', 'tt'),
                'code' => 'refresh_token_miss'
            );

            return false;
        }

        $params = array(
            'grant_type' => 'refresh_token',
            'client_id' => $this->_openkey,
            'client_secret' => $this->_opensecret,
            'refresh_token' => $refresh_token,
            'redirect_uri' => self::getCallbackUrl()
        );

        $url = 'https://api.weibo.com/oauth2/access_token?' . http_build_query($params);

        $response = wp_remote_get($url);

        $body = wp_remote_retrieve_body($response);
        // {
        //    "access_token": "SlAV32hkKG",
        //    "expires_in": 3600
        // }
        if(preg_match('/\{(.*)\}/', $body, $matches)){
            $msg = json_decode(trim($matches[0]));
            if(isset($msg->error)){
                $this->_error = (object)array(
                    'title' => __('Refresh Weibo Access Token Failed', 'tt'),
                    'message' => isset($msg->error_description) ? $msg->error_description : $msg->error,
                    'code'  => 'refresh_access_token_error_' . $msg->error_code
                );
                return false;
            }
        }else{
            $this->_error = (object)array(
                'title' => 'Refresh Weibo Access Token Failed',
                'message' => __('The open server returned with a incorrect response', 'tt'),
                'code'  => 'refresh_access_token_error'
            );
            return false;
        }

        $access_token = $msg->access_token;
        $expire_in = $msg->expire_in;
        $expiration = time() + $expire_in - 60*10;

        update_user_meta($this->_user->ID, static::$_access_token_meta_key, $access_token);
        update_user_meta($this->_user->ID, static::$_token_expiration_meta_key, $expiration);

        wp_safe_redirect(self::getRedirect());
        exit;

        // TODO: 是否要重新获取一些用户信息，如描述，地理位置并更新WP内的数据
    }

}
