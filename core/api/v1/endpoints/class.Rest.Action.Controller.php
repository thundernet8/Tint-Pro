<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/22 22:34
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class WP_REST_Action_Controller
 */
class WP_REST_Action_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'v1';
        $this->rest_base = 'actions';
    }

    /**
     * 注册路由
     */
    public function register_routes(){
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<action>[\S]+)', array(
            array(
                'methods'         => WP_REST_Server::EDITABLE,
                'callback'        => array( $this, 'exec_action' ),
                'permission_callback' => array( $this, 'exec_action_permissions_check' ),
                'args'            => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
            ),
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

    }


    /**
     * 判断当前请求是否有权限执行Action
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function exec_action_permissions_check( $request ) {
        $action = $request['action'];
        $allow_actions_kv = (array)json_decode(ALLOWED_ACTIONS);
        $allow_actions = array_keys($allow_actions_kv);
        if(!in_array($action, $allow_actions)) {
            return new WP_Error('rest_action_invalid', __('Sorry, the action is invalid.', 'tt'), array('status' => 404));
        }
        if ($allow_actions_kv[$action] == 1 && !is_user_logged_in()) {
            return new WP_Error('rest_action_cannot_execute', __('Sorry, you cannot execute the action without signing in.', 'tt'), array('status' => tt_rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * Action
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function exec_action( $request ) {
        $action = $request['action'];

        return tt_exec_api_actions($action);
    }

}