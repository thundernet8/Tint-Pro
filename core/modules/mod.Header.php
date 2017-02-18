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
 * @link https://webapproach.net/tint.html
 */
?>
<?php load_mod('mod.Head'); ?>
<body <?php body_class('is-loadingApp'); ?>>
    <div class="loading-line"></div>
    <?php if(is_home() || (is_single() && in_array(get_post_type(), array('post', 'product', 'page')))) { ?>
    <!-- 顶部公告 -->
    <?php load_mod('mod.HomeBulletins'); ?>
    <?php } ?>
    <header class="header common-header white">
        <input type="checkbox" id="menustate" class="menustate hide">
        <nav id="header-nav" class="navigation container clearfix" role="navigation">
            <!-- Menu Icon -->
            <li class="menuicon visible-xs-block">
                <label class="menuicon-label" for="menustate" aria-hidden="true">
					<span class="menuicon-bread menuicon-bread-top">
						<span class="menuicon-bread-crust menuicon-bread-crust-top"></span>
					</span>
                    <span class="menuicon-bread menuicon-bread-bottom">
						<span class="menuicon-bread-crust menuicon-bread-crust-bottom"></span>
					</span>
                </label>
<!--                <a href="#menustate" class="menuanchor menuanchor-open" id="menuanchor-open">-->
<!--                    <span class="menuanchor-label">Open Menu</span>-->
<!--                </a>-->
<!--                <a href="#" class="menuanchor menuanchor-close" id="menuanchor-close">-->
<!--                    <span class="menuanchor-label">Close Menu</span>-->
<!--                </a>-->
            </li>
            <!-- Logo -->
            <a class="logo nav-col" href="<?php echo home_url(); ?>" title="<?php echo get_bloginfo('name'); ?>">
                <img src="<?php echo tt_get_option('tt_logo'); ?>" alt="<?php echo get_bloginfo('name'); ?>">
            </a>
            <!-- Top Menu -->
            <?php wp_nav_menu( array( 'theme_location' => 'header-menu', 'container' => '', 'menu_id'=> 'header-menu', 'menu_class' => 'header-menu nav-col', 'depth' => '2', 'fallback_cb' => false  ) ); ?>
            <!-- End Top Menu -->
            <!-- Header Right Tools -->
            <ul class="header-tool-menu nav-col pull-right">
                <li class="nav-search"><a href="javascript:void(0)" data-toggle="modal" data-target="#globalSearch" data-backdrop="1"><span class="tico tico-search"></span></a></li>
                <?php $user = wp_get_current_user(); ?>
                <?php if($user && $user->ID) { ?>
                    <?php $unread = tt_count_pm_cached($user->ID, 0, MsgReadStatus::UNREAD); ?>
                    <li class="nav-user dropdown">
                        <a href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php if($unread) { ?><i class="badge"></i><?php } ?>
                            <img src="<?php echo tt_get_avatar($user->ID, 'small'); ?>" class="avatar">
                            <span class="username"><?php echo $user->display_name; ?></span>
                            <span class="tico-angle-down"></span>
                        </a>
                        <ul class="nav-user-menu dropdown-menu">
                            <?php if(current_user_can('edit_users')) { ?>
                            <li><a href="<?php echo get_dashboard_url(); ?>"><span class="tico tico-meter"></span><?php _e('Go Dashboard', 'tt'); ?></a></li>
                            <li><a href="<?php echo tt_url_for('manage_home'); ?>"><span class="tico tico-list-small-thumbnails"></span><?php _e('Site Management', 'tt'); ?></a></li>
                            <?php } ?>
                            <li><a href="<?php echo tt_url_for('new_post'); ?>"><span class="tico tico-quill"></span><?php _e('New Post', 'tt'); ?></a></li>
                            <li><a href="<?php echo tt_url_for('uc_latest', $user); ?>"><span class="tico tico-stack-overflow"></span><?php _e('My Posts', 'tt'); ?></a></li>
                            <li><a href="<?php echo tt_url_for('in_msg'); ?>"><span class="tico tico-envelope"></span><?php _e('My Messages', 'tt'); ?><?php if($unread){printf('<i style="position: absolute;margin-left: 5px;">(%d)</i>', intval($unread));} ?></a></li>
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