<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/13 20:42
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

class WP_REST_Post_Star_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'v1';
        $this->rest_base = 'post/stars';
    }

    /**
     * 注册路由
     */
    public function register_routes(){

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<post_id>[\d]+)', array(
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
     * 判断请求是否有权限读取文章的Star数量
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function get_item_permissions_check( $request ) {
        return true;
    }

    /**
     * 读取文章的Star数量
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error | WP_REST_Response
     */
    public function get_item( $request ) {
        $post_id = absint($request['post_id']);
        //$post_stars = absint(get_comment_meta($post_id, 'tt_post_stars', true));
        $star_user_ids = array_unique(get_post_meta( $post_id, 'tt_post_star_users', false));
        $post_stars = count($star_user_ids);
        return tt_api_success('', array('stars' => $post_stars));
    }


    /**
     * 判断当前请求是否有权限更新文章的Star数量
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function update_item_permissions_check( $request ) {

        return true;
    }

    /**
     * 更新文章的Star数量
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function update_item( $request ) {
        $post_id = absint($request['post_id']);
        if(!$post_id) {
            return tt_api_fail(__('Wrong post id', 'tt'));
        }

        $nonce = $request->get_param('postStarNonce');
        if(!wp_verify_nonce($nonce, 'tt_post_star_nonce')) {
            return tt_api_fail(__('Nonce verify failed', 'tt'));
        }

        $user = wp_get_current_user();
        if(!$user->ID) {
            return tt_api_fail(__('You must be logged in to star a post', 'tt'));
        }

        //$pre_stars = get_post_meta($post_id, 'tt_post_stars', true);
        //$stars = absint($pre_stars) + 1;
        //$stared = update_post_meta($post_id, 'tt_post_stars', $stars);
        $star_user_ids = array_unique(get_post_meta( $post_id, 'tt_post_star_users', false));
        if(in_array($user->ID, $star_user_ids)) {
            $post_stars = count($star_user_ids);
        }else{
            $post_stars = count($star_user_ids) + 1;
            $add = add_post_meta($post_id, 'tt_post_star_users', $user->ID); // Note: tt_post_star_users不唯一
            if($add) {
                do_action('tt_stared_post', $post_id); //TODO clear cache
            }else{
                return tt_api_fail(__('Star post failed', 'tt'), array(), '409');
            }
        }

        return tt_api_success(__('Star post successfully', 'tt'), array('stars' => $post_stars, 'uid' => $user->ID, 'name' => $user->display_name, 'avatar' => tt_get_avatar($user->ID, 'small')));
    }
}
