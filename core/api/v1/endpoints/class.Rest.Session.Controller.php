<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/16 20:32
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

//GET /session # 获取会话信息
//POST /session # 创建新的会话（登入）
//PUT /session # 更新会话信息
//DELETE /session # 销毁当前会话（登出）
//

//注册对应的资源是user，api如下：
//
//GET /user/:id # 获取id用户的信息
//POST /user # 创建新的用户（注册）
//PUT /user/:id # 更新id用户的信息
//DELETE /user/:id # 删除id用户（注销）

/**
 * Class WP_REST_Session_Controller
 */
class WP_REST_Session_Controller extends WP_REST_Controller
{

    public function __construct()
    {
        $this->namespace = 'v1';
        $this->rest_base = tt_get_option('tt_session_api', 'session');
    }

    /**
     * 注册路由
     */
    public function register_routes(){
        register_rest_route($this->namespace, '/' . $this->rest_base, array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_item'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
            ),
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'delete_item'),
                'permission_callback' => array($this, 'delete_item_permissions_check'),
                'args' => array(
                    'force' => array(
                        'default' => false,
                        'description' => __('Required to be true, as resource does not support trashing.'),
                    ),
                    'reassign' => array(),
                ),
            ),
            'schema' => array($this, 'get_public_item_schema'),
        ));
    }


    /**
     * 判断请求是否有权限创建会话
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function create_item_permissions_check( $request ) {
        return true;
    }

    /**
     * 创建会话(登录)
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error | WP_REST_Response
     */
    public function create_item( $request ) {

        $user_login = sanitize_text_field($request->get_param('user_login'));

        $password = sanitize_text_field($request->get_param('password'));
        $oauth = $request->get_param('oauth');
        if($oauth !== null && in_array($oauth, (array)json_decode(ALLOWED_OAUTH_TYPES))){
            if(!is_email($user_login)) {
                return new WP_Error('rest_invalid_email', __('Invalid email format', 'tt'));
            }
            switch($oauth) {
                case 'qq':
                    $open = new OpenQQ(wp_get_current_user());
                    break;
                case 'weibo':
                    $open = new OpenWeibo(wp_get_current_user());
                    break;
                case 'weixin':
                    $open = new OpenWeiXin(wp_get_current_user());
                    break;
            }
            $open_handle_result = $open->openHandleLast($user_login, $password, false, $request->get_param('key'));
            if($open_handle_result instanceof WP_Error){
                return $open_handle_result;
            }elseif(!$open_handle_result){
                return tt_api_fail(__('Open connect failed to complete your profile', 'tt'));
            }else{
                return tt_api_success(__('Open connected', 'tt'));
            }
        }


        $nonce = trim($request->get_param('nonce'));

        // 验证登录nonce
        if(!wp_verify_nonce($nonce, 'page-signin')) {
            return rest_ensure_response(array(
                'success' => 0,
                'message' => __('Verify the login nonce failed', 'tt')
            ));
        }

        // 账户可为普通用户名或邮箱
        if(is_email($user_login)){
            $user = get_user_by('email', $user_login);
        }else{
            $user = get_user_by('login', $user_login);
        }

        if ($user && wp_check_password( $password, $user->data->user_pass, $user->ID)) {
            $creds = array('user_login' => $user->data->user_login, 'user_password' => $password, 'remember' => true);
            $user = wp_signon( $creds, is_ssl() );
            if($user instanceof WP_Error) {
                return $user;
            }
            return rest_ensure_response(array(
                'success' => 1,
                'message' => __('Welcome, you have signed in successfully', 'tt')
            ));
        }

        //hook
        do_action('wp_login_failed', $user_login);

        return new WP_Error( 'rest_session_invalid_login', __( 'There is no user matches the info you offered', 'tt' ), array( 'status' => tt_rest_authorization_required_code() ) );
    }


    /**
     * 判断当前请求是否有权限删除Session
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function delete_item_permissions_check( $request ) {

        if ( !is_user_logged_in() ) {
            return new WP_Error( 'rest_session_cannot_delete', __( 'Sorry, you are not allowed to delete this resource.', 'tt' ), array( 'status' => tt_rest_authorization_required_code() ) );
        }

        return true;
    }

    /**
     * 删除Session(登出)
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function delete_item( $request ) {
        $force = isset( $request['force'] ) ? (bool) $request['force'] : false;

        // We don't support trashing for this type, error out
        if ( ! $force ) {
            return new WP_Error( 'rest_trash_not_supported', __( 'Users do not support trashing.', 'tt' ), array( 'status' => 501 ) );
        }

        $user = wp_get_current_user();
        if ( ! $user ) {
            return new WP_Error( 'rest_user_invalid_id', __( 'Invalid resource id.', 'tt' ), array( 'status' => 400 ) );
        }

        wp_destroy_current_session();
        wp_clear_auth_cookie();

        return rest_ensure_response(array(
            'success' => 1,
            'message' => __('You have signed out successfully', 'tt')
        ));
    }
}