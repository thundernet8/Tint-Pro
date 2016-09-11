<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/5/27 17:33
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/* 后台编辑器预览样式 */
add_editor_style(THEME_ASSET.'/dash/css/editor-preview.css');

/* 后台编辑器强化 */
function tt_add_more_buttons($buttons){
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'styleselect';
	$buttons[] = 'fontselect';
	$buttons[] = 'hr';
	$buttons[] = 'sub';
	$buttons[] = 'sup';
	$buttons[] = 'cleanup';
	$buttons[] = 'image';
	$buttons[] = 'code';
	$buttons[] = 'media';
	$buttons[] = 'backcolor';
	$buttons[] = 'visualaid';
	return $buttons;
}
add_filter("mce_buttons_3", "tt_add_more_buttons");

/**
 * 添加Admin bar项目
 *
 * @since   2.0.0
 * @param   WP_Admin_Bar  $wp_admin_bar
 */
function tt_clear_cache_on_admin_menu_bar( $wp_admin_bar ) {
    $args = array(
        'id'    => 'tt_admin_menu_bar_clear_cache',
        'title' => __('Clear Cache', 'tt'),
        'parent' => false,
        'href'  => wp_nonce_url( admin_url( 'admin.php?page=options-framework&tint_cache_empty=1' ), 'tt_clear_cache', 'tt_clear_cache_nonce' ),
        'meta'  => array( 'class' => 'tt-clear-cache' )
    );
    $wp_admin_bar->add_node( $args );
}
add_action( 'admin_bar_menu', 'tt_clear_cache_on_admin_menu_bar', 999 );

function tt_clear_cache_callback_on_admin_menu_bar(){
    if(isset($_GET['tt_clear_cache_nonce']) && wp_verify_nonce($_GET['tt_clear_cache_nonce'], 'tt_clear_cache')) {
        if(isset($_GET['tint_cache_empty']) && $_GET['tint_cache_empty']==1) {
            tt_clear_all_cache();
            add_settings_error( 'options-framework', 'tt_clear_cache', __( 'All Cache Clear.', 'tt' ), 'updated fade' );
        }
    }
}
//add_action('optionsframework_after', 'tt_clear_cache_callback_on_admin_menu_bar', 999);
add_action('admin_init', 'tt_clear_cache_callback_on_admin_menu_bar', 999);
