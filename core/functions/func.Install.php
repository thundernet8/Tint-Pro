<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/16 21:54
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/**
 * 建立Avatar文件夹
 *
 * @since   2.0.0
 *
 * @return  void
 */
function tt_add_avatar_folder() {
    //$upload = wp_upload_dir();
    //$upload_dir = $upload['basedir'];
    //$upload_dir = $upload_dir . '/avatars';
    $avatar_dir = WP_CONTENT_DIR . '/uploads/avatars';
    if (! is_dir($avatar_dir)) {
        // TODO: safe mkdir and echo possible error info on DEBUG mode(option)
        try {
            mkdir( $avatar_dir, 0755 );
        }catch (Exception $e){
            if(tt_get_option('tt_theme_debug', false)){
                $message = __('Create avatar upload folder failed, maybe check your php.ini to enable `mkdir` function.\n', 'tt') . __('Caught exception: ', 'tt') . $e->getMessage() . '\n';
                $title = __('WordPress internal error', 'tt');
                wp_die($message, $title);
            }
        }
    }
}
add_action('load-themes.php', 'tt_add_avatar_folder');


/**
 * 复制Object-cache.php到wp-content目录
 *
 * @since   2.0.0
 *
 * @return  void
 */
function tt_copy_memcache_cache_plugin(){
    //TODO: maybe need check the file in wp-content is same with that in theme dir
    if(!file_exists( WP_CONTENT_DIR . '/object-cache.php' ) && file_exists( THEME_DIR . '/dash/plugins/memcache/object-cache.php') && tt_get_option('tt_object_cache', 'none') == 'memcache' ){
        try{
            copy(THEME_DIR . '/dash/plugins/memcache/object-cache.php', WP_CONTENT_DIR . '/object-cache.php');
        }catch (Exception $e){
            if(tt_get_option('tt_theme_debug', false)){
                $message = __('Can not copy `memcache object-cache.php` to `wp-content` dir.\n', 'tt') . __('Caught exception: ', 'tt') . $e->getMessage() . '\n';
                $title = __('Create folder error', 'tt');
                wp_die($message, $title);
            }
        }
    }
}
add_action('load-themes.php', 'tt_copy_memcache_cache_plugin');


/**
 * 复制Timthumb图片裁剪插件必须的缓存引导文件至指定目录
 *
 * @since   2.0.0
 *
 * @return  void
 */
function tt_copy_timthumb_cache_base(){
    $cache_dir = WP_CONTENT_DIR . '/cache';
    if (! is_dir($cache_dir)) {
        try {
            mkdir( $cache_dir, 0755 );
            mkdir( $cache_dir . '/timthumb', 0755 );
        }catch (Exception $e){
            if(tt_get_option('tt_theme_debug', false)){
                $message = __('Create timthumb cache folder failed, maybe check your php.ini to enable `mkdir` function.\n', 'tt') . __('Caught exception: ', 'tt') . $e->getMessage() . '\n';
                $title = __('Create folder error', 'tt');
                wp_die($message, $title);
            }
        }
    }

    if(is_dir($cache_dir)){
        try{
            copy(THEME_DIR . '/dash/plugins/timthumb/index.html', WP_CONTENT_DIR . '/cache/timthumb/index.html');
            copy(THEME_DIR . '/dash/plugins/timthumb/timthumb_cacheLastCleanTime.touch', WP_CONTENT_DIR . '/cache/timthumb/timthumb_cacheLastCleanTime.touch');
        }catch (Exception $e){
            if(tt_get_option('tt_theme_debug', false)){
                $message = __('Can not copy `memcache object-cache.php` to `wp-content` dir.\n', 'tt') . __('Caught exception: ', 'tt') . $e->getMessage() . '\n';
                $title = __('WordPress internal error', 'tt');
                wp_die($message, $title);
            }
        }
    }
}
add_action('load-themes.php', 'tt_copy_timthumb_cache_base');


/**
 * 重置缩略图的默认尺寸
 *
 * @since   2.0.0
 *
 * @return  void
 */
function tt_reset_image_size(){
    update_option( 'thumbnail_size_w', 225 );
    update_option( 'thumbnail_size_h', 150 );
    update_option( 'thumbnail_crop', 1 );
    update_option( 'medium_size_w', 375 );
    update_option( 'medium_size_h', 250 );
    update_option( 'large_size_w', 960 );
    update_option( 'large_size_h', 640 );
}
add_action('load-themes.php', 'tt_reset_image_size');

/* 建立数据表 */
//TODO: add tables

/**
 * 新建粉丝和关注所用的数据表
 *
 * @since 2.0.0
 * @return void
 */
function tt_install_follow_table () {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tt_follow';
    if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) :
        $sql = " CREATE TABLE `$table_name` (
			`id` int NOT NULL AUTO_INCREMENT, 
			PRIMARY KEY(id),
			INDEX uid_index(user_id),
			INDEX fuid_index(follow_user_id),
			`user_id` int,
			`follow_user_id` int,
			`follow_status` int,
			`follow_time` datetime
		) ENGINE = MyISAM CHARSET=utf8;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    endif;
}
add_action( 'load-themes.php', 'tt_install_follow_table' );


/**
 * 新建消息所用的数据表
 *
 * @since 2.0.0
 * @return void
 */
function tt_install_message_table () {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tt_message';
    if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) :
        $sql = " CREATE TABLE `$table_name` (
			`msg_id` int NOT NULL AUTO_INCREMENT, 
			PRIMARY KEY(msg_id),
			INDEX uid_index(user_id),
			INDEX sid_index(sender_id),
			INDEX mtype_index(msg_type),
			INDEX mdate_index(msg_date),
			INDEX mstatus_index(msg_read),
			`user_id` int,
			`sender_id` int,
			`sender`  varchar(50),
			`msg_type` varchar(20),
			`msg_date` datetime,
			`msg_title` text,
			`msg_content` text,
			`msg_read`  boolean DEFAULT 0,
			`msg_status`  varchar(20)
		) ENGINE = MyISAM CHARSET=utf8;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    endif;
}
add_action( 'load-themes.php', 'tt_install_message_table' );