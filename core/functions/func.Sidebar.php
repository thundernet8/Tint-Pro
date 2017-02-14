<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/05/27 18:17
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * 动态边栏
 *
 * @since   2.0.0
 * @return  string
 */
function tt_dynamic_sidebar(){
    // 默认通用边栏
    $sidebar = 'sidebar_common';

    // 根据页面选择边栏
    if ( is_home() && $option = tt_get_option('tt_home_sidebar') ) $sidebar = $option;
    if ( is_single() && $option = tt_get_option('tt_single_sidebar') ) $sidebar = $option;
    if ( is_archive() && $option = tt_get_option('tt_archive_sidebar') ) $sidebar = $option;
    if ( is_category() && $option = tt_get_option('tt_category_sidebar') ) $sidebar = $option;
    if ( is_search() && $option = tt_get_option('tt_search_sidebar') ) $sidebar = $option;
    if ( is_404() && $option = tt_get_option('tt_404_sidebar') ) $sidebar = $option;
    if ( is_page() && $option = tt_get_option('tt_page_sidebar') ) $sidebar = $option;
    if (get_query_var('site_util') == 'download' && $option = tt_get_option('tt_download_sidebar')) $sidebar = $option;

    // 检查一个页面或文章是否有特指边栏
    if ( is_singular() ) {
        wp_reset_postdata();
        global $post;
        $meta = get_post_meta($post->ID,'tt_sidebar',true);  //TODO: add post meta box for `tt_sidebar`
        if ( $meta ) {
            $sidebar = $meta;
        }
    }

    return $sidebar;
}


/**
 * 根据用户设置注册边栏
 *
 * @since   2.0.0
 * @return  void
 */
function tt_register_sidebars(){
    $sidebars = (array)tt_get_option('tt_register_sidebars', array('sidebar_common'=>true));
    $titles = array(
        'sidebar_common'    =>    __('Common Sidebar', 'tt'),
        'sidebar_home'      =>    __('Home Sidebar', 'tt'),
        'sidebar_single'    =>    __('Single Sidebar', 'tt'),
        //'sidebar_archive'   =>    __('Archive Sidebar', 'tt'),
        //'sidebar_category'  =>    __('Category Sidebar', 'tt'),
        'sidebar_search'    =>    __('Search Sidebar', 'tt'),
        //'sidebar_404'       =>    __('404 Sidebar', 'tt'),
        'sidebar_page'      =>    __('Page Sidebar', 'tt'),
        'sidebar_download'  =>    __('Download Page Sidebar', 'tt')
    );
    foreach ($sidebars as $key => $value){
        if(!$value) continue;
        $title = array_key_exists($key, $titles) ? $titles[$key] : $value;
        register_sidebar(
            array(
                'name' => $title,
                'id' => $key,
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title"><span>',
                'after_title' => '</span></h3>'
            )
        );
    }

    // 注册浮动小工具容器边栏
    register_sidebar(
        array(
            'name' => __('Float Widgets Container', 'tt'),
            'id' => 'sidebar_float',
            'description' => __("A container for placing some widgets, it will be float once exceed the vision", 'tt'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class=widget-title><span>',
            'after_title' => '</span></h3>'
        )
    );
}
add_action('widgets_init', 'tt_register_sidebars');

/*  注册页脚边栏 */ //TODO
//if ( ! function_exists( 'tin_sidebars' ) ) {
//	function tin_sidebars() {
//		register_sidebar(array( 'name' => 'Primary','id' => 'primary','description' => __("默认边栏区，请在后台设置选择各页面的边栏",'tinection'), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3><span class=widget-title>','after_title' => '</span></h3>'));
//		register_sidebar(array( 'name' => 'Float','id' => 'float','description' => __("浮动边栏，容纳一定小工具，随鼠标滚动超出可视区域后将浮动重新显示",'tinection'), 'before_widget' => '<div id="%1$s" class="%2$s">','after_widget' => '</div>','before_title' => '<h3><span class=widget-title>','after_title' => '</span></h3>'));
//		if ( ot_get_option('footer-widgets') >= '1' ) { register_sidebar(array( 'name' => 'Footer 1','id' => 'footer-1', 'description' => __("底部多列边栏1",'tinection'), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3><span class=widget-title>','after_title' => '</span></h3>')); }
//		if ( ot_get_option('footer-widgets') >= '2' ) { register_sidebar(array( 'name' => 'Footer 2','id' => 'footer-2', 'description' => __("底部多列边栏2",'tinection'), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3><span class=widget-title>','after_title' => '</span></h3>')); }
//		if ( ot_get_option('footer-widgets') >= '3' ) { register_sidebar(array( 'name' => 'Footer 3','id' => 'footer-3', 'description' => __("底部多列边栏3",'tinection'), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3><span class=widget-title>','after_title' => '</span></h3>')); }
//		if ( ot_get_option('footer-widgets') >= '4' ) { register_sidebar(array( 'name' => 'Footer 4','id' => 'footer-4', 'description' => __("底部多列边栏4",'tinection'), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3><span class=widget-title>','after_title' => '</span></h3>')); }
//		if ( ot_get_option('footer-widgets-singlerow') == 'on' ) { register_sidebar(array( 'name' => 'Footer row','id' => 'footer-row', 'description' => __("底部通栏",'tinection'), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3><span class=widget-title>','after_title' => '</span></h3>')); }
//	}
//
//}
//add_action( 'widgets_init', 'tin_sidebars' );
