<?php

/**
 * Copyright 2016, Zhiyanblog.com
 * All right reserved.
 *
 * @author Zhiyan
 * @date 16/5/27 18:17
 * @license GPL v3 LICENSE
 */

?>

<?php

/*  注册通用边栏 */
//if ( ! function_exists( 'tin_custom_sidebars' ) ) {
//	function tin_custom_sidebars() {
//		if ( !ot_get_option('sidebar-areas') =='' ) {
//			$sidebars = ot_get_option('sidebar-areas', array());
//			if ( !empty( $sidebars ) ) {
//				foreach( $sidebars as $sidebar ) {
//					if ( isset($sidebar['title']) && !empty($sidebar['title']) && isset($sidebar['id']) && !empty($sidebar['id']) && ($sidebar['id'] !='sidebar-') ) {
//						register_sidebar(array('name' => ''.$sidebar['title'].'','id' => ''.strtolower($sidebar['id']).'','before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3><span class=widget-title>','after_title' => '</span></h3>'));
//					}
//				}
//			}
//		}
//	}
//}
//add_action( 'widgets_init', 'tin_custom_sidebars' );

/*  动态primary边栏 */
//if ( ! function_exists( 'tin_primary_sidebar' ) ) {
//	function tin_primary_sidebar() {
//		// 默认边栏
//		$sidebar = 'primary';
//
//		// 根据页面选择边栏
//		if ( is_home() && ot_get_option('s1-home') ) $sidebar = ot_get_option('s1-home');
//		if ( is_single() && ot_get_option('s1-single') ) $sidebar = ot_get_option('s1-single');
//		if ( is_archive() && ot_get_option('s1-archive') ) $sidebar = ot_get_option('s1-archive');
//		if ( is_category() && ot_get_option('s1-archive-category') ) $sidebar = ot_get_option('s1-archive-category');
//		if ( is_search() && ot_get_option('s1-search') ) $sidebar = ot_get_option('s1-search');
//		if ( is_404() && ot_get_option('s1-404') ) $sidebar = ot_get_option('s1-404');
//		if ( is_page() && ot_get_option('s1-page') ) $sidebar = ot_get_option('s1-page');
//
//		// 检查一个页面或文章是否有特指边栏
//		if ( is_page() || is_single() ) {
//			wp_reset_postdata();
//			global $post;
//			$meta = get_post_meta($post->ID,'tin_sidebar_primary',true);
//			if ( $meta ) {
//				$sidebar = $meta;
//			}
//		}
//
//		return $sidebar;
//	}
//}

/*  注册页脚边栏 */
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
