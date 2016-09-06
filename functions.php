<?php

/**
 * Copyright 2016, WebApproach.net
 * All right reserved.
 *
 * @author Zhiyan
 * @date 16/5/27 16:30
 * @license GPL v3 LICENSE
 */

?>

<?php

/* 安全检测 */
//defined( 'ABSPATH' ) || exit;
if (!defined('ABSPATH')){
    wp_die(__('Lack of WordPress environment', 'tt'), __('WordPress internal error', 'tt'), array('response'=>500));
}

/* 引入常量 */
require_once 'core/functions/Constants.php';

/* 设置默认时区 */
date_default_timezone_set('PRC');

if(!function_exists('load_dash')){
	function load_dash($path){
		load_template(THEME_DIR.'/dash/'.$path.'.php');
	}
}

if(!function_exists('load_api')){
    function load_api($path){
        load_template(THEME_DIR.'/core/api/'.$path.'.php');
    }
}

if(!function_exists('load_class')){
	function load_class($path){
		load_template(THEME_DIR.'/core/classes/'.$path.'.php');
	}
}

if(!function_exists('load_func')){
    function load_func($path){
        load_template(THEME_DIR.'/core/functions/'.$path.'.php');
    }
}

if(!function_exists('load_mod')){
    function load_mod($path){
        load_template(THEME_DIR.'/core/modules/'.$path.'.php');
    }
}

/* 载入option_framework */
load_dash('of_inc/options-framework');

/* 载入主题选项 */
load_dash('options');

/* 载入后台相关处理逻辑 */
if( is_admin() ){
    load_dash('dash');
}

/* 载入REST API功能控制函数 */
load_api('api.Config');

/* 载入类 */
load_class('class.Avatar');
load_class('class.Open');
load_class('class.PostImage');
load_class('class.Utils');

/* 载入功能函数 */
load_func('func.L10n');
load_func('func.Account');
load_func('func.Avatar');
load_func('func.Cache');
load_func('func.Email');
load_func('func.Init');
load_func('func.Install');
load_func('func.Kits');
load_func('func.Mail');
load_func('func.Module');
load_func('func.Optimization');
load_func('func.Page');
load_func('func.Rewrite');
load_func('func.Robots');
load_func('func.Schedule');
load_func('func.Script');
load_func('func.Sidebar');
load_func('func.Template');
load_func('func.Thumb');

/* 载入主题功能模块 */
function tt_load() {
	//载入小工具
	load_template( THEME_DIR . '/modules/widgets/tin-tabs.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-posts.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-posts-h.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-tagcloud.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-enhanced-text.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-readerwall.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-mailcontact.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-site.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-float-widget.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-bookmark.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-bookmark-h.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-subscribe.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-aboutsite.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-joinus.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-hotsearch.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-creditsrank.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-ucenter.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-donate.php' );
	load_template( THEME_DIR . '/modules/widgets/tin-slider.php' );

	// 载入功能
	load_template( THEME_DIR . '/functions/open-social.php' );
	load_template( THEME_DIR . '/functions/message.php' );
	load_template( THEME_DIR . '/functions/credit.php' );
	load_template( THEME_DIR . '/functions/recent-user.php' );
	load_template( THEME_DIR . '/functions/tracker.php' );
	load_template( THEME_DIR . '/functions/user-page.php' );
	load_template( THEME_DIR . '/functions/meta.php' );
	load_template( THEME_DIR . '/functions/comment.php' );
	load_template( THEME_DIR . '/functions/shortcode.php' );
	load_template( THEME_DIR . '/functions/IP.php' );
	load_template( THEME_DIR . '/functions/mail.php' );
	load_template( THEME_DIR . '/functions/meta-box.php' );
	load_template( THEME_DIR . '/functions/newsletter.php' );
	load_template( THEME_DIR . '/functions/ua.php' );
	load_template( THEME_DIR . '/functions/download.php' );
	load_template( THEME_DIR . '/functions/no_category_base.php' );
	load_template( THEME_DIR . '/functions/shop.php' );
	load_template( THEME_DIR . '/functions/membership.php' );
	load_template( THEME_DIR . '/functions/auto-save-image.php' );
	load_template( THEME_DIR . '/functions-customize.php' );
	if ( is_admin() ) {
		load_template( THEME_DIR . '/functions/class-tgm-plugin-activation.php' );
	}

	// 载入语言包
	//load_theme_textdomain( 'option-tree', THEME_DIR . '/dash/option/languages' );

	// 移除自动保存和修订版本
//	if ( ot_get_option( 'wp_auto_save' ) == 'on' ) {
//		add_action( 'wp_print_scripts', 'tin_disable_autosave' );
//		remove_action( 'post_updated', 'wp_save_post_revision' );
//	}
}
//add_action( 'after_setup_theme', 'tt_load' );



