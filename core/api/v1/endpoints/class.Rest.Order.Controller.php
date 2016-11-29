<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/27 20:17
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php

/**
 * Class WP_REST_Order_Controller
 */
class WP_REST_Order_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'v1';
        $this->rest_base = 'orders';
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
     * 检查是否有获取多条订单的权限
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function get_items_permissions_check($request)
    {
        if (!is_user_logged_in()) {
            return new WP_Error('rest_orders_cannot_view', __('Sorry, you cannot view orders without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 获取多条订单
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items($request)
    {
        $limit = $request->get_param('limit') ? : 20;
        $offset = $request->get_param('offset') ? : 0;
        $currency_type = $request->get_param('currency') ? : 'all';
        $results = tt_get_orders($limit, $offset, $currency_type);
//        if( $results instanceof WP_Error/*is_wp_error($results)*/ ) {
//            return $results;
//        }

        return tt_api_success('', array('data' => (array)$results));
    }


    /**
     * 判断当前请求是否有权限创建订单
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function create_item_permissions_check( $request ) {
        if (!is_user_logged_in()) {
            return new WP_Error('rest_order_cannot_create', __('Sorry, you cannot create order without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 创建订单
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function create_item( $request ) {
        $is_cart = $request->get_param('from') == 'cart';
        if($is_cart){
            $cart_items = tt_get_cart(get_current_user_id(), true);
            if($cart_items instanceof WP_Error){
                return $cart_items;
            }
            $product_ids = array();
            $order_quantities = array();
            foreach ($cart_items as $cart_item){
                $product_ids[] = intval($cart_item['id']);
                $order_quantities[] = absint($cart_item['quantity']);
            }
            $create = tt_create_combine_orders($product_ids, $order_quantities);
            tt_clear_cart(); // 创建订单成功后清空购物车
        }else{
            $product_id = $request->get_param('productId');
            $product_name = $request->get_param('productName');
            $order_quantity = $request->get_param('orderQuantity');
            $create = count(explode(',', $product_id)) > 1 ? tt_create_combine_orders($product_id, $order_quantity) : tt_create_order($product_id, $product_name, intval($order_quantity));
        }

        if($create instanceof WP_Error) {
            return $create;
        }elseif(!$create){
            return new WP_Error('create_order_failed', __('Create order failed', 'tt'));
        }
        $checkout_nonce = wp_create_nonce('checkout');
        $checkout_url = add_query_arg(array('oid' => $create['order_id'], 'spm' => $checkout_nonce), tt_url_for('checkout'));
        $create['url'] = $checkout_url;
        return tt_api_success(__('Create order successfully', 'tt'), array('data' => $create));
    }


    /**
     * 判断当前请求是否有权限更新指定订单
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function update_item_permissions_check( $request ) {
        if (!is_user_logged_in()) {
            return new WP_Error('rest_order_cannot_update', __('Sorry, you cannot update order without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 更新单条订单
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function update_item( $request ) {
        $order_id = $request['id'];
        if($coupon = $request->get_param('coupon')){
            $update = tt_update_order_by_coupon($order_id, $coupon);
        }else{
            $data = array();
            $format = array();
            if($trade_no = $request->get_param('tradeNo') !== null){
                $data['trade_no'] = $trade_no;
                $format[] = '%s';
            }
            if($order_success_time = $request->get_param('orderSuccessTime') !== null){
                $data['order_success_time'] = $order_success_time;
                $format[] = '%s';
            }
            if($order_status = $request->get_param('orderStatus') !== null){
                $data['order_status'] = $order_status;
                $format[] = '%d';
            }
            if($address_id = $request->get_param('addressId') !== null){
                $data['address_id'] = $address_id;
                $format[] = '%d';
            }
            if($user_message = $request->get_param('userMessage') !== null){
                $data['user_message'] = $user_message;
                $format[] = '%s';
            }
            if($user_alipay = $request->get_param('userAlipay') !== null){
                $data['user_alipay'] = $user_alipay;
                $format[] = '%s';
            }
            $update = tt_update_order($order_id, $data, $format);
        }
        if($update instanceof WP_Error) {
            return $update;
        }elseif(!$update){
            return new WP_Error('order_update_failed', __('Update order failed', 'tt'));
        }
        return tt_api_success('', array('data' => array(
            'order_id' => $order_id
        )));
    }


    /**
     * 检查请求是否有删除订单
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function delete_item_permissions_check( $request ) {

        $current_uid = get_current_user_id();

        if (!$current_uid) {
            return new WP_Error('rest_order_cannot_delete', __('Sorry, you cannot delete order without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 删除订单
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function delete_item( $request ) {
        $order_id = absint($request['id']);
        $result = tt_delete_order_by_order_id($order_id);
        if($result instanceof WP_Error) {
            return $result;
        }elseif(!$result){
            return new WP_Error('rest_order_cannot_delete', __('Delete order failed', 'tt'));
        }
        return tt_api_success(__('Delete order successfully', 'tt'), array('data' => array('order_id' => $order_id)));
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