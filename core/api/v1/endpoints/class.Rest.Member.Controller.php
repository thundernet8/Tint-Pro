<?php
/**
 * Copyright (c) 2014-2017, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/15 16:43
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class WP_REST_Member_Controller
 */
class WP_REST_Member_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'v1';
        $this->rest_base = 'members';
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
     * 检查是否有获取多个会员的权限
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function get_items_permissions_check($request)
    {
        if (!current_user_can('administrator')) {
            return new WP_Error('rest_members_cannot_view', __('Sorry, you are not permitted to view members.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 获取多个会员
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items($request)
    {
        $limit = absint($request->get_param('limit')) ? : 20;
        $offset = absint($request->get_param('offset')) ? : 0;
        $results = tt_get_vip_members(-1, $limit, $offset);

        if( !$results || $results instanceof WP_Error/*is_wp_error($results)*/ ) {
            return tt_api_fail(__('Retrieve members failed', 'tt'), array(), 500);
        }

        return tt_api_success('', array('data' => $results));
    }


    /**
     * 判断请求是否有创建会员的权限
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function create_item_permissions_check($request)
    {
        if (!current_user_can('administrator')) {
            return new WP_Error('rest_member_cannot_create', __('Sorry, you are not permitted to create a member.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }


    /**
     * 创建一个会员
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function create_item($request)
    {
        $type = $request->get_param('type');
        $user_name_or_id = $request->get_param('user');
        if(is_numeric($user_name_or_id)) {
            $user = get_user_by('ID', $user_name_or_id);
        }else{
            $user = get_user_by('login', $user_name_or_id);
        }

        if(!$user){
            return tt_api_fail(__('User you specified is not found', 'tt'), array(), 400);
        }

        // type 验证
        if(!in_array($type, array(Member::MONTHLY_VIP, Member::ANNUAL_VIP, Member::PERMANENT_VIP))){
            return tt_api_fail(__('Member VIP type is not right', 'tt'), array(), 400);
        }

        $add = tt_add_or_update_member($user->ID, $type, 0, 0, true);

        if($add instanceof WP_Error) {
            return $add;
        }elseif(!$add) {
            return tt_api_fail(__('Add member failed', 'tt'), array(), 400);
        }

        return tt_api_success(__('Add member successfully', 'tt'), array());
    }


    /**
     * 判断请求是否有权限读取单个会员
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function get_item_permissions_check( $request ) {
        if (is_user_logged_in()) {
            return new WP_Error('rest_member_cannot_view', __('Sorry, you are not permitted to view a member without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 读取单个会员
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error | WP_REST_Response
     */
    public function get_item( $request ) {
        $id = absint($request['id']);
        $member = tt_get_member($id);
        if(!$member) {
            return tt_api_fail(__('Cannot get the member specified', 'tt'));
        }
        return tt_api_success('', array('data' => $member));
    }


    /**
     * 判断当前请求是否有权限更新指定会员
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function update_item_permissions_check( $request ) {
        if (!current_user_can('administrator')) {
            return new WP_Error('rest_member_cannot_update', __('Sorry, you are not permitted to update a member.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 更新单个会员
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function update_item( $request ) {
        $id = absint($request['id']);
        //TODO
        return null;
    }

    /**
     * 检查请求是否有删除指定会员的权限
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function delete_item_permissions_check( $request ) {
        if (!current_user_can('administrator')) {
            return new WP_Error('rest_member_cannot_delete', __('Sorry, you are not permitted to delete a member.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 删除单个会员
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function delete_item( $request ) {
        $id = (int) $request['id'];

        $result = tt_delete_member_by_id($id);
        if(!$result) {
            return new WP_Error( 'rest_cannot_delete', __( 'The member cannot be deleted.', 'tt' ), array( 'status' => 500 ) );
        }

        return tt_api_success(__('delete member successfully', 'tt'), array('member_id' => $id));
    }
}
