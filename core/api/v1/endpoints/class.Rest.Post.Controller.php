<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/24 18:34
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class WP_REST_Post_Controller
 */
class WP_REST_Post_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'v1';
        $this->rest_base = 'posts';
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
     * 检查是否有获取多篇文章的权限
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function get_items_permissions_check($request)
    {
        return true;
    }

    /**
     * 获取多篇文章
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items($request)
    {
        $limit = $request->get_param('limit') ? : 20;
        $offset = $request->get_param('offset') ? : 0;
        $posts = array(); // TODO

        return tt_api_success('', array('data' => (array)$posts));
    }


    /**
     * 判断当前请求是否有权限创建文章
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function create_item_permissions_check( $request ) {
        $action = $request->get_param('action');
        if ($action == 'publish' && !current_user_can('publish_posts')) {
            return new WP_Error('rest_post_cannot_create', __('Sorry, you do not have the capability to publish a post.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }elseif(!current_user_can('edit_posts')){
            return new WP_Error('rest_post_cannot_create', __('Sorry, you do not have the capability to contribute a post.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 创建文章
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function create_item( $request ) {
        $title = sanitize_text_field(trim($request->get_param('title')));
        if(strlen($title) < 10) {
            return new WP_Error('create_post_failed', __('The post title is too short or empty', 'tt'));
        }

        $content = trim($request->get_param('content'));
        if(strlen($content) < 100) {
            return new WP_Error('create_post_failed', __('The post content is too short or empty', 'tt'));
        }

        $excerpt = sanitize_text_field(trim($request->get_param('excerpt')));
        $cat = (int)sanitize_text_field(trim($request->get_param('cat')));
        $tags = sanitize_text_field(trim($request->get_param('tags')));
        $cc_title = sanitize_text_field(trim($request->get_param('ccTitle')));
        $cc_link = esc_url(trim($request->get_param('ccLink')));
        $free_dl = sanitize_text_field(trim($request->get_param('freeDl')));
        $sale_dl = sanitize_text_field(trim($request->get_param('saleDl')));

        $action = in_array($request->get_param('action'), array('publish', 'draft', 'pending')) ? $request->get_param('action') : 'draft';

        // 插入文章
        $new_post = wp_insert_post( array(
            'post_title'    => $title,
            'post_excerpt'  => $excerpt,
            'post_content'  => $content,
            'post_status'   => $action,
            'post_author'   => get_current_user_id(),
            'post_category' => $cat,
            'tags_input'    => $tags
        ) );

        if($new_post instanceof WP_Error) {
            return $new_post;
        }

        // 更新Meta
        if(!empty($cc_title) && !empty($cc_link)) {
            $cc = array(
                'source_title' => $cc_title,
                'source_link' => $cc_link
            );
            update_post_meta($new_post, 'tt_post_copyright', maybe_serialize($cc));
        }
        if(!empty($free_dl)){
            update_post_meta($new_post, 'tt_free_dl', $free_dl);
        }
        if(!empty($sale_dl)) {
            update_post_meta($new_post, 'tt_sale_dl', $sale_dl);
        }
        //TODO email actions or notifications
        $url = $action == 'publish' ? get_permalink($new_post) : tt_url_for('my_drafts');
        return tt_api_success(__('Create post successfully', 'tt'), array('data' => array('url' => $url)));
    }


    /**
     * 判断当前请求是否有权限更新指定文章
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function update_item_permissions_check( $request ) {
        $action = $request->get_param('action');
        if ($action == 'publish' && !current_user_can('publish_posts')) {
            return new WP_Error('rest_post_cannot_update', __('Sorry, you do not have the capability to publish a post.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }elseif(!current_user_can('edit_posts')){
            return new WP_Error('rest_post_cannot_update', __('Sorry, you do not have the capability to contribute a post.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * 更新单篇文章
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function update_item( $request ) {
        $post_id = intval($request['id']);
        $action = in_array($request->get_param('action'), array('publish', 'draft', 'pending', 'trash')) ? $request->get_param('action') : 'draft';
        // 只更新post status的请求处理
        if($request->get_param('onlyStatus')) {
            $update_post = wp_update_post( array( //Return: The ID of the post if the post is successfully updated in the database. Otherwise returns WP_Error
                'ID'            => $post_id,
                'post_status'   => $action
            ), true );
            if($update_post instanceof WP_Error) {
                return $update_post;
            }elseif(!$update_post){
                return new WP_Error('update_post_failed', __('Handle post failed', 'tt'));
            }
            return tt_api_success(__('Handle post successfully', 'tt'));
        }

        // 普通投稿情况
        $title = sanitize_text_field(trim($request->get_param('title')));
        if(strlen($title) < 10) {
            return new WP_Error('update_post_failed', __('The post title is too short or empty', 'tt'));
        }

        $content = trim($request->get_param('content'));
        if(strlen($content) < 100) {
            return new WP_Error('update_post_failed', __('The post content is too short or empty', 'tt'));
        }

        $excerpt = sanitize_text_field(trim($request->get_param('excerpt')));
        $cat = (int)sanitize_text_field(trim($request->get_param('cat')));
        $tags = sanitize_text_field(trim($request->get_param('tags')));
        $cc_title = sanitize_text_field(trim($request->get_param('ccTitle')));
        $cc_link = esc_url(trim($request->get_param('ccLink')));
        $free_dl = sanitize_text_field(trim($request->get_param('freeDl')));
        $sale_dl = sanitize_text_field(trim($request->get_param('saleDl')));



        // 插入文章
        $update_post = wp_update_post( array( //Return: The ID of the post if the post is successfully updated in the database. Otherwise returns 0
            'ID'            => $post_id,
            'post_title'    => $title,
            'post_excerpt'  => $excerpt,
            'post_content'  => $content,
            'post_status'   => $action,
            'post_author'   => get_current_user_id(),
            'post_category' => $cat,
            'tags_input'    => $tags
        ) );

        if($update_post instanceof WP_Error) {
            return $update_post;
        }

        if(!$update_post) {
            return tt_api_fail(__('Update the post failed'));
        }

        // 更新Meta
        if(!empty($cc_title) && !empty($cc_link)) {
            $cc = array(
                'source_title' => $cc_title,
                'source_link' => $cc_link
            );
            update_post_meta($update_post, 'tt_post_copyright', maybe_serialize($cc));
        }
        if(!empty($free_dl)){
            update_post_meta($update_post, 'tt_free_dl', $free_dl);
        }
        if(!empty($sale_dl)) {
            update_post_meta($update_post, 'tt_sale_dl', $sale_dl);
        }
        //TODO email actions or notifications
        $url = $action == 'publish' ? get_permalink($update_post) : tt_url_for('my_drafts');
        return tt_api_success(__('Update post successfully', 'tt'), array('data' => array('url' => $url)));
    }
}