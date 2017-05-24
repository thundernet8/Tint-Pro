<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/24 21:01
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class AuthorWidgetVM
 */
class AuthorWidgetVM extends BaseVM {
    /**
     * @var int 作者ID
     */
    private $_authorId = 0;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'hourly';
        $this->_cacheInterval = 3600; // 缓存保留一小时
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $author_id   作者ID
     * @return  static
     */
    public static function getInstance($author_id = 1) {
        $instance = new static(); // 因为不同作者共用该模型，不采用单例模式
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_author' . $author_id;
        $instance->_authorId = absint($author_id);
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $user = get_user_by('id', $this->_authorId);

        // 昵称
        $nickname = get_user_meta($this->_authorId, 'nickname', true);

        // 作者主页
        $author_home = get_author_posts_url($this->_authorId, $nickname);

        // 作者头像
        $avatar = tt_get_avatar($user);

        // 作者用户的自定义封面图像
        $author_cover = tt_get_user_cover($this->_authorId, 'mini'); // TODO 上传大封面时考虑裁剪一份作为边栏作者信息展示封面

        // 作者的用户等级
        $author_cap = tt_get_user_cap_string($this->_authorId);

        // 作者的文章总数
        $author_posts_count = count_user_posts($this->_authorId, 'post');

        // 作者的关注数量
        $author_following_count = tt_count_user_following($this->_authorId);

        // 作者的粉丝数量
        $author_followers_count = tt_count_user_followers($this->_authorId);

        // 作者的文章被浏览总数
        //$author_posts_views = tt_count_author_posts_views($this->_authorId);

        // 作者的文章收到的Star总数
        $author_posts_stars = tt_count_author_posts_stars($this->_authorId);

        return (object)array(
            'ID'                => $user->ID,
            'user_login'        => $user->user_login,
            'user_email'        => $user->user_email,
            'display_name'      => $user->display_name,
            'nickname'          => $nickname,
            'homepage'          => $author_home,
            'avatar'            => $avatar,
            'cover'             => $author_cover,
            'cap'               => $author_cap,
            'posts_count'       => $author_posts_count,
            'following_count'   => $author_following_count,
            'followers_count'   => $author_followers_count,
            //'posts_views'       => $author_posts_views,
            'posts_stars'       => $author_posts_stars
        );
    }
}
