<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/25 21:43
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/**
 * 主题扩展
 *
 * @since   2.0.0
 *
 * @return  void
 */
function tt_setup() {
    // 开启自动feed地址
    add_theme_support( 'automatic-feed-links' );

    // 开启缩略图
    add_theme_support( 'post-thumbnails' );

    // 增加文章形式
    add_theme_support( 'post-formats', array( 'audio', 'aside', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );

    // 菜单区域
    register_nav_menus( array(
        'header-menu' => '顶部菜单',
        'footer-menu' => '底部菜单',
        'shop-menu' => '商城分类导航',
        'page-menu' => '页面合并菜单',
    ) );
}
add_action( 'after_setup_theme', 'tt_setup' );
