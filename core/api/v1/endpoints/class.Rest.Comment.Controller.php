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
 * @link https://webapproach.net/tint.html
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
                'methods'         => WP_REST_Server::READABLE,
                'callback'        => array( $this, 'get_items' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
                'args'            => $this->get_collection_params(),
            ),
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_item'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
            ),
            'schema' => array($this, 'get_public_item_schema'),
        ));

        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<comment_id>[\d]+)', array(
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
        ));
    }


    /**
     * 判断是否有权限读取评论
     *
     * @param WP_REST_Request $request
     * @return boolean | WP_Error
     */
    function get_items_permissions_check ( $request ) {
        return true;
    }

    /**
     * 读取评论
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error | WP_REST_Response
     */
    public function get_items( $request ) {
        $comment_page = max(absint($request->get_param('commentPage')), 2);
        $comments_per_page = tt_get_option('tt_comments_per_page', 20);
        $comment_post_ID = absint($request->get_param('commentPostId'));
        $post_type = get_post_type($comment_post_ID);
        if(!$comment_post_ID) {
            return tt_api_fail(__('Invalid post ID', 'tt'));
        }

        $nonce = $request->get_param('_wpnonce');

        if(!wp_verify_nonce($nonce, 'wp_rest')) {
            return tt_api_fail(__('Nonce verify failed', 'tt'), array(), '400');
        }

        $the_comments = get_comments(array(
            'status' => 'approve',
            'type' => 'comment', // 'pings' (includes 'pingback' and 'trackback'),
            'post_id'=> $comment_post_ID,
            //'meta_key' => 'tt_sticky_comment',
            'orderby' => 'comment_date', //meta_value_num
            'order' => 'DESC',
            'number' => $comments_per_page,
            'offset' => $comments_per_page * ($comment_page-1)
        ));

        $count = count(array($the_comments));
        if(!$the_comments || $count == 0) {
            return tt_api_fail(__('No more comments', 'tt'), array('count' => 0), '200');
        }

        $comment_list = wp_list_comments(array(
            'type'=>'all',
            'callback'=>$post_type=='product' ? 'tt_shop_comment' : 'tt_comment',
            'end-callback'=>'tt_end_comment',
            'max_depth'=>3,
            'reverse_top_level'=>0,
            'style'=>'div',
            'page'=>1,
            'per_page'=>$comments_per_page,
            'echo'=>false
        ), $the_comments);

        return tt_api_success($comment_list, array('count' => $count, 'nextPage' => $comment_page + 1));
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
            return tt_api_fail(__('Spam nonce check failed', 'tt'));
        }
        $comment_post_ID = absint($_POST['postId']);
        $user = wp_get_current_user();

        $post_type = get_post_type($comment_post_ID);
        $product_rating = absint($request->get_param('productRating'));
        if($post_type=='product' && $product_rating) {
            // 商品对仅购买成功的用户方可评论
            if(!tt_check_user_has_buy_product($comment_post_ID, $user->ID)){
                return tt_api_fail(__('You cannot comment this product becasue you donot buy it', 'tt'));
            }
        }

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

        // 检测重复评论 (由wp_new_comment内部机制去完成)
//        global $wpdb;
//        $dupe = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND comment_author = '$user_ID' AND comment_content = '$comment_content' LIMIT 1";
//        if ( $wpdb->get_var($dupe) ) {
//            return tt_api_fail(__('Duplicated comment', 'tt'), array(), "409");
//        }

        // 检测评论频率 (由wp_new_comment内部机制去完成)
//        if ( $last_time = $wpdb->get_var( $wpdb->prepare("SELECT comment_date_gmt FROM $wpdb->comments WHERE comment_author = %s ORDER BY comment_date DESC LIMIT 1", $comment_author) ) ) {
//            $time_last_comment = mysql2date('U', $last_time, false);
//            $time_new_comment  = mysql2date('U', current_time('mysql', 1), false);
//            $flood_die = apply_filters('comment_flood_filter', false, $time_last_comment, $time_new_comment);
//            if ( $flood_die ) {
//                return tt_api_fail(__('Comment too fast', 'tt'));
//            }
//        }

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

        // add comment meta(rating for product)
        if($post_type=='product' && $product_rating) {
            update_comment_meta($comment_id, 'tt_rating_product', $product_rating);
            $product_ratings_raw = get_post_meta($comment_post_ID, 'tt_post_ratings', true);
            $product_ratings = $product_ratings_raw ? (array)maybe_unserialize($product_ratings_raw) : array();
            $product_ratings[] = $product_rating;
            update_post_meta($comment_post_ID, 'tt_post_ratings', maybe_serialize($product_ratings));
            update_post_meta($comment_post_ID, 'tt_latest_rated', time());
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
        ), array($comment));

        return tt_api_success($comment_html);

    }


    /**
     * 判断当前请求是否有权限删除评论
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean | WP_Error
     */
    public function delete_item_permissions_check( $request ) {

        if ( !is_user_logged_in() || !current_user_can('Administrator') ) {
            return new WP_Error( 'rest_comment_cannot_delete', __( 'Sorry, you are not allowed to delete this resource.', 'tt' ), array( 'status' => tt_rest_authorization_required_code() ) );
        }

        return true;
    }

    /**
     * 删除评论
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function delete_item( $request ) {

    }
}
