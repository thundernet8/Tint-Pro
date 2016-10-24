<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/24 21:19
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/**
 * 获取用户权限描述字符
 *
 * @since 2.0.0
 * @param $user_id
 * @return string
 */
function tt_get_user_cap_string ($user_id) {
    if(user_can($user_id,'install_plugins')) {
        return __('Site Manager', 'tt');
    }
    if(user_can($user_id,'edit_others_posts')) {
        return __('Editor', 'tt');
    }
    if(user_can($user_id,'publish_posts')) {
        return __('Author', 'tt');
    }
    if(user_can($user_id,'edit_posts')) {
        return __('Contributor', 'tt');
    }
    return __('Reader', 'tt');
}


/**
 * 获取用户的封面
 *
 * @since 2.0.0
 * @param $user_id
 * @param $size
 * @return string
 */
function tt_get_user_cover ($user_id, $size = 'full') {
    if(!in_array($size, ['full', 'mini'])) {
        $size = 'full';
    }
    if($cover = get_user_meta($user_id, 'tt_user_cover', true)) {
        return $cover; // TODO size
    }
    return THEME_ASSET . '/img/user-default-cover-' . $size . '.jpg';
}


/**
 * 获取用户正在关注的人数
 *
 * @since 2.0.0
 * @param $user_id
 * @return int
 */
function tt_count_user_following ($user_id) {

    return 1; //TODO
}

/**
 * 获取用户的粉丝数量
 *
 * @since 2.0.0
 * @param $user_id
 * @return int
 */
function tt_count_user_followers ($user_id) {

    return 20; //TODO
}


/**
 * 获取作者的文章被浏览总数
 *
 * @since 2.0.0
 * @param $user_id
 * @param $view_key
 * @return int
 */
function tt_count_author_posts_views ($user_id, $view_key = 'views') {
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $sql = $wpdb->prepare("SELECT SUM(meta_value) FROM $wpdb->postmeta RIGHT JOIN $wpdb->posts ON $wpdb->postmeta.meta_key='%s' AND $wpdb->posts.post_author=%d AND $wpdb->postmeta.post_id=$wpdb->posts.ID", $view_key, $user_id);
    $count = $wpdb->get_var($sql);

    return $count;
}


function tt_count_author_posts_stars ($user_id) {
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $sql = $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->postmeta  WHERE meta_key='%s' AND post_id IN (SELECT ID FROM $wpdb->posts WHERE post_author=%d)", 'tt_post_star_users', $user_id);
    $count = $wpdb->get_var($sql);

    return $count;
}