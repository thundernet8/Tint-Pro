<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/24 23:00
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no,minimal-ui=yes">
    <title><?php echo tt_get_page_title(); ?></title>
    <meta name="keywords" content=""><!-- TODO: SEO -->
    <meta name="description" content=""><!-- TODO: SEO -->
    <!--    <meta name="author" content="Your Name,Your Email">-->
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-transform">
    <meta http-equiv="Cache-Control" content="no-siteapp"> <!-- 禁止移动端百度转码 -->
    <meta http-equiv="Cache-Control" content="private">
    <!--    <meta http-equiv="Cache-Control" content="max-age=0">-->
    <!--    <meta http-equiv="Cache-Control" content="must-revalidate">-->
    <meta http-equiv="Page-Enter" content="revealTrans(duration=1.0,transtion=23)">
    <meta http-equiv="Page-Exit" content="revealTrans(duration=1.0,transtion=23)">
    <meta name="apple-mobile-web-app-title" content="<?php bloginfo('name'); ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="format-detection" content="telephone=no, email=no"> <!-- 禁止自动识别电话号码和邮箱 -->
    <?php if($favicon = tt_get_option('tt_favicon')) { ?>
        <link rel="shortcut icon" href="<?php echo $favicon; ?>" >
    <?php } ?>
    <?php if($png_favicon = tt_get_option('tt_png_favicon')) { ?>
        <link rel="alternate icon" type="image/png" href="<?php echo $png_favicon; ?>" >
    <?php } ?>
    <link rel='https://api.w.org/' href="<?php echo tt_url_for('api_root'); ?>" >
    <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
    <link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <!--[if lt IE 9]>
    <script src="<?php echo THEME_ASSET.'/vender/js/html5shiv/3.7.3/html5shiv.min.js'; ?>"></script>
    <script src="<?php echo THEME_ASSET.'/vender/js/respond/1.4.2/respond.min.js'; ?>"></script>
    <![endif]-->
    <!--[if lte IE 7]>
    <script type="text/javascript">
        window.location.href = "<?php echo tt_url_for('upgrade_browser'); ?>";
    </script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="<?php echo tt_get_css(); ?>"  />
    <?php if(tt_get_option('tt_head_code')) { echo tt_get_option('tt_head_code'); } ?>
    <?php wp_head(); ?>
</head>
<body <?php body_class('is-loadingApp'); ?>>
    <div class="loading-line"></div>
    <header class="header common-header">
        <nav id="header-nav" class="navigation container clearfix" role="navigation">
            <!-- Logo -->
            <a class="logo nav-col" href="<?php echo home_url(); ?>" title="<?php echo get_bloginfo('name'); ?>">
                <img src="<?php echo tt_get_option('tt_logo'); ?>" alt="<?php echo get_bloginfo('name'); ?>">
            </a>
            <!-- Top Menu -->
            <?php wp_nav_menu( array( 'theme_location' => 'header-menu', 'container' => '', 'menu_class' => 'header-menu nav-col', 'depth' => '2', 'fallback_cb' => false  ) ); ?>
            <!-- End Top Menu -->
            <!-- Header Right Tools -->
            <ul class="header-tool-menu nav-col pull-right">
                <li class="nav-search"><a href="javascript:void(0)"><span class="tico tico-search"></span></a></li>
                <?php $user = wp_get_current_user(); ?>
                <?php if($user && $user->ID) { ?>
                    <li class="nav-user dropdown">
                        <a href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo tt_get_avatar($user->ID, 'small'); ?>" class="avatar">
                            <span class="username"><?php echo $user->display_name; ?></span>
                            <span class="tico-angle-down"></span>
                        </a>
                        <ul class="nav-user-menu dropdown-menu">
                            <?php if(current_user_can('edit_users')) { ?>
                            <li><a href="<?php echo get_dashboard_url(); ?>"><span class="tico tico-meter"></span><?php _e('Go Dashboard', 'tt'); ?></a></li>
                            <?php } ?>
                            <li><a href="<?php echo tt_url_for('new_post'); ?>"><span class="tico tico-quill"></span><?php _e('New Post', 'tt'); ?></a></li>
                            <li><a href="<?php echo tt_url_for('uc_latest'); ?>"><span class="tico tico-user"></span><?php _e('My Posts', 'tt'); ?></a></li>
                            <li><a href="<?php echo tt_url_for('in_msg'); ?>"><span class="tico tico-envelope"></span><?php _e('My Messages', 'tt'); ?></a></li>
                            <li><a href="<?php echo tt_url_for('my_settings'); ?>"><span class="tico tico-equalizer"></span><?php _e('My Settings', 'tt'); ?></a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?php echo tt_add_redirect(tt_url_for('signout'), tt_get_current_url()); ?>"><span class="tico tico-sign-out"></span><?php _e('Sign Out', 'tt'); ?></a></li>
                        </ul>
                    </li>
                <?php }else{ ?>
                    <li class="login-actions">
                        <a href="<?php echo tt_add_redirect(tt_url_for('signin'), tt_get_current_url()); ?>" class="login-link bind-redirect"><span><?php _e('Sign In or Up', 'tt'); ?></span></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </header>