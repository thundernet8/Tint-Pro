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
    $menus = array(
        'header-menu' => __('Top Menu', 'tt'), //顶部菜单
        'footer-menu' => __('Foot Menu', 'tt'), //底部菜单
        //'page-menu' => __('Pages Menu', 'tt') //页面合并菜单
    );
    if(TT_PRO && tt_get_option('tt_enable_shop', false)) {
        $menus['shop-menu'] = __('Shop Left Menu', 'tt');
    }
    register_nav_menus($menus);

    // 必须和推荐插件安装提醒
    function tt_register_required_plugins() {
        $plugins = array(
            // 浏览数统计
            array(
                'name' => 'WP-PostViews',
                'slug' => 'wp-postviews',
                'source' => 'https://downloads.wordpress.org/plugin/wp-postviews.1.73.zip',
                'required' => true,
                'version' => '1.73',
                'force_activation' => true,
                'force_deactivation' => false
            ),

            // 代码高亮
            array(
                'name' => 'Crayon-Syntax-Highlighter',
                'slug' => 'crayon-syntax-highlighter',
                'source' => 'https://downloads.wordpress.org/plugin/crayon-syntax-highlighter.zip',
                'required' => false,
                'version' => '2.8.4',
                'force_activation' => false,
                'force_deactivation' => false
            ),
        );
        $config = array(
            'domain'       		=> 'tt',         	// Text domain - likely want to be the same as your theme.
            'default_path' 		=> THEME_DIR .'/dash/plugins',                         	// Default absolute path to pre-packaged plugins
            //'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug(deprecated)
            //'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug(deprecated)
            'menu'         		=> 'install-required-plugins', 	// Menu slug
            'has_notices'      	=> true,                       	// Show admin notices or not
            'is_automatic'    	=> false,					   	// Automatically activate plugins after installation or not
            'message' 			=> '',							// Message to output right before the plugins table
            'strings'      		=> array(
                'page_title'                       			=> __( 'Install Required Plugins', 'tt' ),
                'menu_title'                       			=> __( 'Install Plugins', 'tt' ),
                'installing'                       			=> __( 'Installing: %s', 'tt' ), // %1$s = plugin name
                'oops'                             			=> __( 'There is a problem with the plugin API', 'tt' ),
                'notice_can_install_required'     			=> _n_noop( 'Tint require the plugin: %1$s.', 'Tint require these plugins: %1$s.', 'tt' ), // %1$s = plugin name(s)
                'notice_can_install_recommended'			=> _n_noop( 'Tint recommend the plugin: %1$s.', 'Tint recommend these plugins: %1$s.', 'tt' ), // %1$s = plugin name(s)
                'notice_cannot_install'  					=> _n_noop( 'Permission denied while installing %s plugin.', 'Permission denied while installing %s plugins.', 'tt' ),
                'notice_can_activate_required'    			=> _n_noop( 'The required plugin are not activated yet: %1$s', 'These required plugins are not activated yet: %1$s', 'tt' ),
                'notice_can_activate_recommended'			=> _n_noop( 'The recommended plugin are not activated yet: %1$s', 'These recommended plugins are not activated yet: %1$s', 'tt' ),
                'notice_cannot_activate' 					=> _n_noop( 'Permission denied while activating the %s plugin.', 'Permission denied while activating the %s plugins.', 'tt' ),
                'notice_ask_to_update' 						=> _n_noop( 'The plugin need update: %1$s.', 'These plugins need update: %1$s.', 'tt' ), // %1$s = plugin name(s)
                'notice_cannot_update' 						=> _n_noop( 'Permission denied while updating the %s plugin.', 'Permission denied while updating %s plugins.', 'tt' ),
                'install_link' 					  			=> _n_noop( 'Install the plugin', 'Install the plugins', 'tt' ),
                'activate_link' 				  			=> _n_noop( 'Activate the installed plugin', 'Activate the installed plugins', 'tt' ),
                'return'                           			=> __( 'return back', 'tt' ),
                'plugin_activated'                 			=> __( 'Plugin activated', 'tt' ),
                'complete' 									=> __( 'All plugins are installed and activated %s', 'tt' ), // %1$s = dashboard link
                'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
            )
        );
        tgmpa( $plugins, $config );
    }
    add_action( 'tgmpa_register', 'tt_register_required_plugins' );
}
add_action( 'after_setup_theme', 'tt_setup' );
