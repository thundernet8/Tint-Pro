<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/04 20:50
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
<<<<<<< HEAD
 * @link https://www.webapproach.net/tint.html
=======
 * @link https://webapproach.net/tint.html
>>>>>>> dev
 */
?>
<?php

/**
 * 返回授权失败的HTTP错误码
 *
 * @since 2.0.0
 *
 * @return integer
 */
function tt_rest_authorization_required_code() {
    return is_user_logged_in() ? 403 : 401;
}

/**
 * 返回授权失败的HTTP错误码
 *
 * @since 2.0.0
 *
 * @return integer
 */
function tt_rest_resource_not_found_code() {
    // @see http://stackoverflow.com/questions/11746894/what-is-the-proper-rest-response-code-for-a-valid-request-but-an-empty-data
    // Maybe 204
    return 404;
}


/**
 * 在REST API根索引添加自定义链接
 * @param WP_REST_Response $response
 * @return WP_REST_Response
 */
function tt_add_rest_index_link($response) {
<<<<<<< HEAD
    $response->add_link( 'theme', 'https://www.webapproach.net/tint.html' );
=======
    $response->add_link( 'theme', TT_SITE . '/tint.html' );
>>>>>>> dev
    return $response;
}
add_filter('rest_index', 'tt_add_rest_index_link');


/**
 * Registers default REST API routes.
 *
 * @since 4.4.0
 */
function tt_create_initial_rest_routes() {

    foreach ( get_post_types( array( 'show_in_rest' => true ), 'objects' ) as $post_type ) {
        $class = ! empty( $post_type->rest_controller_class ) ? $post_type->rest_controller_class : 'WP_REST_Posts_Controller';

        if ( ! class_exists( $class ) ) {
            continue;
        }
        $controller = new $class( $post_type->name );
        if ( ! is_subclass_of( $controller, 'WP_REST_Controller' ) ) {
            continue;
        }

        $controller->register_routes();

//        if ( post_type_supports( $post_type->name, 'revisions' ) ) {
//            $revisions_controller = new WP_REST_Revisions_Controller( $post_type->name );
//            $revisions_controller->register_routes();
//        }
    }

    // Post types.
//    $controller = new WP_REST_Post_Types_Controller;  // TODO controller
//    $controller->register_routes();

    // Post statuses.
//    $controller = new WP_REST_Post_Statuses_Controller;
//    $controller->register_routes();

    // Taxonomies.
//    $controller = new WP_REST_Taxonomies_Controller;
//    $controller->register_routes();

    // Terms.
//    foreach ( get_taxonomies( array( 'show_in_rest' => true ), 'object' ) as $taxonomy ) {
//        $class = ! empty( $taxonomy->rest_controller_class ) ? $taxonomy->rest_controller_class : 'WP_REST_Terms_Controller';
//
//        if ( ! class_exists( $class ) ) {
//            continue;
//        }
//        $controller = new $class( $taxonomy->name );
//        if ( ! is_subclass_of( $controller, 'WP_REST_Controller' ) ) {
//            continue;
//        }
//
//        $controller->register_routes();
//    }

    // Users.
    $user_controller = new WP_REST_User_Controller;
    $user_controller->register_routes();

    // Session
    $session_controller = new WP_REST_Session_Controller;
    $session_controller->register_routes();

    // Comments.
    $comment_controller = new WP_REST_Comment_Controller;
    $comment_controller->register_routes();

    // Comment Stars
    $comment_star_controller = new WP_REST_Comment_Star_Controller;
    $comment_star_controller->register_routes();

    // Post Stars
    $post_star_controller = new WP_REST_Post_Star_Controller;
    $post_star_controller->register_routes();

    // Follow
    $follow_controller = new WP_REST_User_Follow_Controller;
    $follow_controller->register_routes();

    // Messages
    $message_controller = new WP_REST_Message_Controller;
    $message_controller->register_routes();

    // User Status
    $user_status_controller = new WP_REST_User_Status_Controller;
    $user_status_controller->register_routes();

    // User meta
    $user_meta_controller = new WP_REST_Usermeta_Controller;
    $user_meta_controller->register_routes();

    // Shopping cart
    $shop_cart_controller = new WP_REST_ShoppingCart_Controller;
    $shop_cart_controller->register_routes();

    // Order
    $order_controller = new WP_REST_Order_Controller;
    $order_controller->register_routes();

    // Buy post resource
    $post_resource_buy_controller = new WP_REST_BoughtResource_Controller;
    $post_resource_buy_controller->register_routes();

    // User profile
    $user_profile_controller = new WP_REST_User_Profile_Controller;
    $user_profile_controller->register_routes();

    // Other actions
    $other_action_controller = new WP_REST_Action_Controller;
    $other_action_controller->register_routes();

    // TT post
    $tt_post_controller = new WP_REST_Post_Controller;
    $tt_post_controller->register_routes();
<<<<<<< HEAD
=======

    // Product
    $product_controller = new WP_REST_Product_Controller;
    $product_controller->register_routes();

    // Coupon
    $coupon_controller = new WP_REST_Coupon_Controller;
    $coupon_controller->register_routes();

    // Member
    $member_controller = new WP_REST_Member_Controller;
    $member_controller->register_routes();
>>>>>>> dev
}
add_action( 'rest_api_init', 'tt_create_initial_rest_routes', 0 );  // TODO cached 接口


/**
 * REST请求时设置DOING_AJAX为true
 *
 * @since 2.0.0
<<<<<<< HEAD
 * @return void
 */
function tt_rest_set_doing_ajax () {
=======
 * @param $access
 * @return bool
 */
function tt_rest_set_doing_ajax ($access) {
>>>>>>> dev
    if(!defined('DOING_AJAX')) {
        define('DOING_AJAX', true);
    }

<<<<<<< HEAD
    return true;
}
add_filter('rest_enabled', 'tt_rest_set_doing_ajax');
=======
    return $access;
}
add_filter('rest_authentication_errors', 'tt_rest_set_doing_ajax');
>>>>>>> dev


/**
 * REST请求的缓存键
 *
 * @since   2.0.0
 *
 * @param   WP_REST_Request  $request
 * @return  string
 */
function tt_get_rest_request_cache_key($request) {
    //$request_uri = esc_url( $_SERVER['REQUEST_URI'] );

    $prefix = 'tt_cache_rest_';
    $user_id = 0;

    $method = $request->get_method();
    $path = $request->get_route();
    $params = $request->get_params();
    $params_str = json_encode($params);
    if(isset($params['user_diff']) && $params['user_diff']){  // TODO: user_diff (表示该接口的值是用户相关的) // TODO no_cache 参数包含在请求中以阻止缓存
        $user_id = get_current_user_id();
    }

    $cache_key = $prefix . md5(implode('_', array($method, $path, $params_str, $user_id)));

    return $cache_key;
}

/**
 * 接口缓存 - GET
 *
 * @since   2.0.0
 *
 * @param   mixed   $result
 * @param   WP_REST_Server  $server
 * @param   WP_REST_Request $request
 * @return  WP_REST_Response | false
 */
function tt_rest_pre_dispatch_cache($result, $server, $request) {

    // 部分接口不缓存，如登录接口 //TODO more
    // POST请求不缓存|DEBUG模式不緩存
<<<<<<< HEAD
    if($request->get_method() == 'POST' || in_array($request->get_route(), ['/v1/session']) || tt_get_option('tt_theme_debug', false)) {
=======
    if($request->get_method() == 'POST' || in_array($request->get_route(), array('/v1/session')) || tt_get_option('tt_theme_debug', false)) {
>>>>>>> dev
        return false;
    }

    // 更改headers的filter
    // add_filter( 'tt_rest_cache_headers', function( $headers ) {
    // $headers['Cache-Control'] = 'public, max-age=3600';

    //     return $headers;
    // } );
    $headers = apply_filters( 'tt_rest_cache_headers', array(), $server, $request );  //TODO `tt_rest_cache_headers` filter
    if ( !empty( $headers ) ) {
        $server->send_headers( $headers );
    }

    $cache_key = tt_get_rest_request_cache_key($request);

    if($result = get_transient($cache_key)){
        $result = maybe_unserialize($result);

        return rest_ensure_response($result);
    }

    return false;

}
add_filter('rest_pre_dispatch', 'tt_rest_pre_dispatch_cache', 10, 3);


/**
 * 接口缓存 - SET
 *
 * @since   2.0.0
 *
 * @param   bool    $dispatch_result
 * @param   WP_REST_Request     $request
 * @param   string      $route
 * @param   array     $handler
 * @return  WP_REST_Response
 */
function tt_rest_dispatch_request_cache($dispatch_result, $request, $route, $handler) {
    // 处理request
    $callback = $handler['callback'];
    $response = call_user_func( $callback, $request );

    // 设置cache
    $cache_key = tt_get_rest_request_cache_key($request);
    $expiration = isset($handler['cache_expiration']) ? (int)$handler['cache_expiration'] : 60*60*24; // TODO: cache_expiration
    set_transient($cache_key, $response, $expiration);


    return $response;
}
add_filter( 'rest_dispatch_request', 'tt_rest_dispatch_request_cache', 10, 4 );


/**
 * 输出成功JSON响应
 *
 * @since 2.0.0
 * @param string $message
 * @param array|object $data
 * @param string $status
 * @param string $header_location
 * @return WP_REST_Response
 */
function tt_api_success($message, $data = array(), $status = "200", $header_location = "") {
    $response = array_merge(array(
        'success' => true,
        'message' => $message
    ), (array)$data);
    //echo json_encode($response);
    //exit();
    $response = rest_ensure_response($response);
    $response->set_status( $status );
    if($status=='301' || $status=='302') {
        $response->header( 'Location', $header_location );
    }

    return $response;
}

/**
 * 输出失败JSON响应
 *
 * @since 2.0.0
 * @param string $message
 * @param array|object $data
 * @param string $status
 * @param string $header_location
 * @return WP_REST_Response
 */
function tt_api_fail($message, $data = array(), $status = "404", $header_location = "") {
    $response = array_merge(array(
        'success' => false,
        'message' => $message
    ), (array)$data);
    //echo json_encode($response);
    //exit();
    $response = rest_ensure_response($response);
    $response->set_status( $status );
    if($status=='301' || $status=='302') {
        $response->header( 'Location', $header_location );
    }

    return $response;
}
