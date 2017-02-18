<?php
/**
 * Copyright (c) 2014-2017, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/02/18 12:07
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class SingleBulletinVM
 */
class SingleBulletinVM extends BaseVM {
    /**
     * @var int 公告ID
     */
    private $_bulletinId = 0;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $bulletin_id   公告ID
     * @return  static
     */
    public static function getInstance($bulletin_id = 1) {
        $instance = new static(); // 因为不同分页共用该模型，不采用单例模式
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_bulletin' . $bulletin_id;
        $instance->_bulletinId = absint($bulletin_id);
        //$instance->_enableCache = false; //Debug关闭缓存
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $the_bulletin = null;
        while(have_posts()) : the_post();
            global $post;
            $the_bulletin = $post ? : get_post($this->_bulletinId);
        endwhile;

        // 基本信息
        $info = array();
        $info['ID'] = $the_bulletin->ID;
        $info['title'] = get_the_title($the_bulletin);
        $info['permalink'] = get_permalink($the_bulletin);
        $info['comment_count'] = $the_bulletin->comment_count;
        $info['comment_status'] = !($the_bulletin->comment_status != 'open');
        $info['excerpt'] = get_the_excerpt($the_bulletin);
        $content = get_the_content();
        $content = str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', $content ) );
        $info['content'] =  $content; //$the_post->post_content;
        $info['author'] = get_the_author();
        $info['author_url'] = get_author_posts_url($the_bulletin->post_author);
        //$info['time'] = get_post_time('F j, Y', false, $the_bulletin, false); //get_post_time( string $d = 'U', bool $gmt = false, int|WP_Post $post = null, bool $translate = false )
        $info['datetime'] = get_the_time('Y-m-d H:i', $the_bulletin);
        $info['modified'] = get_post_modified_time('Y-m-d H:i', false, $the_bulletin);
        //$info['timediff'] = Utils::getTimeDiffString($info['datetime']);
        //$info['modifieddiff'] = Utils::getTimeDiffString($info['modified']);

        // 浏览数
        $views = absint(get_post_meta( $the_bulletin->ID, 'views', true ));

        // 上下篇公告
        $prev = get_previous_post_link('%link');
        $next = get_next_post_link('%link');

        return (object)array_merge(
            $info,
            array(
                'views'        => $views,
                'prev'         => $prev ? $prev : __('Not any more', 'tt'),
                'next'         => $next ? $next : __('Not any more', 'tt')
            )
        );
    }
}