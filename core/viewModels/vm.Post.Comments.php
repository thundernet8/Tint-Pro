<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/10 20:51
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class PostCommentsVM
 */
class PostCommentsVM extends BaseVM {
    /**
     * @var int 文章ID
     */
    private $_postId = 0;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'hourly';
        $this->_cacheInterval = 3600; // 缓存保留一小时
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $post_id   文章ID
     * @return  static
     */
    public static function getInstance($post_id = 1) {
        $instance = new static(); // 因为不同文章的评论共用该模型，不采用单例模式
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_post' . $post_id . '_comments';
        $instance->_postId = intval($post_id);
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $per_page = tt_get_option('tt_comments_per_page', 20); // 自定义的每页评论数量

        $the_comments = get_comments(array(
            'status' => 'approve',
            'type' => 'comment', // 'pings' (includes 'pingback' and 'trackback'),
            'post_id'=> $this->_postId,
            //'meta_key' => 'tt_sticky_comment',
            'orderby' => 'comment_date', //meta_value_num
            'order' => 'DESC',
            'number' => $per_page,
            'offset' => 0
        ));

        $comment_list = wp_list_comments(array(
            'type'=>'all',
            'callback'=>'tt_comment',
            'end-callback'=>'tt_end_comment',
            'max_depth'=>3,
            'reverse_top_level'=>0,
            'style'=>'div',
            'page'=>1,
            'per_page'=>$per_page,
            'echo'=>false
        ), $the_comments);

        return (object)array(
            'list_html' => $comment_list,
            'list_count' => count($the_comments)
        );
    }
}