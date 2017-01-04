<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/25 22:15
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/**
 * 获取文章缩略图
 *
 * @since   2.0.0
 *
 * @param   int | object    文章id或WP_Post对象
 * @param   string | array  $size   图片尺寸
 * @return  string
 */
function tt_get_thumb($post = null, $size = 'thumbnail'){
    if(!$post){
        global $post;
    }
    $post = get_post($post);

    // 优先利用缓存
    $callback = function () use ($post, $size) {
        $instance = new PostImage($post, $size);
        return $instance->getThumb();
    };
    $instance = new PostImage($post, $size);
    return tt_cached($instance->cache_key, $callback, 'thumb', 60*60*24*7);
}
