<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/13 11:20
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class WP_REST_Comment_Star_Controller
 */
class WP_REST_Comment_Star_Controller extends WP_REST_Controller
{

    public function __construct()
    {
        $this->namespace = 'v1';
        $this->rest_base = 'comment/stars';
    }

    /**
     * 注册路由
     */
    public function register_routes(){

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<comment_id>[\d]+)', array(
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
     * 判断请求是否有权限读取评论的Star数量
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function get_item_permissions_check( $request ) {
        return true;
    }

    /**
     * 读取评论的Star数量
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error | WP_REST_Response
     */
    public function get_item( $request ) {
        $comment_id = absint($request['comment_id']);
        $comment_stars = (int)get_comment_meta($comment_id, 'tt_comment_likes', true);
        return tt_api_success('', array('stars' => $comment_stars));
    }


    /**
     * 判断当前请求是否有权限更新评论的Star数量
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function update_item_permissions_check( $request ) {

        return true;
    }

    /**
     * 更新评论的Star数量
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function update_item( $request ) {
        $comment_id = absint($request['comment_id']);
        if(!$comment_id) {
            return tt_api_fail(__('Wrong comment id', 'tt'));
        }

        $nonce = $request->get_param('commentStarNonce');
        if(!wp_verify_nonce($nonce, 'tt_comment_star_nonce')) {
            return tt_api_fail(__('Nonce verify failed', 'tt'));
        }

        $pre_stars = get_comment_meta($comment_id, 'tt_comment_likes', true);
        $stars = absint($pre_stars) + 1;
        $stared = update_comment_meta($comment_id, 'tt_comment_likes', $stars);

        if(!$stared) {
            return tt_api_fail(__('Star comment failed', 'tt'), array(), '409');
        }

        do_action('tt_stared_comment', $comment_id); //TODO clear cache

        return tt_api_success(__('Star comment successfully', 'tt'), array('stars' => $stars));
    }
}
