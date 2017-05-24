<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/02 21:46
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class WP_REST_Message_Controller
 */
class WP_REST_Message_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'v1';
        $this->rest_base = 'messages';
    }

    /**
     * 注册路由
     */
    public function register_routes(){

        register_rest_route($this->namespace, '/' . $this->rest_base, array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_items'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'context' => $this->get_context_param(array('default' => 'view')),
                ),
            ),
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_item'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => $this->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
            ),
            'schema' => array($this, 'get_public_item_schema'),
        ));

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
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array( $this, 'delete_item' ),
                'permission_callback' => array( $this, 'delete_item_permissions_check' ),
                'args' => array(
                    'force'    => array(
                        'default'     => false,
                        'description' => __( 'Required to be true, as resource does not support trashing.' ),
                    ),
                    'reassign' => array(),
                ),
            ),
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );
    }


    /**
     * 检查是否有获取多条消息的权限
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function get_items_permissions_check($request)
    {
        if (!is_user_logged_in()) {
            return new WP_Error('rest_messages_cannot_view', __('Sorry, you cannot view messages without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 获取多条消息
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items($request)
    {
        $limit = absint($request->get_param('limit')) ? : 20;
        $offset = absint($request->get_param('offset')) ? : 0;
        $read = $request->get_param('read') ? : 0; //默认获取未读消息
        $results = tt_get_pm(0, $limit, $offset, $read);

        if( !$results || $results instanceof WP_Error/*is_wp_error($results)*/ ) {
            return tt_api_fail(__('Retrieve messages failed', 'tt'), array(), 500);
        }

        return tt_api_success('', array('data' => $results));
    }


    /**
     * 判断请求是否有创建消息的权限
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function create_item_permissions_check($request)
    {

        if (!is_user_logged_in()) {
            return new WP_Error('rest_message_cannot_create', __('Sorry, you cannot create message without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }

        return true;
    }


    /**
     * 创建一条消息
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function create_item($request)
    {
        $current_uid = get_current_user_id();
        $pm_nonce = $request->get_param('pmNonce');
        $receiver_id = $request->get_param('receiverId');
        $message = sanitize_text_field($request->get_param('message'));

        // Nonce 验证
        if(!wp_verify_nonce($pm_nonce, 'tt_pm_nonce')){
            return tt_api_fail(__('Verify nonce failed', 'tt'), array(), 400);
        }

        //
        if(!$receiver_id) {
            return tt_api_fail(__('Invalid receiver', 'tt'), array(), 400);
        }

        //
        if(!$message) {
            return tt_api_fail(__('Message content cannot be blank', 'tt'), array(), 400);
        }

        $send = tt_create_pm($receiver_id, $current_uid, $message, true);

        if(!$send) {
            return tt_api_fail(__('Send message failed', 'tt'), array(), 400);
        }

        $people_url = get_author_posts_url($current_uid);
        $msg_html = '<div class="message chat-message"><a class="people-link" href="' . $people_url . '"><img class="avatar" src="' . tt_get_avatar($current_uid) . '"></a><div class="msg-main"><div class="msg-content"><a class="sender-label" href="' . $people_url . '">' . sprintf(__('You to %s', 'tt'), get_user_by('ID', $receiver_id)->display_name) . '</a> : ' . $message . '</div><div class="msg-meta"><span class="msg-date text-muted">' . current_time('mysql') . '</span></div></div></div>';

        return tt_api_success(__('Send message successfully', 'tt'), array(
            'data' => array(
                'chatUrl' => tt_get_user_chat_url($receiver_id),
                'msgHtml' => $msg_html
            )
        ));
    }


    /**
     * 判断请求是否有权限读取单条消息
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function get_item_permissions_check( $request ) {
        if (!is_user_logged_in()) {
            return new WP_Error('rest_message_cannot_view', __('Sorry, you cannot view message without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 读取单条消息
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error | WP_REST_Response
     */
    public function get_item( $request ) {
        $id = absint($request['id']);
        $message = tt_get_message($id);
        if(!$message) {
            return tt_api_fail(__('Cannot get the message specified', 'tt'));
        }
        return tt_api_success('', array('data' => $message));
    }


    /**
     * 判断当前请求是否有权限更新指定消息
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function update_item_permissions_check( $request ) {
        if (!is_user_logged_in()) {
            return new WP_Error('rest_message_cannot_update', __('Sorry, you cannot update message without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 更新单条消息
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function update_item( $request ) {
        $msg_id = absint($request['id']);
        $action = $request->get_param('action');
        if($action == 'markRead') {
            $result = tt_mark_message($msg_id);
            if($result) {
                return tt_api_success(__('Mark message read successfully', 'tt'));
            }
            return tt_api_fail(__('Mark message read failed', 'tt'));
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