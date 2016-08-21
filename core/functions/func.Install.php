<?php

/**
 * Copyright 2016, WebApproach.net
 * All right reserved.
 *
 * @author Zhiyan
 * @date 2016/08/16 21:54
 * @license GPL v3 LICENSE
 */

?>

<?php

/* 建立Avatar文件夹 */
function tt_add_avatar_folder() {
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir = $upload_dir . '/avatars';
    if (! is_dir($upload_dir)) {
        // TODO: safe mkdir and echo possible error info on DEBUG mode(option)
        try {
            mkdir( $upload_dir, 0755 );
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
 */
function tt_copy_memcache_cache_plugin(){
    //TODO: maybe need check the file in wp-content is same with that in theme dir
    if(!file_exists( WP_CONTENT_DIR . '/object-cache.php' ) && file_exists( THEME_DIR . '/dashboard/plugins/memcache/object-cache.php') && tt_get_option('tt_object_cache', 'none') == 'memcache' ){
        try{
            copy(THEME_DIR . '/dashboard/plugins/memcache/object-cache.php', WP_CONTENT_DIR . '/object-cache.php');
        }catch (Exception $e){
            if(tt_get_option('tt_theme_debug', false)){
                $message = __('Can not copy `memcache object-cache.php` to `wp-content` dir.\n', 'tt') . __('Caught exception: ', 'tt') . $e->getMessage() . '\n';
                $title = __('WordPress internal error', 'tt');
                wp_die($message, $title);
            }
        }
    }
}
add_action('load-themes.php', 'tt_copy_memcache_cache_plugin');

/* 建立数据表 */
//TODO: add tables
