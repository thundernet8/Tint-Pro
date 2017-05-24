<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/10 21:35
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class UCChatVM
 */
class UCChatVM extends BaseVM {

    /**
     * @var int 作者ID
     */
    private  $_authorId = 0;

    /**
     * @var int 当前登录用户ID
     */
    private $_userId = 0;

    /**
     * @var int 分页号
     */
    private $_page = 1;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'hourly';
        $this->_cacheInterval = 60*10; // 缓存保留10分钟
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $page   分页号
     * @param   int    $author_id 作者ID
     * @return  static
     */
    public static function getInstance($page = 1, $author_id = 0) {
        $instance = new static();
        $user_id = get_current_user_id();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_author' . $author_id . '_user' . $user_id . '_page' . $page;
        $instance->_page = max(1, $page);
        $instance->_authorId = absint($author_id);
        $instance->_userId = $user_id;
        $instance->_enableCache = false; // 禁用缓存
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $per_page = 20;
        $offset = $per_page * ($this->_page - 1);

        $messages = tt_get_bothway_chat( $this->_authorId, $per_page, $offset, MsgReadStatus::ALL, 'publish', false );
        $messages_count = tt_get_bothway_chat( $this->_authorId, $per_page, $offset, MsgReadStatus::ALL, 'publish', true );
        $max_num_pages = ceil($messages_count / $per_page);

        $unread_count = tt_count_pm($this->_authorId, MsgReadStatus::UNREAD); //Note: 自己发送的消息一定为已读 //tt_get_bothway_chat( $this->_authorId, $per_page, $offset, 0, 'publish', true );

        $pagination = array(
            'max_num_pages' => $max_num_pages,
            'current_page' => $this->_page,
            'base' => get_author_posts_url($this->_authorId) . '/chat/page/%#%'
        );

        $chat_messages = array();
        $user = wp_get_current_user();
        $author = get_user_by('ID', $this->_authorId);
        $user_avatar = tt_get_avatar($user->ID);
        $author_avatar = tt_get_avatar($author->ID);
        //$user_name = $user->display_name;
        $author_name = $author->display_name;
        $user_home = get_author_posts_url($user->ID);
        $author_home = get_author_posts_url($author->ID);

        foreach ($messages as $message) {
            $chat_message = array();

            $chat_message['msg_ID'] = $message->msg_id;
            $chat_message['receiver_id'] = $message->user_id;
            $chat_message['sender_id'] = $message->sender_id;
            $chat_message['date'] = $message->msg_date;
            $chat_message['text'] = $message->msg_title;
            $chat_message['read'] = $message->sender_id == $user->ID || $message->msg_read != 0;
            $chat_message['tome'] = $message->user_id == $this->_userId;
            $chat_message['chat_avatar'] = $message->sender_id == $user->ID ? $user_avatar : $author_avatar;
            $chat_message['chat_name'] = $message->sender_id == $user->ID ? sprintf(__('You to %s', 'tt'), $author_name) : $author_name;
            $chat_message['people_home'] = $message->sender_id == $user->ID ? $user_home : $author_home;
            $chat_message['class'] = $chat_message['read'] ? 'message chat-message' : 'message chat-message unread-message';

            $chat_messages[] = $chat_message;
        }

        return (object)array(
            'pagination' => $pagination,
            'messages' => $chat_messages,
            'messages_count' => $messages_count,
            'unread_count' => $unread_count
        );
    }
}