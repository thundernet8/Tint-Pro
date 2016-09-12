<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/28 15:46
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

wp_no_robots();
if ( !get_option('users_can_register') ) {
	wp_safe_redirect( add_query_arg('registration', 'disabled', tt_url_for('signin')));
	exit();
}

// 引入头部
tt_get_header('simple');
?>
<body class="is-loadingApp action-page signup">
    <header class="header simple-header">
        <nav id="header-nav" class="navigation container clearfix" role="navigation">
            <!-- Logo -->
            <a class="logo nav-col" href="<?php echo home_url(); ?>" title="<?php echo get_bloginfo('name'); ?>">
                <img src="<?php echo tt_get_option('tt_logo'); ?>" alt="<?php echo get_bloginfo('name'); ?>">
            </a>
        </nav>
    </header>
    <div class="wrapper container no-aside">
        <div class="main inner-wrap">
            <form class="form-signup">
                <div class="title signup-title"><?php _e('Sign Up', 'tt'); ?></div>
                <div class="already-user">
                    <?php _e('Already have an account? '); ?>
                    <a class="login-link" id="go-login" href="<?php echo tt_url_for('signin'); ?>"><?php _e('Sign In'); ?></a>
                </div>
                <?php
                    $open_weibo = tt_get_option('tt_enable_weibo_login');
                    $open_qq = tt_get_option('tt_enable_qq_login');
                    $open_weixin = tt_get_option('tt_enable_weixin_login');
                    $has_open_login = $open_weibo || $open_qq || $open_weixin;
                ?>
                <div class="signup-section <?php echo $has_open_login ? 'two-col' : 'one-col'; ?>">
                    <div class="local-signup">
                        <div class="input-container clearfix">
                            <label class="label"><?php _e('Account', 'tt'); ?></label>
                            <input autofocus="" name="user_login" type="text" class="input text-input" id="user_login-input" title="Account" required="required">
                            <div class="focus-line"></div>
                        </div>
                        <div class="input-container clearfix">
                            <label class="label"><?php _e('Email', 'tt'); ?></label>
                            <input autofocus="" name="email" type="email" class="input email-input" id="email-input" title="Email" required="required">
                            <div class="focus-line"></div>
                        </div>
                        <div class="input-container clearfix">
                            <label class="label"><?php _e('Password', 'tt'); ?></label>
                            <input autofocus="" name="password" type="password" class="input password-input" id="password-input" title="Password" required="required">
                            <div class="focus-line"></div>
                        </div>
                        <div class="input-container clearfix">
                            <div class="pull-left">
                                <label class="label"><?php _e('Captcha', 'tt'); ?></label>
                                <input autofocus="" name="captcha" type="text" class="input text-input" id="captcha-input" title="Captcha" required="required">
                                <div class="focus-line"></div>
                            </div>
                            <div class="pull-left captcha-wrap">
                                <img class="captcha" src="" title="<?php _e('Click to refresh', 'tt'); ?>" alt="Captcha"> <!-- TODO captcha -->
                            </div>
                        </div>
                        <button class="btn btn-primary btn-wide" id="signup-btn"><?php _e('Sign Up', 'tt'); ?></button>
                    </div>
                    <?php if($has_open_login) { ?>
                    <div class="divider vertical-divider"><span>OR</span></div>
                    <!-- Open Login -->
                    <div class="open-login">
                        <div class="open-login-title"><?php _e('Sign in using your social account', 'tt'); ?></div>
                        <?php if($open_weibo) { ?>
                        <a href="<?php echo tt_add_redirect(tt_url_for('oauth_weibo'), tt_get_current_url()); ?>" class="btn btn-default btn-sm btn-sn-weibo">
                            <button class="long-social-btn icon-and-text">
                                <span class="tico tico-weibo"></span>
                                <span class="signin-btn-text"><?php _e('Signin with Sina weibo'); ?></span>
                            </button>
                        </a>
                        <?php } ?>
                        <?php if($open_qq) { ?>
                        <a href="<?php echo tt_add_redirect(tt_url_for('oauth_qq'), tt_get_current_url()); ?>" class="btn btn-default btn-sm btn-sn-qq">
                            <button class="long-social-btn icon-and-text">
                                <span class="tico tico-qq"></span>
                                <span class="signin-btn-text"><?php _e('Signin with QQ'); ?></span>
                            </button>
                        </a>
                        <?php } ?>
                        <?php if($open_weixin) { ?>
                        <a href="<?php echo tt_add_redirect(tt_url_for('oauth_weixin'), tt_get_current_url()); ?>" class="btn btn-default btn-sm btn-sn-weixin">
                            <button class="long-social-btn icon-and-text">
                                <span class="tico tico-weixin"></span>
                                <span class="signin-btn-text"><?php _e('Signin with Wechat'); ?></span>
                            </button>
                        </a>
                        <?php } ?>
                    </div>
                    <!-- End Open Login -->
                    <?php } ?>
                </div>
                <!-- Terms -->
                <div class="terms-section"><?php echo sprintf('* By signing up, you agree to our <a href="%s" target="_blank">Terms of Use, Privacy Policy</a> and to receive emails, newsletters &amp; updates.', tt_url_for('privacy')); ?></div>
            </form>
        </div>
    </div>
<?php

// 引入页脚
tt_get_footer('simple');