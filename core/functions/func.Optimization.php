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

/* 移除wp-embed等相关功能 */
//function tt_deregister_wp_embed_scripts(){
//    wp_deregister_script( 'wp-embed' );
//}
//add_action( 'wp_footer', 'tt_deregister_wp_embed_scripts' );
/**
 * Disable embeds on init.
 *
 * - Removes the needed query vars.
 * - Disables oEmbed discovery.
 * - Completely removes the related JavaScript.
 *
 * @since 1.0.0
 */
function tt_disable_embeds_init() {
    /* @var WP $wp */
    global $wp;

    // Remove the embed query var.
    $wp->public_query_vars = array_diff( $wp->public_query_vars, array(
        'embed',
    ) );

    // Remove the REST API endpoint.
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );

    // Turn off oEmbed auto discovery.
    add_filter( 'embed_oembed_discover', '__return_false' );

    // Don't filter oEmbed results.
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

    // Remove oEmbed discovery links.
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    add_filter( 'tiny_mce_plugins', 'tt_disable_embeds_tiny_mce_plugin' );

    // Remove all embeds rewrite rules.
    add_filter( 'rewrite_rules_array', 'tt_disable_embeds_rewrites' );

    // Remove filter of the oEmbed result before any HTTP requests are made.
    remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
}

add_action( 'init', 'tt_disable_embeds_init', 9999 );

/**
 * Removes the 'wpembed' TinyMCE plugin.
 *
 * @since 1.0.0
 *
 * @param array $plugins List of TinyMCE plugins.
 * @return array The modified list.
 */
function tt_disable_embeds_tiny_mce_plugin( $plugins ) {
    return array_diff( $plugins, array( 'wpembed' ) );
}

/**
 * Remove all rewrite rules related to embeds.
 *
 * @since 1.2.0
 *
 * @param array $rules WordPress rewrite rules.
 * @return array Rewrite rules without embeds rules.
 */
function tt_disable_embeds_rewrites( $rules ) {
    foreach ( $rules as $rule => $rewrite ) {
        if ( false !== strpos( $rewrite, 'embed=true' ) ) {
            unset( $rules[ $rule ] );
        }
    }

    return $rules;
}

/**
 * Remove embeds rewrite rules on theme activation.
 *
 * @since 1.2.0
 */
function tt_disable_embeds_remove_rewrite_rules() {
    add_filter( 'rewrite_rules_array', 'tt_disable_embeds_rewrites' );
    flush_rewrite_rules();
}
add_action('load-themes.php', 'tt_disable_embeds_remove_rewrite_rules');

/**
 * Flush rewrite rules on theme deactivation.
 *
 * @since 1.2.0
 */
function tt_disable_embeds_flush_rewrite_rules() {
    remove_filter( 'rewrite_rules_array', 'tt_disable_embeds_rewrites' );
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'tt_disable_embeds_flush_rewrite_rules');


/**
 * 搜索结果排除页面
 *
 * @since 2.0.0
 * @param WP_Query $query
 * @return WP_Query
 */
function tt_search_filter_page($query) {
    if ($query->is_search) {
        $query->set('post_type', 'post');
    }
    return $query;
}
add_filter('pre_get_posts','tt_search_filter_page');


/**
 * 摘要长度
 *
 * @since 2.0.0
 * @param $length
 * @return mixed
 */
function tt_excerpt_length( $length ) {
    return tt_get_option('tt_excerpt_length', $length);
}
add_filter( 'excerpt_length', 'tt_excerpt_length', 999 );

/* 去除正文P标签包裹 */
//remove_filter( 'the_content', 'wpautop' );

/* 去除摘要P标签包裹 */
remove_filter( 'the_excerpt', 'wpautop' );

/* HTML转义 */
//取消内容转义
remove_filter('the_content', 'wptexturize');
//取消摘要转义
//remove_filter('the_excerpt', 'wptexturize');
//取消评论转义
//remove_filter('comment_text', 'wptexturize');


/* 找回上传图片路径设置 */
if(get_option('upload_path') == 'wp-content/uploads' || get_option('upload_path') == null){
    update_option('upload_path','wp-content/uploads');
}

/**
 * 中文名文件上传改名
 *
 * @since 2.0.0
 * @param $file
 * @return mixed
 */
function tt_custom_upload_name($file){
    if(preg_match('/[一-龥]/u',$file['name'])):
        $ext=ltrim(strrchr($file['name'],'.'),'.');
        $file['name'] = preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])) . '_' . date('Y-m-d_H-i-s') . '.' . $ext;
    endif;
    return $file;
}
add_filter('wp_handle_upload_prefilter','tt_custom_upload_name', 5, 1);


/**
 * 替换文章或评论内容外链为内链
 *
 * @since 2.0.0
 * @param $content
 * @return mixed
 */
function tt_convert_to_internal_links($content){
    if(!tt_get_option('tt_disable_external_links', false)) {
        return $content;
    }
    preg_match_all('/\shref=(\'|\")(http[^\'\"#]*?)(\'|\")([\s]?)/', $content, $matches);
    if($matches){
        $home = home_url();
        foreach($matches[2] as $val){
            if(strpos($val, $home)===false){
                $rep = $matches[1][0].$val.$matches[3][0];
                $new = '"'. $home . '/redirect/' . base64_encode($val). '" target="_blank"';
                $content = str_replace("$rep", "$new", $content);
            }
        }
    }
    return $content;
}
add_filter('the_content', 'tt_convert_to_internal_links', 99);
add_filter('comment_text', 'tt_convert_to_internal_links', 99);
add_filter('get_comment_author_link', 'tt_convert_to_internal_links', 99);


/**
 * 转换为内链的外链跳转处理
 *
 * @return bool
 */
function tt_handle_external_links_redirect() {
    $base_url = home_url('/redirect/');
    $request_url = Utils::getPHPCurrentUrl();
    if (substr($request_url, 0, strlen($base_url)) != $base_url) {
        return false;
    }
    $key = str_ireplace($base_url, '', $request_url);
    if (!empty($key)) {
        $external_url = base64_decode($key);
        wp_redirect( $external_url, 302 );
        exit;
    }
    return false;
}
add_action('template_redirect', 'tt_handle_external_links_redirect');