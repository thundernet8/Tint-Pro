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
 * @link https://www.webapproach.net/tint.html
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
    $response->add_link( 'theme', 'https://www.webapproach.net/tint.html' );
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
    $controller = new WP_REST_User_Controller;
    $controller->register_routes();

    // Comments.
//    $controller = new WP_REST_Comments_Controller;
//    $controller->register_routes();
}
add_action( 'rest_api_init', 'tt_create_initial_rest_routes', 0 );  // TODO cached 接口


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

    $prefix = 'tt_rest_cache_';
    $user_id = 0;

    $method = $request->get_method();
    $path = $request->get_route();
    $params = $request->get_params();
    $params_str = json_encode($params);
    if(isset($params['user_diff']) && $params['user_diff']){  // TODO: user_diff
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

        return new WP_REST_Response($result); // WP_REST_Response  - __construct( $data = null, $status = 200, $headers = array() )
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
