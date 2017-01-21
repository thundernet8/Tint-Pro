<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/26 22:03
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class RecentCommentsVM
 */
class RecentCommentsVM extends BaseVM {

    /**
     * @var int 评论数量
     */
    private $_count = 6;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'hourly';
        $this->_cacheInterval = 1800; // 缓存保留半小时
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $count   评论数量
     * @return  static
     */
    public static function getInstance($count = 6) {
        // TODO post type参数
        $comments_count = min(6, absint($count));
        $instance = new static();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_count' . $comments_count;
        $instance->_count = $comments_count;;
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $the_comments = get_comments(array(
            'status' => 'approve',
            'type' => 'comment', // 'pings' (includes 'pingback' and 'trackback'),
            //'meta_key' => 'tt_sticky_comment',
            //'author__not_in' => tt_get_administrator_ids(),
            'orderby' => 'comment_date', //meta_value_num
            'order' => 'DESC',
            'number' => $this->_count,
            'offset' => 0
        ));

        $recent_comments = array();
        foreach ($the_comments as $the_comment) {
            $recent_comment = array();
            if(!$the_comment->user_id) continue;
            $recent_comment['author_name'] = $the_comment->comment_author;
            $recent_comment['author_avatar'] = tt_get_avatar($the_comment->user_id, 'small');
            $recent_comment['author_url'] = $the_comment->comment_author_url;
            $recent_comment['comment_date'] = $the_comment->comment_date;
            $recent_comment['comment_date_diff'] = Utils::getTimeDiffString($recent_comment['comment_date']);
            $recent_comment['post_permalink'] = get_permalink($the_comment->comment_post_ID);
            $recent_comment['post_title'] = get_the_title($the_comment->comment_post_ID);
            $recent_comment['comment_text'] = get_comment_text($the_comment);

            $recent_comments[] = $recent_comment;
        }

        return $recent_comments;
    }
}
