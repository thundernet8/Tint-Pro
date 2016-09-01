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
 * @link https://www.webapproach.net/tint.html
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
     * WP用户实例
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
        setcookie(self::$_state_cookie_name, $state, time()+60*10);
        return $state;
    }

    /**
     * 获取回调地址
     *
     * @since   2.0.0
     *
     * @return string
     */
    protected static function getCallbackUrl(){
        return tt_url_for(static::$_callback_url_key);
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
     * 判断用户是否已经连接过该开放平台
     *
     * @since   2.0.0
     *
     * @return bool
     */
    protected function isOpenConnected(){
        // 判断该第三方账号是否已经授权过博客的登录
        $user = $this->_user;
        if(!$user) return false;
        return get_user_meta($user->ID, static::$_openid_meta_key, true) && get_user_meta($user->ID, static::$_access_token_meta_key, true);
    }

    /**
     * 开放平台登录前检查并输出错误
     *
     * @since   2.0.0
     *
     * @param string $schema 检查类别
     * @return bool
     */
    protected function checkOpen($schema = 'enable_check'){
        switch ($schema){
            case 'duplication_check':

                if($this->isOpenConnected()){
                    $this->_error = (object)array(
                        'title' => __('Can not bind QQ again', 'tt'),
                        'message' => __('You have connected with QQ before, can not do it again, please unbind it before if need', 'tt'),
                        'code'  => 'duplicated_connect'
                    );
                    return false;
                }
                return true;
                break;
            case 'enable_check':

                if($this->isOpenAvailable()){
                    $this->_error = (object)array(
                        'title' => __('QQ login disabled', 'tt'),
                        'message' => __('You have not enabled QQ login, or the required information e.g OpenID, OpenKey missed', 'tt'),
                        'code'  => 'disabled_connect'
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

    abstract public function authenticate();

    abstract public function authorize($code, $state);

    abstract public function getOpenUser($access_token);

    abstract public function openSignIn($openid, $access_token, $refresh_token, $expiration, $name);

    abstract public function refreshToken();

    abstract public function openSignOut();

    abstract public function handle();

}

/**
 * Class OpenQQ QQ开放平台登录
 */
class OpenQQ extends Open{

    private static $_status_option_name = 'tt_enable_qq_login';

    private static $_openkey_option_name = 'tt_qq_openid';

    private static $_opensecret_option_name = 'tt_qq_openkey';

    private static $_openid_meta_key = 'tt_qq_openid';

    private static $_access_token_meta_key = 'tt_qq_access_token';

    private static $_refresh_token_meta_key = 'tt_qq_refresh_token';

    private static $_token_expiration_meta_key = 'tt_qq_token_expiration';

    private static $_state_cookie_name = 'tt_qq_state';

    private static $_callback_url_key = 'oauth_qq';

    /**
     * 鉴权，获取code
     *
     * @since   2.0.0
     *
     * @return bool
     */
    public function authenticate(){
        if(!$this->checkOpen('duplication_check') || !$this->checkOpen('enable_check')) return false;

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
     * 认证，获取access token
     *
     * @since   2.0.0
     *
     * @param string $code  鉴权阶段成功后返回的code，用于认证步骤
     * @param string $state 状态码，加强CSRF防护
     * @return bool
     */
    public function authorize($code, $state){
        if(!$this->checkState()) return false;

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
                    'title' => 'Grant QQ Access Token Failed',
                    'message' => $msg->error_description,
                    'code'  => 'grant_access_token_error'
                );
            }
            return false;
        }

        $params = array();
        parse_str($response, $params);

        // 使用access token获取openid
        $access_token = $params['access_token'];
        $expire_in = $params['expires_in'];
        $refresh_token = $params['expires_in'];

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
                    'title' => 'Grant QQ OpenID Failed',
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
                'title' => 'Grant QQ User Info Failed',
                'message' => $msg->error_description,
                'code'  => 'grant_user_info_error'
            );
            return false;
        }

        // 将QQ用户信息接入WP，尝试登入
        $expiration = time() + $expire_in - 60*10;
        return $this->openSignIn($openid, $access_token, $refresh_token, $expiration, $info->nickname);

    }

    public function getOpenUser($access_token){

    }

    /**
     * 获取必要的信息完成后尝试登入并连接WP用户系统
     * @param $openid
     * @param $access_token
     * @param $refresh_token
     * @param $expiration
     * @param $name
     * @return bool
     */
    public function openSignIn($openid, $access_token, $refresh_token, $expiration, $name){

        return false;
    }

    public function refreshToken(){

    }

    public function openSignOut(){

    }

    public function handle(){

    }

}