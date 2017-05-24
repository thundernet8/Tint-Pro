<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/30 21:32
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class WP_REST_Coupon_Controller
 */
class WP_REST_Coupon_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'v1';
        $this->rest_base = 'coupons';
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
     * 检查是否有获取多个优惠码的权限
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function get_items_permissions_check($request)
    {
        if (!current_user_can('administrator')) {
            return new WP_Error('rest_coupons_cannot_view', __('Sorry, you are not permitted to view coupons.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 获取多个优惠码
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items($request)
    {
        $limit = absint($request->get_param('limit')) ? : 20;
        $offset = absint($request->get_param('offset')) ? : 0;
        $results = tt_get_coupons(0, $limit, $offset);

        if( !$results || $results instanceof WP_Error/*is_wp_error($results)*/ ) {
            return tt_api_fail(__('Retrieve coupons failed', 'tt'), array(), 500);
        }

        return tt_api_success('', array('data' => $results));
    }


    /**
     * 判断请求是否有创建优惠码的权限
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function create_item_permissions_check($request)
    {
        if (!current_user_can('administrator')) {
            return new WP_Error('rest_coupon_cannot_create', __('Sorry, you are not permitted to create a coupon.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }


    /**
     * 创建一个优惠码
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function create_item($request)
    {
        $type = $request->get_param('type');
        $effect_date = $request->get_param('effectDate');
        $expire_date = $request->get_param('expireDate');
        $code = sanitize_text_field($request->get_param('code'));
        $discount = $request->get_param('discount');

        // type 验证
        if(!in_array($type, array('once', 'multi'))){
            return tt_api_fail(__('Coupon type is not right', 'tt'), array(), 400);
        }

        // Code 验证
        if(strlen($code) < 4){
            return tt_api_fail(__('Coupon code is too short', 'tt'), array(), 400);
        }

        //
        if($discount < 0 || $discount > 1) {
            return tt_api_fail(__('Invalid coupon discount value, should not less than 0 and not greater than 1', 'tt'), array(), 400);
        }

        //
        if(!$effect_date) {
            return tt_api_fail(__('Coupon effect date should not be empty', 'tt'), array(), 400);
        }

        if(!$expire_date) {
            return tt_api_fail(__('Coupon expire date should not be empty', 'tt'), array(), 400);
        }

        $add = tt_add_coupon($code, $type, $discount, $effect_date, $expire_date);

        if($add instanceof WP_Error) {
            return $add;
        }elseif(!$add) {
            return tt_api_fail(__('Add coupon failed', 'tt'), array(), 400);
        }

        return tt_api_success(__('Add coupon successfully', 'tt'), array());
    }


    /**
     * 判断请求是否有权限读取单个优惠码
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function get_item_permissions_check( $request ) {
        if (!current_user_can('administrator')) {
            return new WP_Error('rest_coupon_cannot_view', __('Sorry, you are not permitted to view a coupon.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 读取单个优惠码
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error | WP_REST_Response
     */
    public function get_item( $request ) {
        $id = absint($request['id']);
        $coupon = tt_get_coupon($id);
        if(!$coupon) {
            return tt_api_fail(__('Cannot get the coupon specified', 'tt'));
        }
        return tt_api_success('', array('data' => $coupon));
    }


    /**
     * 判断当前请求是否有权限更新指定优惠码
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function update_item_permissions_check( $request ) {
        if (!current_user_can('administrator')) {
            return new WP_Error('rest_coupon_cannot_update', __('Sorry, you are not permitted to update a coupon.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 更新单个优惠码
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
     * 检查请求是否有删除指定优惠码的权限
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function delete_item_permissions_check( $request ) {
        if (!current_user_can('administrator')) {
            return new WP_Error('rest_coupon_cannot_delete', __('Sorry, you are not permitted to delete a coupon.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 删除单个优惠码
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function delete_item( $request ) {
        $id = (int) $request['id'];

        $result = tt_delete_coupon($id);
        if(!$result) {
            return new WP_Error( 'rest_cannot_delete', __( 'The coupon cannot be deleted.', 'tt' ), array( 'status' => 500 ) );
        }

        return tt_api_success(__('delete coupon successfully', 'tt'), array('coupon_id' => $id));
    }
}
