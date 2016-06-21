<?php

/**
 * Copyright 2016, Zhiyanblog.com
 * All right reserved.
 *
 * @author Zhiyan
 * @date 16/5/27 18:08
 * @license GPL v3 LICENSE
 */
 
?>

<?php

/* WordPress 后台禁用Google Open Sans字体，加速网站 */
function tin_remove_open_sans() {
	wp_deregister_style( 'open-sans' );
	wp_register_style( 'open-sans', false );
	wp_enqueue_style('open-sans','');
}
add_action( 'init', 'tin_remove_open_sans' );

/* 移除头部多余信息 */
function tin_remove_wp_version(){
	return;
}
add_filter('the_generator', 'tin_remove_wp_version'); //WordPress的版本号

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
function tin_no_self_ping( &$links ) {
	$home = get_option('home');
	foreach ( $links as $key => $link )
		if ( 0 === strpos( $link, $home ) )
			unset($links[$key]);
}
add_action('pre_ping','tin_no_self_ping');

/* 添加链接功能 */
add_filter( 'pre_option_link_manager_enabled', '__return_true' );