<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/30 22:07
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php

/**
 * 获取用户的积分
 *
 * @since 2.0.0
 * @param int $user_id
 * @return int
 */
function tt_get_user_credit($user_id = 0){
    $user_id = $user_id ? : get_current_user_id();
    return (int)get_user_meta($user_id, 'tt_credits', true);
}


/**
 * 获取用户已经消费的积分
 *
 * @since 2.0.0
 * @param $user_id
 * @return int
 */
function tt_get_user_consumed_credit($user_id = 0){
    $user_id = $user_id ? : get_current_user_id();
    return (int)get_user_meta($user_id, 'tt_consumed_credits', true);
}


/**
 * 更新用户积分(添加积分或消费积分)
 *
 * @since 2.0.0
 * @param int $user_id
 * @param int $amount
 * @param string $msg
 * @param bool $admin_handle
 * @return bool
 */
function tt_update_user_credit($user_id = 0, $amount = 0, $msg = '', $admin_handle = false){
    $user_id = $user_id ? : get_current_user_id();
    $before_credits = (int)get_user_meta($user_id, 'tt_credits', true);
    // 管理员直接更改用户积分
    if($admin_handle){
        $update = update_user_meta($user_id, 'tt_credits', min(0, (int)$amount));
        if($update){
            // 添加积分消息
            $msg = $msg ? : sprintf(__('Administrator change your credits to %d', 'tt') , $amount);
            tt_create_message( $user_id, 0, 'System', 'credit', $msg, '', 0, 'publish');
        }
        return !!$update;
    }
    // 普通更新
    if($amount > 0){
        $update = update_user_meta($user_id, 'tt_credits', $before_credits + $amount); //Meta ID if the key didn't exist; true on successful update; false on failure or if $meta_value is the same as the existing meta value in the database.
        if($update){
            // 添加积分消息
            $msg = $msg ? : sprintf(__('Gain %d credits', 'tt') , $amount);
            tt_create_message( $user_id, 0, 'System', 'credit', $msg, '', 0, 'publish');
        }
    }elseif($amount < 0){
        if($before_credits + $amount < 0){
            return false;
        }
        $before_consumed = (int)get_user_meta($user_id, 'tt_consumed_credits', true);
        update_user_meta($user_id, 'tt_consumed_credits', $before_consumed - $amount);
        $update = update_user_meta($user_id, 'tt_credits', $before_credits + $amount);
        if($update){
            // 添加积分消息
            $msg = $msg ? : sprintf(__('Spend %d credits', 'tt') , absint($amount));
            tt_create_message( $user_id, 0, 'System', 'credit', $msg, '', 0, 'publish');
        }
    }
    return true;
}


/**
 * 使用积分支付
 *
 * @since 2.0.0
 * @param int $amount
 * @param bool $rest
 * @return bool|WP_Error
 */
function tt_credit_pay($amount = 0, $rest = false) {
    $amount = absint($amount);
    $user_id = get_current_user_id();
    if(!$user_id) {
        return $rest ? new WP_Error('unknown_user', __('You must sign in before payment', 'tt')) : false;
    }

    $credits = (int)get_user_meta($user_id, 'tt_credits', true);
    if($credits < $amount) {
        return $rest ? new WP_Error('insufficient_credits', __('You do not have enough credits to accomplish this payment', 'tt')) : false;
    }

//    $new_credits = $credits - $amount;
//    $update = update_user_meta($user_id, 'tt_credits', $new_credits);
//    if($update) {
//        $consumed = (int)get_user_meta($user_id, 'tt_consumed_credits', true);
//        update_user_meta($user_id, 'tt_consumed_credits', $consumed + $amount);
//        // 添加积分消息
//        $msg = sprintf(__('Spend %d credits', 'tt') , absint($amount));
//        tt_create_message( $user_id, 0, 'System', 'credit', $msg, '', 0, 'publish');
//    }

    tt_update_user_credit($user_id, $amount*(-1)); //TODO confirm update
    return true;
}


/**
 * 用户注册时添加推广人和奖励积分
 *
 * @since 2.0.0
 * @param $user_id
 * @return void
 */
function tt_update_credit_by_user_register( $user_id ) {
    if( isset($_COOKIE['tt_ref']) && is_numeric($_COOKIE['tt_ref']) ){
        $ref_from = absint($_COOKIE['tt_ref']);
        //链接推广人与新注册用户(推广人meta)
        if(get_user_meta( $ref_from, 'tt_ref_users', true)){
            $ref_users = get_user_meta( $ref_from, 'tt_ref_users', true);
            if(empty($ref_users)){
                $ref_users = $user_id;
            }else{
                $ref_users .= ',' . $user_id;}
            update_user_meta( $ref_from, 'tt_ref_users', $ref_users);
        }else{
            update_user_meta( $ref_from, 'tt_ref_users', $user_id);
        }
        //链接推广人与新注册用户(注册人meta)
        update_user_meta( $user_id, 'tt_ref', $ref_from );
        $rec_reg_num = (int)tt_get_option('tt_rec_reg_num','5');
        $rec_reg = json_decode(get_user_meta( $ref_from, 'tt_rec_reg', true ));
        $ua = $_SERVER["REMOTE_ADDR"] . '&' . $_SERVER["HTTP_USER_AGENT"];
        if(!$rec_reg){
            $rec_reg = array();
            $new_rec_reg = array($ua);
        }else{
            $new_rec_reg = $rec_reg;
            array_push($new_rec_reg , $ua);
        }
        if( (count($rec_reg) < $rec_reg_num) &&  !in_array($ua,$rec_reg) ){
            update_user_meta( $ref_from , 'tt_rec_reg' , json_encode( $new_rec_reg ) );

            $reg_credit = (int)tt_get_option('tt_rec_reg_credit', '30');
            if($reg_credit){
                tt_update_user_credit($ref_from, $reg_credit, sprintf(__('获得注册推广（来自%1$s的注册）奖励%2$s积分', 'tt') , get_the_author_meta('display_name', $user_id), $reg_credit));
            }
        }
    }
    $credit = tt_get_option('tt_reg_credit', 50);
    if($credit){
        tt_update_user_credit($user_id, $credit, sprintf(__('获得注册奖励%s积分', 'tt') , $credit));
    }
}
add_action( 'user_register', 'tt_update_credit_by_user_register');


function tt_update_credit_by_referral_view(){
    if( isset($_COOKIE['tt_ref']) && is_numeric($_COOKIE['tt_ref']) ){
        $ref_from = absint($_COOKIE['tt_ref']);
        $rec_view_num = (int)tt_get_option('tt_rec_view_num', '50');
        $rec_view = json_decode(get_user_meta( $ref_from, 'tt_rec_view', true ));
        $ua = $_SERVER["REMOTE_ADDR"] . '&' . $_SERVER["HTTP_USER_AGENT"];
        if(!$rec_view){
            $rec_view = array();
            $new_rec_view = array($ua);
        }else{
            $new_rec_view = $rec_view;
            array_push($new_rec_view , $ua);
        }
        //推广人推广访问数量，不受每日有效获得积分推广次数限制，但限制同IP且同终端刷分
        if( !in_array($ua, $rec_view) ){
            $ref_views = (int)get_user_meta( $ref_from, 'tt_aff_views', true);
            $ref_views++;
            update_user_meta( $ref_from, 'tt_aff_views', $ref_views);
        }
        //推广奖励，受每日有效获得积分推广次数限制及同IP终端限制刷分
        if( (count($rec_view) < $rec_view_num) && !in_array($ua, $rec_view) ){
            update_user_meta( $ref_from , 'tt_rec_view' , json_encode( $new_rec_view ) );
            $view_credit = (int)tt_get_option('tt_rec_view_credit','5');
            if($view_credit){
                tt_update_user_credit($ref_from, $view_credit, sprintf(__('获得访问推广奖励%d积分', 'tt') , $view_credit));
            }
        }
    }
}
add_action( 'tt_ref', 'tt_update_credit_by_referral_view');


/**
 * 发表评论时给作者添加积分
 *
 * @since 2.0.0
 * @param $comment_id
 * @param $comment_object
 */
function tt_comment_add_credit($comment_id, $comment_object){

    $user_id = $comment_object->user_id;
    if($user_id){
        $rec_comment_num = (int)tt_get_option('tt_rec_comment_num', 10);
        $rec_comment_credit = (int)tt_get_option('tt_rec_comment_credit', 5);
        $rec_comment = (int)get_user_meta( $user_id, 'tt_rec_comment', true );

        if( $rec_comment<$rec_comment_num && $rec_comment_credit ){
            tt_update_user_credit($user_id, $rec_comment_credit, sprintf(__('获得评论回复奖励%d积分', 'tt') , $rec_comment_credit));
            update_user_meta( $user_id, 'tt_rec_comment', $rec_comment+1);
        }
    }
}
add_action('wp_insert_comment', 'tt_comment_add_credit', 99, 2 );


/**
 * 每天 00:00 清空推广数据
 *
 * @since 2.0.0
 * @return void
 */
function tt_clear_rec_setup_schedule() {
    if ( ! wp_next_scheduled( 'tt_clear_rec_daily_event' ) ) {
        //~ 1193875200 是 2007/11/01 00:00 的时间戳
        wp_schedule_event( '1193875200', 'daily', 'tt_clear_rec_daily_event');
    }
}
add_action( 'wp', 'tt_clear_rec_setup_schedule' );

function tt_do_clear_rec_daily() {
    global $wpdb;
    $wpdb->query( " DELETE FROM $wpdb->usermeta WHERE meta_key='tt_rec_view' OR meta_key='tt_rec_reg' OR meta_key='tt_rec_post' OR meta_key='tt_rec_comment' OR meta_key='tt_resource_dl_users' " ); // TODO tt_resource_dl_users
}
add_action( 'tt_clear_rec_daily_event', 'tt_do_clear_rec_daily' );


/**
 * 在后台用户列表中显示积分
 *
 * @since 2.0.0
 * @param $columns
 * @return mixed
 */
function tt_credit_column( $columns ) {
    $columns['tt_credit'] = __('Credit','tt');
    return $columns;
}
add_filter( 'manage_users_columns', 'tt_credit_column' );

function tt_credit_column_callback( $value, $column_name, $user_id ) {

    if( 'tt_credit' == $column_name ){
        $credit = intval(get_user_meta($user_id, 'tt_credits', true));
        $void = intval(get_user_meta($user_id, 'tt_consumed_credits', true));
        $value = sprintf(__('总积分 %1$d 已消费 %2$d 剩余 %3$d','tinection'), $credit+$void, $void, $credit );
    }
    return $value;
}
add_action( 'manage_users_custom_column', 'tt_credit_column_callback', 10, 3 );


/**
 * 按积分排序获取用户排行
 *
 * @since 2.0.0
 * @param int $limits
 * @param int $offset
 * @return array|null|object
 */
function tt_credits_rank($limits=10, $offset = 0){
    global $wpdb;
    $limits = (int)$limits;
    $offset = absint($offset);
    $ranks = $wpdb->get_results( " SELECT * FROM $wpdb->usermeta WHERE meta_key='tt_credits' ORDER BY -meta_value ASC LIMIT $limits OFFSET $offset" );
    return $ranks;
}


/**
 * 创建积分充值订单
 *
 * @since 2.0.0
 * @param $user_id
 * @param int $amount // 积分数量为100*$amount
 * @return array|bool
 */
function tt_create_credit_charge_order($user_id, $amount = 1){
    $amount = absint($amount);
    if(!$amount){
        return false;
    }
    $order_id = tt_generate_order_num();
    $order_time = current_time('mysql');
    $product_id = Product::CREDIT_CHARGE;
    $product_name = Product::CREDIT_CHARGE_NAME;
    $currency = 'cash';
    $hundred_credits_price = intval(tt_get_option('tt_hundred_credit_price', 1));
    $order_price = sprintf('%0.2f', $hundred_credits_price/100);
    $order_quantity = $amount * 100;
    $order_total_price = sprintf('%0.2f', $hundred_credits_price * $amount);

    global $wpdb;
    $prefix = $wpdb->prefix;
    $orders_table = $prefix . 'tt_orders';
    $insert = $wpdb->insert(
        $orders_table,
        array(
            'parent_id' => 0,
            'order_id' => $order_id,
            'product_id' => $product_id,
            'product_name' => $product_name,
            'order_time' => $order_time,
            'order_price' => $order_price,
            'order_currency' => $currency,
            'order_quantity' => $order_quantity,
            'order_total_price' => $order_total_price,
            'user_id' => $user_id
        ),
        array('%d', '%s', '%d', '%s', '%s', '%f', '%s', '%d', '%f', '%d')
    );
    if($insert) {
        return array(
            'insert_id' => $wpdb->insert_id,
            'order_id' => $order_id,
            'total_price' => $order_total_price
        );
    }
    return false;
}