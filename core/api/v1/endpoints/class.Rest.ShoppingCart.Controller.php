<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/22 22:23
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class WP_REST_ShoppingCart_Controller
 */
class WP_REST_ShoppingCart_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'v1';
        $this->rest_base = 'shoppingcart';
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
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array( $this, 'delete_items' ),
                'permission_callback' => array( $this, 'delete_items_permissions_check' ),
                'args' => array(
                    'force'    => array(
                        'default'     => false,
                        'description' => __( 'Required to be true, as resource does not support trashing.' ),
                    ),
                    'reassign' => array(),
                ),
            ),
            'schema' => array($this, 'get_public_item_schema'),
        ));

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_item'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => $this->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
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
     * 检查是否有获取购物车内容的权限
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function get_items_permissions_check($request)
    {
        if (!is_user_logged_in()) {
            return new WP_Error('rest_shoppingcart_cannot_view', __('Sorry, you cannot view shopping cart without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 获取购物车内容
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items($request)
    {
        $results = tt_get_cart(0, true);
        if( $results instanceof WP_Error/*is_wp_error($results)*/ ) {
            return $results;
        }

        return tt_api_success('', array('data' => (array)$results));
    }


    /**
     * 判断当前请求是否有权限添加内容至购物车
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function create_item_permissions_check( $request ) {
        if (!is_user_logged_in()) {
            return new WP_Error('rest_cart_cannot_update', __('Sorry, you cannot update cart without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 更新购物车
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function create_item( $request ) {
        $product_id = absint($request['id']);
        $quantity = $request->get_param('quantity') ? absint($request->get_param('quantity')) : 1;
        $result = tt_add_cart($product_id, $quantity, true);
        if($result instanceof WP_Error) {
            return $result;
        }
        return tt_api_success(__('Add to cart successfully', 'tt'), array('data' => $result));
    }

    /**
     * 检查请求是否有删除购物车指定内容
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function delete_item_permissions_check( $request ) {

        $current_uid = get_current_user_id();

        if (!$current_uid) {
            return new WP_Error('rest_cart_cannot_delete', __('Sorry, you cannot delete cart item without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 删除购物车指定内容
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function delete_item( $request ) {
        $product_id = absint($request['id']);
        $quantity = $request->get_param('quantity') ? absint($request->get_param('quantity')) : 1;
        $result = tt_delete_cart($product_id, $quantity, true);
        if($result instanceof WP_Error) {
            return $result;
        }
        return tt_api_success(__('Delete item from cart successfully', 'tt'), array('data' => $result));
    }


    /**
     * 检查请求是否有清空购物车
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function delete_items_permissions_check( $request ) {

        $current_uid = get_current_user_id();

        if (!$current_uid) {
            return new WP_Error('rest_cart_cannot_delete', __('Sorry, you cannot delete cart items without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 清空购物车
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function delete_items( $request ) {
        $result = tt_clear_cart(true);
        if($result instanceof WP_Error) {
            return $result;
        }
        return tt_api_success(__('Delete items from cart successfully', 'tt'), array('data' => $result));
    }
}