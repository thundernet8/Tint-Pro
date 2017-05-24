<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/17 15:54
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class WP_REST_User_Profile_Controller
 */
class WP_REST_User_Profile_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'v1';
        $this->rest_base = 'users/profiles';
    }

    /**
     * 注册路由
     */
    public function register_routes(){

        register_rest_route( $this->namespace, '/' . $this->rest_base, array(
//            array(
//                'methods'         => WP_REST_Server::READABLE,
//                'callback'        => array( $this, 'get_item' ),
//                'permission_callback' => array( $this, 'get_item_permissions_check' ),
//                'args'            => array(
//                    'context'          => $this->get_context_param( array( 'default' => 'view' ) ),
//                ),
//            ),
            array(
                'methods'         => WP_REST_Server::EDITABLE,
                'callback'        => array( $this, 'update_item' ),
                'permission_callback' => array( $this, 'update_item_permissions_check' ),
                'args'            => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
            ),
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );
    }


    /**
     * 判断当前请求是否有权限更新用户资料
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function update_item_permissions_check( $request ) {
        if (!is_user_logged_in()) {
            return new WP_Error('rest_user_profile_cannot_update', __('Sorry, you cannot update user profiles without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        if ($request->get_param('admin') && !current_user_can('edit_users')) { //管理员更新其他用户资料
            return new WP_Error('rest_user_profile_cannot_update', __('Sorry, you have no authority to update user profiles.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 更新用户资料
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function update_item( $request ) {
        $user_id = $request->get_param('admin') ? intval($request->get_param('uid')) : get_current_user_id();
//        $user = get_user_by('ID', $user_id);
//        if(!$user) {
//            return tt_api_fail(__('The specified user is not existed', 'tt'));
//        }

        $type = $request->get_param('type');
        // 基本信息
        if($type == 'basis') {
            $avatar_type = trim($request->get_param('avatarType'));
            $nickname = sanitize_text_field($request->get_param('nickname'));
            $site = sanitize_text_field($request->get_param('site'));
            $description = sanitize_text_field($request->get_param('description'));

            $result = tt_update_basic_profiles($user_id, $avatar_type, $nickname, $site, $description);
            if(isset($result['success']) && $result['success']) {
                return tt_api_success($result['message']);
            }
            return $result; //WP_Error
        }

        // 扩展信息
        if($type == 'extends') {
            $data = array(
                'ID' => $user_id,
                'tt_qq' => sanitize_text_field($request->get_param('qq')),
                'tt_weibo' => sanitize_text_field($request->get_param('weibo')),
                'tt_weixin' => sanitize_text_field($request->get_param('weixin')),
                'tt_twitter' => sanitize_text_field($request->get_param('twitter')),
                'tt_facebook' => sanitize_text_field($request->get_param('facebook')),
                'tt_googleplus' => sanitize_text_field($request->get_param('googleplus')),
                'tt_alipay_email' => sanitize_text_field($request->get_param('alipay')),
                'tt_alipay_pay_qr' => sanitize_text_field($request->get_param('alipayPay')),
                'tt_wechat_pay_qr' => sanitize_text_field($request->get_param('weixinPay')),
            );

            $result = tt_update_extended_profiles($data);

            if(isset($result['success']) && $result['success']) {
                return tt_api_success($result['message']);
            }
            return $result;
        }

        // 安全信息
        if($type == 'security') {
            $data = array(
                'ID' => $user_id,
                'user_email' => sanitize_email($request->get_param('email'))
            );
            if($password = $request->get_param('password')){
                $data['user_pass'] = sanitize_text_field($password);
            }

            $result = tt_update_security_profiles($data);
            if(isset($result['success']) && $result['success']) {
                return tt_api_success($result['message']);
            }
            return $result; //WP_Error
        }
        return new WP_Error('rest_profile_type_not_found', __('Sorry, invalid profile type', 'tt'), array('status' => 404));
    }
}