<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/09 19:40
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class UCFollowersVM
 */
class UCFollowersVM extends BaseVM {

    /**
     * @var int 作者ID
     */
    private  $_authorId = 0;

    /**
     * @var int 分页号
     */
    private $_page = 1;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
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
        $instance = new static(); // 因为不同分页共用该模型，不采用单例模式
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_author' . $author_id . '_page' . $page;
        $instance->_page = max(1, $page);
        $instance->_authorId = $author_id;
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {

        $per_page = 12;
        $offset = $per_page * ($this->_page - 1);
        $followers = tt_get_followers($this->_authorId, $per_page, $offset );
        $count = $followers ? count($followers) : 0;
        $total_count = tt_count_followers($this->_authorId);
        $max_pages = ceil($total_count / $per_page);
        $pagination_base = tt_url_for('uc_followers', $this->_authorId) . '/page/%#%';

        $follower_infos = array();
        foreach ($followers as $follower) {
            $info = array();
            $user_id = $follower->follow_user_id;
            $user = get_user_by('ID', $user_id);

            $info['ID'] = $user_id;
            $info['user_email'] = $user->user_email;
            $info['nickname'] = get_user_meta($user_id, 'nickname', true);
            $info['home'] = get_author_posts_url($user_id);
            $info['posts_url'] = $info['home'] . '/latest';
            $info['comments_url'] = $info['home'] . '/comments';
            $info['activities_url'] = $info['home'] . '/activities';
            $info['cover'] = tt_get_user_cover($user_id, 'mini', THEME_ASSET . '/img/personcard-cover.jpg');
            $info['avatar'] = tt_get_avatar($user_id, 'medium');
            $info['display_name'] = $user->display_name;
            //TODO vip图标
            $info['description'] = get_the_author_meta('description', $user_id);
            $info['followers_count'] = tt_count_followers($user_id);
            $info['following_count'] = tt_count_following($user_id);
            $info['posts_count'] = count_user_posts($user_id, 'post');
            //$info['posts_stars'] = tt_count_author_posts_stars($user_id);
            //$info['posts_views'] = tt_count_author_posts_views($user_id);
            $follower_infos[] = $info;
        }

        return (object)array(
            'count' => $count,
            'followers' => $follower_infos,
            'total' => $total_count,
            'max_pages' => $max_pages,
            'pagination_base' => $pagination_base,
            'prev_page' => str_replace('%#%', max(1, $this->_page - 1), $pagination_base),
            'next_page' => str_replace('%#%', min($max_pages, $this->_page + 1), $pagination_base)
        );
    }
}