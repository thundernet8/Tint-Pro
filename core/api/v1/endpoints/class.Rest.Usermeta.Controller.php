<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/21 06:02
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class WP_REST_Usermeta_Controller
 */
class WP_REST_Usermeta_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'v1';
        $this->rest_base = 'users/metas';
    }

    /**
     * 注册路由
     */
    public function register_routes(){

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<key>[\S]+)', array(
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
     * 判断请求是否有权限读取单个usermeta
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function get_item_permissions_check( $request ) {
        if (!is_user_logged_in()) {
            return new WP_Error('rest_usermeta_cannot_view', __('Sorry, you cannot retrieve usermeta without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 读取单个usermeta
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error | WP_REST_Response
     */
    public function get_item( $request ) {
        $user_id = get_current_user_id();
        $key = strval($request['key']);
        $multi = $request->get_param('multi') ? : 0;
        $meta = get_user_meta($user_id, $key, !!$multi);
        if(!$meta) {
            return tt_api_fail(__('Cannot get the usermeta specified', 'tt'));
        }
        return tt_api_success('', array('data' => $meta));
    }


    /**
     * 判断当前请求是否有权限更新指定usermeta
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
        $user_id = get_current_user_id();
        $key = strval($request['key']);
        $value = $request->get_param('value');
        $multi = $request->get_param('multi') ? : 0;
        if(!!$multi) {
            $meta = add_user_meta($user_id, $key, $value, false);
        }else{
            $meta = update_user_meta($user_id, $key, $value); //Meta ID if the key didn't exist; true on successful update; false on failure or if $meta_value is the same as the existing meta value in the database.
        }
        if(!$meta) {
            return tt_api_fail(__('Cannot update or create the usermeta specified', 'tt'));
        }
        return tt_api_success('', array('data' => $meta));
    }

    /**
     * 检查请求是否有删除指定消息的权限
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function delete_item_permissions_check( $request ) {

        $current_uid = get_current_user_id();

        if (!$current_uid) {
            return new WP_Error('rest_usermeta_cannot_delete', __('Sorry, you cannot delete usermeta without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
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
        $user_id = get_current_user_id();
        $key = strval($request['key']);
        $value = $request->get_param('value');
        $delete = delete_user_meta($user_id, $key, $value);
        if(!$delete) {
            return new WP_Error( 'rest_cannot_delete', __( 'The usermeta cannot be deleted.', 'tt' ), array( 'status' => 500 ) );
        }

        return tt_api_success(__('delete usermeta successfully', 'tt'));
    }
}