<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/31 20:11
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/**
 * 创建消息
 *
 *
 * @since 2.0.0
 * @param int $user_id  接收用户ID
 * @param int $sender_id  发送者ID(可空)
 * @param string $sender   发送者
 * @param string $type  消息类型(notification/chat/credit)
 * @param string $title 消息标题
 * @param string $content 消息内容
 * @param int $read (已读/未读)
 * @param string $status  消息状态(publish/trash)
 * @param string $date  消息时间
 * @return bool
 */
function tt_create_message( $user_id=0, $sender_id=0, $sender, $type='', $title='', $content='', $read=0, $status='publish', $date='' ){

    $user_id = absint($user_id);
    $sender_id = absint($sender_id);
    $title = sanitize_text_field($title);

    if( !$user_id || empty($title) ) return false;

    $type = $type ? sanitize_text_field($type) : 'chat';
    $date = $date ? $date : current_time('mysql');
    $content = htmlspecialchars($content);

    global $wpdb;
    $table_name = $wpdb->prefix . 'tt_messages';

    if($wpdb->query( $wpdb->prepare("INSERT INTO $table_name (user_id, sender_id, sender, msg_type, msg_title, msg_content, msg_read, msg_status, msg_date) VALUES (%d, %d, %s, %s, %s, %s, %d, %s, %s)", $user_id, $sender_id, $sender, $type, $title, $content, $read, $status, $date )) )
        return true;
    return false;
}


/**
 * 创建一条私信
 *
 * @param $receiver_id
 * @param $sender
 * @param $text
 * @param $send_mail
 * @return bool
 */
function tt_create_pm($receiver_id, $sender, $text, $send_mail = false) {
    if($sender instanceof WP_User && $sender->ID) {
        if($send_mail && $sender->user_email) {
            $subject = sprintf( __('%1$s向你发送了一条消息 - %2$s', 'tt'), $sender->display_name, get_bloginfo('name') );
            $args = array(
                'senderName' => $sender->display_name,
                'message' => $text,
                'chatLink' => tt_url_for('uc_chat', $sender)
            );
            tt_async_mail('', get_user_by('id', $receiver_id)->user_email, $subject, $args, 'pm');
        }
        return tt_create_message($receiver_id, $sender->ID, $sender->display_name, 'chat', $text);
    }elseif(is_int($sender)){
        $sender = get_user_by('ID', $sender);
        if($send_mail && $sender->user_email) {
            $subject = sprintf( __('%1$s向你发送了一条消息 - %2$s', 'tt'), $sender->display_name, get_bloginfo('name') );
            $args = array(
                'senderName' => $sender->display_name,
                'message' => $text,
                'chatLink' => tt_url_for('uc_chat', $sender)
            );
            tt_async_mail('', get_user_by('id', $receiver_id)->user_email, $subject, $args, 'pm');
        }
        return tt_create_message($receiver_id, $sender->ID, $sender->display_name, 'chat', $text);
    }
    return false;
}


/**
 * 标记消息阅读状态
 *
 * @since 2.0.0
 * @param $id
 * @param int $read
 * @return bool
 */
function tt_mark_message( $id, $read = 1 ) {
    $id = absint($id);
    $user_id = get_current_user_id(); //确保只能标记自己的消息

    if( ( !$id || !$user_id) ) return false;

    $read = $read == 0 ? : 1;

    global $wpdb;
    $table_name = $wpdb->prefix . 'tt_messages';

    if($wpdb->query( $wpdb->prepare("UPDATE $table_name SET `msg_read` = %d WHERE `msg_id` = %d AND `user_id` = %d", $read, $id, $user_id) )) {
        return true;
    }
    return false;
}


/**
 * 标记所有未读消息已读
 *
 * @since 2.0.0
 * @return bool
 */
function tt_mark_all_message_read( ) {
    $user_id = get_current_user_id();
    if(!$user_id) return false;

    global $wpdb;
    $table_name = $wpdb->prefix . 'tt_messages';

    if($wpdb->query( $wpdb->prepare("UPDATE $table_name SET `msg_read` = 1 WHERE `user_id` = %d AND `msg_read` = 0", $user_id) )) {
        return true;
    }
    return false;
}


/**
 * 获取单条消息
 *
 * @since 2.0.0
 * @param $msg_id
 * @return bool|object
 */
function tt_get_message($msg_id) {
    $user_id = get_current_user_id();
    if(!$user_id) return false; // 用于防止获取其他用户的消息

    global $wpdb;
    $table_name = $wpdb->prefix . 'tt_messages';

    $row = $wpdb->get_row(sprintf("SELECT * FROM $table_name WHERE `msg_id`=%d AND `user_id`=%d OR `sender_id`=%d", $msg_id, $user_id, $user_id));
    if($row) return $row;
    return false;
}


/**
 * 查询消息
 *
 * @since 2.0.0
 * @param string $type (notification/chat/credit)
 * @param int $limit
 * @param int $offset
 * @param int $read
 * @param string $msg_status
 * @param int $sender_id
 * @param bool  $count
 * @return array|bool|null|object|int
 */
function tt_get_messages( $type = 'chat', $limit = 20, $offset = 0, $read = 0, $msg_status = 'publish', $sender_id = 0, $count = false ) {
    $user_id = get_current_user_id();

    if(!$user_id) return false;

    if(is_array($type)) {
        $type = implode(',', $type);
    }
    if(!in_array($read, [0, 1, 'all'])) {
        $read = 0;
    }
    if(!in_array($msg_status, ['publish', 'trash', 'all'])) {
        $msg_status = 'publish';
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'tt_messages';

    $sql = sprintf("SELECT %s FROM $table_name WHERE `user_id`=%d%s AND `msg_type` IN('$type')%s%s ORDER BY (CASE WHEN `msg_read`='all' THEN 1 ELSE 0 END) DESC, `msg_date` DESC%s", $count ? "COUNT(*)" : "*", $user_id, $sender_id ? " AND `sender_id`=$sender_id" : "", $read!='all' ? " AND `msg_read`='$read'" : "", $msg_status!='all' ? " AND `msg_status`='$msg_status'" : "", $count ? "" : " LIMIT $offset, $limit");

    $results = $count ? $wpdb->get_var($sql) : $wpdb->get_results($sql);
    if($results){
        return $results;
    }
    return 0;
}


/**
 * 指定类型消息计数
 *
 * @since 2.0.0
 * @param string $type
 * @param int $read
 * @param string $msg_status
 * @param int $sender_id
 * @return array|bool|int|null|object
 */
function tt_count_messages( $type = 'chat', $read = 0, $msg_status = 'publish', $sender_id = 0) {
    return tt_get_messages($type, 0, 0, $read, $msg_status, $sender_id, true);
}


/**
 * 获取未读消息
 *
 * @since 2.0.0
 * @param string $type
 * @param int $limit
 * @param int $offset
 * @param string $msg_status
 * @return array|bool|int|null|object
 */
function tt_get_unread_messages( $type = 'chat', $limit = 20, $offset = 0, $msg_status = 'publish') {
    return tt_get_messages($type, $limit, $offset, 0, $msg_status);
}


/**
 * 未读消息计数
 *
 * @since 2.0.0
 * @param string $type
 * @param string $msg_status
 * @return array|bool|int|null|object
 */
function tt_count_unread_messages( $type = 'chat', $msg_status = 'publish' ) {
    return tt_count_messages($type, 0, $msg_status);
}


/**
 * 获取积分消息
 *
 *
 * @since 2.0.0
 * @param int $limit
 * @param int $offset
 * @param string $msg_status
 * @return array|bool|int|null|object
 */
function tt_get_credit_messages( $limit = 20, $offset = 0, $msg_status = 'all'){ //TODO: 积分消息不应该有msg_status，不可删除
    return tt_get_messages('credit', $limit, $offset, 'all', $msg_status); //NOTE: 积分消息不分已读未读
}


/**
 * 积分消息计数
 *
 * @since 2.0.0
 * @return array|bool|int|null|object
 */
function tt_count_credit_messages() {
    return tt_count_messages('credit', 'all', 'all');
}


/**
 * 获取聊天消息
 *
 * @since 2.0.0
 * @param $sender_id
 * @param int $limit
 * @param int $offset
 * @param int $read
 * @return array|bool|int|null|object
 */
function tt_get_pm($sender_id, $limit = 20, $offset = 0, $read = 0) {
    return tt_get_messages( 'chat', $limit, $offset, $read, 'publish', $sender_id);
}


/**
 * 获取来自指定发送者的聊天消息(sender_id为0时不指定发送者)
 *
 * @param int $sender_id
 * @param int $read
 * @return array|bool|int|null|object
 */
function tt_count_pm($sender_id = 0, $read = 0) {
    return tt_count_messages('chat', $read, 'publish', $sender_id);
}


/**
 * 删除消息
 *
 * @since 2.0.0
 * @param $msg_id
 * @return bool
 */
function tt_trash_message($msg_id) {
    $user_id = get_current_user_id();
    if(!$user_id) return false;

    global $wpdb;
    $table_name = $wpdb->prefix . 'tt_messages';

    if($wpdb->query( $wpdb->prepare("UPDATE $table_name SET `msg_status` = 'trash' WHERE `msg_id` = %d AND `user_id` = %d", $msg_id, $user_id) ) || $wpdb->query( $wpdb->prepare("UPDATE $table_name SET `msg_status` = 'trash' WHERE `msg_id` = %d AND `sender_id` = %d", $msg_id, $user_id) )) { //TODO optimize
        return true;
    }
    return false;
}


/**
 * 恢复消息
 *
 * @since 2.0.0
 * @param $msg_id
 * @return bool
 */
function tt_restore_message($msg_id) { //NOTE: 应该不用
    $user_id = get_current_user_id();
    if(!$user_id) return false;

    global $wpdb;
    $table_name = $wpdb->prefix . 'tt_messages';

    if($wpdb->query( $wpdb->prepare("UPDATE $table_name SET `msg_status` = 'publish' WHERE `msg_id` = %d AND `user_id` = %d", $msg_id, $user_id) )) {
        return true;
    }
    return false;
}


/**
 * 获取对话(双向消息)
 *
 * @since 2.0.0
 * @param $one_uid
 * @param int $limit
 * @param int $offset
 * @param int $read
 * @param string $msg_status
 * @param bool $count
 * @return array|bool|int|null|object|string
 */
function tt_get_bothway_chat( $one_uid, $limit = 20, $offset = 0, $read = 0, $msg_status = 'publish', $count = false ) {
    $user_id = get_current_user_id();

    if(!$user_id) return false;

    if(!in_array($read, [0, 1, 'all'])) {
        $read = 0;
    }
    if(!in_array($msg_status, ['publish', 'trash', 'all'])) {
        $msg_status = 'publish';
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'tt_messages';
    $concat_id_str = '\'' . $one_uid . '_' . $user_id . '\',' . '\'' . $user_id . '_' . $one_uid . '\'';

    $sql = sprintf("SELECT %s FROM $table_name WHERE CONCAT_WS('_', `user_id`, `sender_id`) IN (%s) AND `msg_type`='chat'%s%s ORDER BY (CASE WHEN `msg_read`='all' THEN 1 ELSE 0 END) DESC, `msg_date` DESC%s", $count ? "COUNT(*)" : "*", $concat_id_str, $read!='all' ? " AND `msg_read`='$read'" : "", $msg_status!='all' ? " AND `msg_status`='$msg_status'" : "", $count ? "" : " LIMIT $offset, $limit");
    $results = $count ? $wpdb->get_var($sql) : $wpdb->get_results($sql);

    if($results){
        return $results;
    }
    return 0;
}