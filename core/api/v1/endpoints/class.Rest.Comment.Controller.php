<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/12 21:38
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/**
 * Class WP_REST_Comment_Controller
 */
class WP_REST_Comment_Controller extends WP_REST_Controller
{

    public function __construct()
    {
        $this->namespace = 'v1';
        $this->rest_base = 'comments';
    }

    /**
     * 注册路由
     */
    public function register_routes(){
        register_rest_route($this->namespace, '/' . $this->rest_base, array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_item'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
            ),
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'delete_item'),
                'permission_callback' => array($this, 'delete_item_permissions_check'),
                'args' => array(
                    'force' => array(
                        'default' => false,
                        'description' => __('Required to be true, as resource does not support trashing.'),
                    ),
                    'reassign' => array(),
                ),
            ),
            'schema' => array($this, 'get_public_item_schema'),
        ));
    }


    /**
     * 判断请求是否有权限发表评论
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function create_item_permissions_check( $request ) {
        return true;
    }

    /**
     * 发表评论
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error | WP_REST_Response
     */
    public function create_item( $request ) {
        $nonce = trim($_POST['commentNonce']);
        if(!wp_verify_nonce($nonce, 'tt_comment_nonce')) {
            return tt_api_fail(__('Span nonce check failed', 'tt'));
        }
        $comment_post_ID = absint($_POST['postId']);
        if($comment_post_ID <= 0 || !comments_open($comment_post_ID)) {
            return tt_api_fail(__('Comment closed for the post', 'tt'));
        }
        if(get_post_status($comment_post_ID) != 'publish') {
            return tt_api_fail(__('Cannot comment a unpublished post', 'tt'));
        }
        if(post_password_required($comment_post_ID)) {
            return tt_api_fail(__('Cannot comment a password protected post', 'tt'));
        }
        do_action('pre_comment_on_post', $comment_post_ID);

        $comment_content = trim( $_POST['content'] );
        $comment_type = isset( $_POST['commentType'] ) ? trim( $_POST['commentType'] ) : '';
        $ksesNonce = trim( $_POST['ksesNonce'] );
        $comment_parent = absint($_POST['parentId']);

        $user_ID = 0;
        $comment_author = '';
        $comment_author_email = '';
        $comment_author_url = '';

        $user = wp_get_current_user();
        if ( $user->ID ) { // $user->exists()
            $user_ID = $user->ID;
            $comment_author = wp_slash( $user->display_name );
            $comment_author_email = wp_slash( $user->user_email );
            $comment_author_url = wp_slash( $user->user_url );
            if ( current_user_can( 'unfiltered_html' ) ) {
                if ( ! isset( $_POST['_wp_unfiltered_html_comment'] ) )
                    $_POST['_wp_unfiltered_html_comment'] = '';

                if ( wp_create_nonce( 'unfiltered-html-comment' ) != $ksesNonce ) {
                    kses_remove_filters(); // start with a clean slate
                    kses_init_filters(); // set up the filters
                }
            }
        } else {
            return tt_api_fail(__('Sorry, you must be logged in to reply to a comment.', 'tt'), array(), tt_rest_authorization_required_code());
        }

        //$user_login = sanitize_text_field($request->get_param('user_login'));
        if($comment_content == '') { // TODO 更多内容验证
            return tt_api_fail(__('Comment cannot be blank', 'tt'));
        }

        // 检测重复评论
        global $wpdb;
        $dupe = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND comment_author = '$user_ID' AND comment_content = '$comment_content' LIMIT 1";
        if ( $wpdb->get_var($dupe) ) {
            return tt_api_fail(__('Duplicated comment', 'tt'));
        }

        // 检测评论频率
        if ( $last_time = $wpdb->get_var( $wpdb->prepare("SELECT comment_date_gmt FROM $wpdb->comments WHERE comment_author = %s ORDER BY comment_date DESC LIMIT 1", $comment_author) ) ) {
            $time_last_comment = mysql2date('U', $last_time, false);
            $time_new_comment  = mysql2date('U', current_time('mysql', 1), false);
            $flood_die = apply_filters('comment_flood_filter', false, $time_last_comment, $time_new_comment);
            if ( $flood_die ) {
                return tt_api_fail(__('Comment too fast', 'tt'));
            }
        }

        // 评论数据合并
        // -comment_approved
        $commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');

        $edit_id = isset($_POST['editId']) ? absint($_POST['editId']) : 0; // TODO edit id in comments.js
        if ( $edit_id ){
            $comment_id = $commentdata['comment_ID'] = $edit_id;
            wp_update_comment( $commentdata );
        } else {
            $comment_id = wp_new_comment( $commentdata ); //wp_insert_comment
        }

        if(!$comment_id) {
            return tt_api_fail(__('Add comment failed', 'tt'));
        }

        $comment = get_comment($comment_id);
        $comment_html = wp_list_comments(array(
            'type'=>'all',
            'callback'=>'tt_comment',
            'end-callback'=>'tt_end_comment',
            'max_depth'=>3,
            'reverse_top_level'=>0,
            'style'=>'div',
            'page'=>1,
            'per_page'=>1,
            'echo'=>false
        ), [$comment]);

        return tt_api_success($comment_html);

    }


    /**
     * 判断当前请求是否有权限删除评论
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function delete_item_permissions_check( $request ) {

        if ( !is_user_logged_in() ) {
            return new WP_Error( 'rest_session_cannot_delete', __( 'Sorry, you are not allowed to delete this resource.', 'tt' ), array( 'status' => tt_rest_authorization_required_code() ) );
        }

        return true;
    }

    /**
     * 删除Session(登出)
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function delete_item( $request ) {
        $force = isset( $request['force'] ) ? (bool) $request['force'] : false;

        // We don't support trashing for this type, error out
        if ( ! $force ) {
            return new WP_Error( 'rest_trash_not_supported', __( 'Users do not support trashing.', 'tt' ), array( 'status' => 501 ) );
        }

        $user = wp_get_current_user();
        if ( ! $user ) {
            return new WP_Error( 'rest_user_invalid_id', __( 'Invalid resource id.', 'tt' ), array( 'status' => 400 ) );
        }

        wp_destroy_current_session();
        wp_clear_auth_cookie();

        return rest_ensure_response(array(
            'success' => 1,
            'message' => __('You have signed out successfully', 'tt')
        ));
    }
}
