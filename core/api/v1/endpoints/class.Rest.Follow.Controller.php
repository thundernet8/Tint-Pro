<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/30 19:17
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class WP_REST_User_Follow_Controller
 */
class WP_REST_User_Follow_Controller extends WP_REST_Controller
{

    public function __construct()
    {
        $this->namespace = 'v1';
        $this->rest_base = 'users';
    }

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes()
    {
        // 粉丝
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<uid>[\d]+)/followers', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_follower_items'),
                'permission_callback' => array($this, 'get_follower_items_permissions_check'),
                'args' => array(
                    'context' => $this->get_context_param(array('default' => 'view')),
                ),
            ),
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_follower_item'),
                'permission_callback' => array($this, 'create_follower_item_permissions_check'),
                'args' => $this->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
            ),
            'schema' => array($this, 'get_public_item_schema'),
        ));

        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/followers', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_follower_items'),
                'permission_callback' => array($this, 'get_follower_items_permissions_check'),
                'args' => array(
                    'context' => $this->get_context_param(array('default' => 'view')),
                ),
            ),
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_follower_item'),
                'permission_callback' => array($this, 'create_follower_item_permissions_check'),
                'args' => $this->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
            ),
            'schema' => array($this, 'get_public_item_schema'),
        ));

//        register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<uid>[\d]+)/followers/(?P<fid>[\d]+)', array(
//            array(
//                'methods'         => WP_REST_Server::READABLE,
//                'callback'        => array( $this, 'get_follower_item' ),
//                'permission_callback' => array( $this, 'get_follower_item_permissions_check' ),
//                'args'            => array(
//                    'context'          => $this->get_context_param( array( 'default' => 'view' ) ),
//                ),
//            ),
//            array(
//                'methods'         => WP_REST_Server::EDITABLE,
//                'callback'        => array( $this, 'update_follower_item' ),
//                'permission_callback' => array( $this, 'update_follower_item_permissions_check' ),
//                'args'            => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
//            ),
//            array(
//                'methods' => WP_REST_Server::DELETABLE,
//                'callback' => array( $this, 'delete_follower_item' ),
//                'permission_callback' => array( $this, 'delete_follower_item_permissions_check' ),
//                'args' => array(
//                    'force'    => array(
//                        'default'     => false,
//                        'description' => __( 'Required to be true, as resource does not support trashing.' ),
//                    ),
//                    'reassign' => array(),
//                ),
//            ),
//            'schema' => array( $this, 'get_public_item_schema' ),
//        ) );

        // 关注
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<uid>[\d]+)/following', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_following_items'),
                'permission_callback' => array($this, 'get_following_items_permissions_check'),
                'args' => array(
                    'context' => $this->get_context_param(array('default' => 'view')),
                ),
            ),
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_following_item'),
                'permission_callback' => array($this, 'create_following_item_permissions_check'),
                'args' => $this->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
            ),
            'schema' => array($this, 'get_public_item_schema'),
        ));

        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/following', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_following_items'),
                'permission_callback' => array($this, 'get_following_items_permissions_check'),
                'args' => array(
                    'context' => $this->get_context_param(array('default' => 'view')),
                ),
            ),
//            array(
//                'methods' => WP_REST_Server::CREATABLE,
//                'callback' => array($this, 'create_following_item'),
//                'permission_callback' => array($this, 'create_following_item_permissions_check'),
//                'args' => $this->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
//            ),
            'schema' => array($this, 'get_public_item_schema'),
        ));

//        register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<uid>[\d]+)/following/(?P<fid>[\d]+)', array(
//            array(
//                'methods'         => WP_REST_Server::READABLE,
//                'callback'        => array( $this, 'get_following_item' ),
//                'permission_callback' => array( $this, 'get_following_item_permissions_check' ),
//                'args'            => array(
//                    'context'          => $this->get_context_param( array( 'default' => 'view' ) ),
//                ),
//            ),
//            array(
//                'methods'         => WP_REST_Server::EDITABLE,
//                'callback'        => array( $this, 'update_following_item' ),
//                'permission_callback' => array( $this, 'update_following_item_permissions_check' ),
//                'args'            => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
//            ),
//            array(
//                'methods' => WP_REST_Server::DELETABLE,
//                'callback' => array( $this, 'delete_follower_item' ),
//                'permission_callback' => array( $this, 'delete_follower_item_permissions_check' ),
//                'args' => array(
//                    'force'    => array(
//                        'default'     => false,
//                        'description' => __( 'Required to be true, as resource does not support trashing.' ),
//                    ),
//                    'reassign' => array(),
//                ),
//            ),
//            'schema' => array( $this, 'get_public_item_schema' ),
//        ) );
    }


    /* 我的粉丝 */

    /**
     * Permissions check for getting all followers.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function get_follower_items_permissions_check($request)
    {
//        if (!is_user_logged_in()) {
//            return new WP_Error('rest_followers_cannot_view', __('Sorry, you cannot view followers without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
//        }

        return true;
    }

    /**
     * Get all followers
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_follower_items($request)
    {
        $uid = absint($request['uid']) ? : get_current_user_id();
        $limit = absint($request->get_param('limit')) ? : 20;
        $offset = absint($request->get_param('offset')) ? : 0;
        $results = tt_get_followers($uid, $limit, $offset);

        if( $results instanceof WP_Error/*is_wp_error($results)*/) {
            return $results;
        }

        return rest_ensure_response($results);
    }


    /**
     * Check if a given request has access create follower
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function create_follower_item_permissions_check($request)
    {

        if (!is_user_logged_in()) {
            return new WP_Error('rest_follower_cannot_create', __('Sorry, you cannot follow or unfollow without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }

        return true;
    }


    /**
     * Create a follower for the user
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function create_follower_item($request)
    {
        $uid = absint($request['uid']); // 被关注者, 关注者为当前用户
        $action = in_array($request->get_param('action'), array('follow', 'unfollow')) ? $request->get_param('action') : 'follow';
        $result = $action == 'follow' ? tt_follow($uid) : tt_unfollow($uid);

        if($result instanceof WP_Error) {
            return $result;
        }

        return rest_ensure_response($result);
    }



    /* 我的关注 */

    /**
     * Permissions check for getting all following users.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function get_following_items_permissions_check($request)
    {
//        if (!is_user_logged_in()) {
//            return new WP_Error('rest_followers_cannot_view', __('Sorry, you cannot view followers without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
//        }

        return true;
    }

    /**
     * Get all following users
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_following_items($request)
    {
        $uid = absint($request['uid']) ? : get_current_user_id();
        $limit = absint($request->get_param('limit')) ? : 20;
        $offset = absint($request->get_param('offset')) ? : 0;
        $results = tt_get_following($uid, $limit, $offset);

        if( $results instanceof WP_Error/*is_wp_error($results)*/) {
            return $results;
        }

        return rest_ensure_response($results);
    }

}



