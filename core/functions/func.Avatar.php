<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/20 23:25
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

require_once THEME_CLASS . '/class.Avatar.php';
require_once 'func.Cache.php';

/**
 * 获取头像
 *
 * @since   2.0.0
 * @param   int | string | object   $id_or_email    用户ID或Email或用户实例对象
 * @param   int | string    $size                   头像尺寸
 * @return  string
 */
function tt_get_avatar($id_or_email, $size='medium'){
    $callback = function () use ($id_or_email, $size) {
        return (new Avatar($id_or_email, $size))->getAvatar();
    };
    return tt_cached((new Avatar($id_or_email, $size))->cache_key, $callback, 'avatar', 60*60*24);
}


/**
 * 清理Avatar transient缓存
 *
 * @since   2.0.0
 * @return  void
 */
//function tt_daily_clear_avatar_cache(){
//    // transient的avatar缓存在get_transient函数调用时会自动判断过期并决定是否删除，对于每个avatar url查询请求执行两次delete_option操作
//    // 这里采用定时任务一次性删除多条隔天的过期缓存，减少delete_option操作
//    global $wpdb;
//    $wpdb->query( "DELETE FROM $wpdb->options WHERE `option_name` LIKE '_transient_tt_cache_daily_avatar_%' OR `option_name` LIKE '_transient_timeout_tt_cache_daily_avatar_%'" );
//}
//add_action('tt_setup_common_daily_event', 'tt_daily_clear_avatar_cache');
