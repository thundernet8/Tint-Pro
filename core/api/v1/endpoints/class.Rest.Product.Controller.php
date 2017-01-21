<?php
/**
 * Copyright (c) 2014-2017, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/11 21:20
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class WP_REST_Product_Controller
 */
class WP_REST_Product_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'v1';
        $this->rest_base = 'products';
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
//            array(
//                'methods' => WP_REST_Server::DELETABLE,
//                'callback' => array( $this, 'delete_item' ),
//                'permission_callback' => array( $this, 'delete_item_permissions_check' ),
//                'args' => array(
//                    'force'    => array(
//                        'default'     => false,
//                        'description' => __( 'Required to be true, as resource does not support trashing.' ),
//                    ),
//                    'reassign' => array(),
//                ),
//            ),
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

    }


    /**
     * 检查是否有获取多篇商品的权限
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function get_items_permissions_check($request)
    {
        return true;
    }

    /**
     * 获取多篇商品
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items($request)
    {
        $limit = $request->get_param('limit') ? : 20;
        $offset = $request->get_param('offset') ? : 0;
        $products = array(); // TODO

        return tt_api_success('', array('data' => (array)$products));
    }


    /**
     * 判断当前请求是否有权限创建商品
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function create_item_permissions_check( $request ) {
        $action = $request->get_param('action');
        if ($action == 'publish' && !current_user_can('publish_posts')) {
            return new WP_Error('rest_product_cannot_create', __('Sorry, you do not have the capability to publish a product.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }elseif(!current_user_can('edit_posts')){
            return new WP_Error('rest_product_cannot_create', __('Sorry, you do not have the capability to contribute a product.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 创建商品
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function create_item( $request ) {
        $title = sanitize_text_field(trim($request->get_param('title')));
        if(strlen($title) < 10) {
            return new WP_Error('create_product_failed', __('The product name is too short or empty', 'tt'));
        }

        $content = trim($request->get_param('content'));
        if(strlen($content) < 100) {
            return new WP_Error('create_product_failed', __('The product description is too short or empty', 'tt'));
        }

        $excerpt = sanitize_text_field(trim($request->get_param('excerpt')));
        $cat = (int)sanitize_text_field(trim($request->get_param('cat')));
        //$tags = sanitize_text_field(trim($request->get_param('tags')));

        $action = in_array($request->get_param('action'), array('publish', 'draft', 'pending')) ? $request->get_param('action') : 'draft';

        // 插入商品
        $new_product = wp_insert_post( array(
            'post_type'     => 'product',
            'post_title'    => $title,
            'post_excerpt'  => $excerpt,
            'post_content'  => $content,
            'post_status'   => $action,
            'post_author'   => get_current_user_id(),
            'tax_input'     => $cat
        ) );

        if($new_product instanceof WP_Error) {
            return $new_product;
        }

        // 更新Meta

        //TODO email actions or notifications
        $url = $action == 'publish' ? get_permalink($new_product) : tt_url_for('manage_products');
        return tt_api_success(__('Create product successfully', 'tt'), array('data' => array('url' => $url)));
    }


    /**
     * 判断当前请求是否有权限更新指定商品
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function update_item_permissions_check( $request ) {
        //$action = $request->get_param('action');
//        if ($action == 'publish' && !current_user_can('publish_posts')) {
//            return new WP_Error('rest_product_cannot_update', __('Sorry, you do not have the capability to publish a product.', 'tt'), array('status' => tt_rest_authorization_required_code()));
//        }elseif(!current_user_can('edit_posts')){
//            return new WP_Error('rest_product_cannot_update', __('Sorry, you do not have the capability to contribute a product.', 'tt'), array('status' => tt_rest_authorization_required_code()));
//        }
        if(!current_user_can('administrator')) {
            return new WP_Error('rest_product_cannot_update', __('Sorry, you do not have the capability to contribute a product.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 更新单个商品
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function update_item( $request ) {
        $post_id = intval($request['id']);
        $action = in_array($request->get_param('action'), array('publish', 'draft', 'pending', 'trash')) ? $request->get_param('action') : 'draft';
        // 只更新post status的请求处理
        if($request->get_param('onlyStatus')) {
            $update_product = wp_update_post( array( //Return: The ID of the post if the post is successfully updated in the database. Otherwise returns WP_Error
                'ID'            => $post_id,
                'post_status'   => $action
            ), true );
            if($update_product instanceof WP_Error) {
                return $update_product;
            }elseif(!$update_product){
                return new WP_Error('update_product_failed', __('Handle product failed', 'tt'));
            }
            return tt_api_success(__('Handle product successfully', 'tt'));
        }

        // 普通投稿情况
        $title = sanitize_text_field(trim($request->get_param('title')));
        if(strlen($title) < 10) {
            return new WP_Error('update_product_failed', __('The product name is too short or empty', 'tt'));
        }

        $content = trim($request->get_param('content'));
        if(strlen($content) < 100) {
            return new WP_Error('update_product_failed', __('The product description is too short or empty', 'tt'));
        }

        $excerpt = sanitize_text_field(trim($request->get_param('excerpt')));
        $cat = (int)sanitize_text_field(trim($request->get_param('cat')));
        //$tags = sanitize_text_field(trim($request->get_param('tags')));



        // 插入商品
        $update_product = wp_update_post( array( //Return: The ID of the post if the post is successfully updated in the database. Otherwise returns 0
            'ID'            => $post_id,
            'post_title'    => $title,
            'post_excerpt'  => $excerpt,
            'post_content'  => $content,
            'post_status'   => $action,
            'post_author'   => get_current_user_id(),
            'tax_input'     => $cat,
            //'tags_input'    => $tags
        ) );

        if($update_product instanceof WP_Error) {
            return $update_product;
        }

        if(!$update_product) {
            return tt_api_fail(__('Update the product failed'));
        }

        // 更新Meta

        //TODO email actions or notifications
        $url = $action == 'publish' ? get_permalink($update_product) : tt_url_for('manage_products');
        return tt_api_success(__('Update product successfully', 'tt'), array('data' => array('url' => $url)));
    }
}