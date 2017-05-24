<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/08 20:18
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class UCCommentsVM
 */
class UCCommentsVM extends BaseVM {
    /**
     * @var int 作者ID
     */
    private $_authorId = 0;

    /**
     * @var int 分页号
     */
    private $_page = 1;

    /**
     * @var bool 是否返回所有状态的评论
     */
    private $_allStatus = false;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'hourly';
        $this->_cacheInterval = 3600; // 缓存保留一小时
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $author_id   作者ID
     * @param   int    $page    分页号
     * @param   bool   $all_status 是否显示所有状态评论
     * @return  static
     */
    public static function getInstance($page = 1, $author_id = 0, $all_status = false) {
        $instance = new static(); // 因为不同作者不同分页的评论共用该模型，不采用单例模式
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_author' . $author_id . '_page' . $page . '_status' . intval(!$all_status);
        $instance->_authorId = absint($author_id);
        $instance->_page = absint($page);
        $instance->_allStatus = $all_status;
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $per_page = 20; //tt_get_option('tt_comments_per_page', 20); // 自定义的每页评论数量

        $the_comments = get_comments(array(
            'status' => $this->_allStatus ? '' : 'approve',
            'type' => 'comment', // 'pings' (includes 'pingback' and 'trackback'),
            'author__in' => array($this->_authorId),
            //'meta_key' => 'tt_sticky_comment',
            'orderby' => 'comment_date', //meta_value_num
            'order' => 'DESC',
            'number' => $per_page,
            'offset' => ($this->_page - 1) * $per_page
        ));

        $uc_comments = array();
        foreach ($the_comments as $the_comment) {
            $uc_comment = array();
            if(!$the_comment->user_id) continue;
            $uc_comment['comment_ID'] = $the_comment->comment_ID;
            $uc_comment['author_name'] = $the_comment->comment_author;
            $uc_comment['author_avatar'] = tt_get_avatar($the_comment->user_id, 'small');
            $uc_comment['author_url'] = $the_comment->comment_author_url;
            $uc_comment['comment_date'] = $the_comment->comment_date;
            $uc_comment['comment_datetime'] = date_format(new DateTime($the_comment->comment_date), 'Y-m-d H:i');
            $uc_comment['comment_date_diff'] = Utils::getTimeDiffString($uc_comment['comment_date']);
            $uc_comment['post_permalink'] = get_permalink($the_comment->comment_post_ID);
            $uc_comment['post_title'] = get_the_title($the_comment->comment_post_ID);
            $uc_comment['comment_text'] = get_comment_text($the_comment);
            $uc_comment['approve'] = $the_comment->comment_approved;
            $uc_comment['class'] = $the_comment->comment_approved ? 'comment comment-approved' : 'comment comment-pending';

            $uc_comments[] = $uc_comment;
        }

        $all_comments_count = get_comments( array('status' => '', 'user_id'=>$this->_authorId, 'count' => true) );
        $approved_comments_count = get_comments( array('status' => 'approve', 'user_id'=>$this->_authorId, 'count' => true) );

        $pagination = array(
            'max_num_pages' => $this->_allStatus ? ceil($all_comments_count / $per_page) : ceil($approved_comments_count / $per_page),
            'current_page' => $this->_page,
            'base' => get_author_posts_url($this->_authorId) . '/comments/page/%#%'
        );

//        $comment_list = wp_list_comments(array(
//            'type'=>'all',
//            'callback'=>'tt_comment',
//            'end-callback'=>'tt_end_comment',
//            'max_depth'=>3,
//            'reverse_top_level'=>0,
//            'style'=>'div',
//            'page'=>1,
//            'per_page'=>$per_page,
//            'echo'=>false
//        ), $the_comments);

        return (object)array(
            'comments' => $uc_comments,
            'all_count' => $all_comments_count,
            'approved_count' => $approved_comments_count,
            'pending_count' => $all_comments_count - $approved_comments_count,
            'pagination' => $pagination
        );
    }
}