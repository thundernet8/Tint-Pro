<?php
/**
 * Copyright (c) 2014-2017, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/02/18 11:45
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * 创建公告自定义文章类型
 *
 * @since 2.0.5
 * @return void
 */
function tt_create_bulletin_post_type() {
    $bulletin_slug = tt_get_option('tt_bulletin_archives_slug', 'bulletin');
    register_post_type( 'bulletin',
        array(
            'labels' => array(
                'name' => _x( 'Bulletins', 'taxonomy general name', 'tt' ),
                'singular_name' => _x( 'Bulletin', 'taxonomy singular name', 'tt' ),
                'add_new' => __( 'Add New Bulletin', 'tt' ),
                'add_new_item' => __( 'Add New Bulletin', 'tt' ),
                'edit' => __( 'Edit', 'tt' ),
                'edit_item' => __( 'Edit Bulletin', 'tt' ),
                'new_item' => __( 'Add Bulletin', 'tt' ),
                'view' => __( 'View', 'tt' ),
                'all_items' => __( 'All Bulletins', 'tt' ),
                'view_item' => __( 'View Bulletin', 'tt' ),
                'search_items' => __( 'Search Bulletin', 'tt' ),
                'not_found' => __( 'Bulletin not found', 'tt' ),
                'not_found_in_trash' => __( 'Bulletin not found in trash', 'tt' ),
                'parent' => __( 'Parent Bulletin', 'tt' ),
                'menu_name' => __( 'Bulletins', 'tt' ),
            ),

            'public' => true,
            'menu_position' => 16,
            'supports' => array( 'title', 'author', 'editor',/* 'comments', */'excerpt' ),
            'taxonomies' => array( '' ),
            'menu_icon' => 'dashicons-megaphone',
            'has_archive' => false,
            'rewrite'	=> array('slug'=>$bulletin_slug)
        )
    );
}
add_action( 'init', 'tt_create_bulletin_post_type' );


/**
 * 为公告启用单独模板
 *
 * @since 2.0.0
 * @param $template_path
 * @return string
 */
function tt_include_bulletin_template_function( $template_path ) {
    if ( get_post_type() == 'bulletin' ) {
        if ( is_single() ) {
            //指定单个公告模板
            if ( $theme_file = locate_template( array ( 'core/templates/bulletins/tpl.Bulletin.php' ) ) ) {
                $template_path = $theme_file;
            }
        }
    }
    return $template_path;
}
add_filter( 'template_include', 'tt_include_bulletin_template_function', 1 );


/**
 * 自定义公告的链接
 *
 * @since 2.0.0
 * @param $link
 * @param object $post
 * @return string|void
 */
function tt_custom_bulletin_link( $link, $post = null ){
    $bulletin_slug = tt_get_option('tt_bulletin_archives_slug', 'bulletin');
    $bulletin_slug_mode = tt_get_option('tt_bulletin_link_mode')=='post_name' ? $post->post_name : $post->ID;
    if ( $post->post_type == 'bulletin' ){
        return home_url( $bulletin_slug . '/' . $bulletin_slug_mode . '.html' );
    } else {
        return $link;
    }
}
add_filter('post_type_link', 'tt_custom_bulletin_link', 1, 2);


/**
 * 处理公告自定义链接Rewrite规则
 *
 * @since 2.0.0
 * @return void
 */
function tt_handle_custom_bulletin_rewrite_rules(){
    $bulletin_slug = tt_get_option('tt_bulletin_archives_slug', 'bulletin');
    if(tt_get_option('tt_bulletin_link_mode') == 'post_name'):
        add_rewrite_rule(
            $bulletin_slug . '/([一-龥a-zA-Z0-9_-]+)?.html([\s\S]*)?$',
            'index.php?post_type=bulletin&name=$matches[1]',
            'top' );
    else:
        add_rewrite_rule(
            $bulletin_slug . '/([0-9]+)?.html([\s\S]*)?$',
            'index.php?post_type=bulletin&p=$matches[1]',
            'top' );
    endif;
}
add_action( 'init', 'tt_handle_custom_bulletin_rewrite_rules' );
