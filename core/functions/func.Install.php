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
            echo __('Create avatar upload folder failed, maybe check your php.ini to enable `mkdir` function.\n', 'tt'),
            __('Caught exception: ', 'tt'),
            $e->getMessage(),
            '\n';
        }
    }
}
add_action('load-themes.php', 'tt_add_avatar_folder');

/* 建立数据表 */
//TODO: add tables
