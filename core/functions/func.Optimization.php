<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/05/27 18:08
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>

<?php

/* WordPress 后台禁用Google Open Sans字体，加速网站 */
function tt_remove_open_sans() {
	wp_deregister_style( 'open-sans' );
	wp_register_style( 'open-sans', false );
	wp_enqueue_style('open-sans','');
}
add_action( 'init', 'tt_remove_open_sans' );

/* 移除头部多余信息 */
function tt_remove_wp_version(){
	return;
}
add_filter('the_generator', 'tt_remove_wp_version'); //WordPress的版本号

remove_action('wp_head', 'feed_links', 2); //包含文章和评论的feed
remove_action('wp_head','index_rel_link'); //当前文章的索引
remove_action('wp_head', 'feed_links_extra', 3); //额外的feed,例如category, tag页
remove_action('wp_head', 'start_post_rel_link', 10); //开始篇
remove_action('wp_head', 'parent_post_rel_link', 10); //父篇
remove_action('wp_head', 'adjacent_posts_rel_link', 10); //上、下篇.
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10); //rel=pre
remove_action('wp_head', 'wp_shortlink_wp_head', 10); //rel=shortlink
//remove_action('wp_head', 'rel_canonical' );

/* 阻止站内文章Pingback */
function tt_no_self_ping( &$links ) {
	$home = get_option('home');
	foreach ( $links as $key => $link )
		if ( 0 === strpos( $link, $home ) )
			unset($links[$key]);
}
add_action('pre_ping','tt_no_self_ping');

/* 添加链接功能 */
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

/* 登录用户浏览站点时不显示工具栏 */
add_filter('show_admin_bar', '__return_false');

/* 移除emoji相关脚本 */
remove_action( 'admin_print_scripts', 'print_emoji_detection_script');
remove_action( 'admin_print_styles', 'print_emoji_styles');
remove_action( 'wp_head', 'print_emoji_detection_script', 7);
remove_action( 'wp_print_styles', 'print_emoji_styles');
remove_action('embed_head',	'print_emoji_detection_script');
remove_filter( 'the_content_feed', 'wp_staticize_emoji');
remove_filter( 'comment_text_rss', 'wp_staticize_emoji');
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email');

function tt_disable_emoji_tiny_mce_plugin($plugins){
    return array_diff( $plugins, array( 'wpemoji' ) );
}
add_filter( 'tiny_mce_plugins', 'tt_disable_emoji_tiny_mce_plugin' );
