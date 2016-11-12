<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/12 23:21
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php

class WP_REST_User_Status_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'v1';
        $this->rest_base = 'users/status';
    }

    /**
     * 注册路由
     */
    public function register_routes(){

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
            array(
                'methods'         => WP_REST_Server::READABLE,
                'callback'        => array( $this, 'get_item' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' ),
                'args'            => array(
                    'context'          => $this->get_context_param( array( 'default' => 'view' ) ),
                ),
            ),
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
     * 判断请求是否有权限读取单个用户账户状态
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function get_item_permissions_check( $request ) {
        if (!is_user_logged_in()) {
            return new WP_Error('rest_user_status_cannot_view', __('Sorry, you cannot view user account status without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 读取单个用户账户状态
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error | WP_REST_Response
     */
    public function get_item( $request ) {
        $id = absint($request['id']);
        $user = get_user_by('ID', $id);
        if(!$user) {
            return tt_api_fail(__('The specified user is not existed', 'tt'));
        }
        return tt_api_success('', array('data' => tt_get_account_status($id, 'array')));
    }


    /**
     * 判断当前请求是否有权限更新指定用户账户状态
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function update_item_permissions_check( $request ) {
        if (!is_user_logged_in()) {
            return new WP_Error('rest_user_status_cannot_update', __('Sorry, you cannot update user account status without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        if (!current_user_can('edit_users')) {
            return new WP_Error('rest_user_status_cannot_update', __('Sorry, you have no authority to update user account status.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 更新用户账户状态
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function update_item( $request ) {
        $id = absint($request['id']);
        $user = get_user_by('ID', $id);
        if(!$user) {
            return tt_api_fail(__('The specified user is not existed', 'tt'));
        }

        // Nonce 验证
        $ban_nonce = $request->get_param('banNonce');
        if(!wp_verify_nonce($ban_nonce, 'tt_ban_nonce')){
            return tt_api_fail(__('Verify nonce failed', 'tt'), array(), 400);
        }

        $action = $request->get_param('action');
        if($action == 'ban') {
            // Reason
            $reason = sanitize_text_field($request->get_param('reason'));
            if(!$reason) {
                return tt_api_fail(__('A reason is required', 'tt'), array(), 400);
            }

            $result = tt_ban_user($id, $reason, 'array');
            if($result['success']) {
                return tt_api_success($result['message'], 'tt');
            }
            return tt_api_fail($result['message']);
        }
        if($action == 'unban') {
            $result = tt_unban_user($id, 'array');
            if($result['success']) {
                return tt_api_success($result['message'], 'tt');
            }
            return tt_api_fail($result['message']);
        }
        return null;
    }

    /**
     * 检查请求是否有删除指定消息的权限
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function delete_item_permissions_check( $request ) {

        $id = (int) $request['id'];
        $current_uid = get_current_user_id();

        if (!$current_uid) {
            return new WP_Error('rest_message_cannot_delete', __('Sorry, you cannot delete message without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        $message = tt_get_message($id);
        if(!$message) {
            return new WP_Error('rest_message_not_found', __('Sorry, the message is not found.', 'tt'), array('status' => 404));
        }

        if($message->user_id != $current_uid && $message->sender_id != $current_uid) {
            return new WP_Error('rest_message_cannot_delete', __('Sorry, you cannot delete message not belong to you.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }

        return true;
    }

    /**
     * 删除单条消息
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function delete_item( $request ) {
        $id = (int) $request['id'];

        $result = tt_trash_message($id);
        if(!$result) {
            return new WP_Error( 'rest_cannot_delete', __( 'The message cannot be deleted.', 'tt' ), array( 'status' => 500 ) );
        }

        return tt_api_success(__('delete message successfully', 'tt'), array('msg_id' => $id));
    }
}
