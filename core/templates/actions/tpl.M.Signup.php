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
 * @link https://webapproach.net/tint.html
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
    <?php load_template(THEME_MOD . '/mod.LogoHeader.php'); ?>
    <div id="content" class="wrapper container no-aside">
        <div class="main inner-wrap">
            <form class="form-signup">
                <h2 class="title signup-title mb30"><?php _e('Create Account', 'tt'); ?></h2>
<!--                <div class="msg"></div>-->
                <p id="default-tip"><?php _e('We will send you an email including a activation link to help to complete the registration steps.', 'tt'); ?></p>
                <?php
                    $open_weibo = tt_get_option('tt_enable_weibo_login');
                    $open_qq = tt_get_option('tt_enable_qq_login');
                    $open_weixin = tt_get_option('tt_enable_weixin_login');
                    $has_open_login = $open_weibo || $open_qq || $open_weixin;
                ?>
                <div class="local-signup">
                    <div class="input-container clearfix">
                        <input autofocus="" name="username" type="text" class="input text-input form-control" id="user_login-input" title="" placeholder="<?php _e('Account', 'tt'); ?>" required="required">
                    </div>
                    <div class="input-container clearfix mt10">
                        <input autofocus="" name="email" type="email" class="input email-input form-control" id="email-input" title="" placeholder="<?php _e('Email', 'tt'); ?>" required="required">
                    </div>
                    <div class="input-container clearfix mt10">
                        <input autocomplete="new-password" name="password" type="password" class="input password-input form-control" id="password-input" title="" placeholder="<?php _e('Password', 'tt'); ?>" required="required">
                    </div>
                    <div class="input-container clearfix mt10">
                        <div class="pull-left">
                            <input autofocus="" name="captcha" type="text" class="input text-input form-control" id="captcha-input" title="" placeholder="<?php _e('Captcha', 'tt'); ?>" required="required">
                            <span class="status-icon"></span><!-- TODO .b64_right .b64_error -->
                        </div>
                        <div class="pull-left captcha-wrap">
                            <img class="captcha" src="<?php echo add_query_arg('t', str_replace(' ', '_', microtime()), tt_url_for('captcha')); ?>" title="<?php _e('Click to refresh', 'tt'); ?>" alt="Captcha">
                        </div>
                    </div>
                    <input name="nonce" type="hidden" value="<?php echo wp_create_nonce('page-signup'); ?>">
                    <input name="step" type="hidden" value=1>
                    <button class="btn btn-primary mt20 mb20" id="signup-btn" disabled><!--span class="indicator spinner tico tico-spinner3"></span--><?php _e('Sign Up', 'tt'); ?></button>
                </div>
                <?php if($has_open_login) { ?>
                    <!-- Open Login -->
                    <div class="open-login clearfix mt10 mb10">
                        <p class="text-white mt10 mr10 pull-left hidden-xs"><?php _e('Quick SignIn', 'tt'); ?></p>
                        <?php if($open_weibo) { ?>
                            <a href="<?php echo tt_add_redirect(tt_url_for('oauth_weibo')); ?>" class="btn btn-lg btn-sn-weibo pull-left anchor-noborder">
                                <span class="tico tico-weibo"></span>
                                <strong class="visible-xs-inline"><?php _e('Signin with Sina weibo'); ?></strong>
                            </a>
                        <?php } ?>
                        <?php if($open_qq) { ?>
                            <a href="<?php echo tt_add_redirect(tt_url_for('oauth_qq')); ?>" class="btn btn-lg btn-sn-qq pull-left anchor-noborder">
                                <span class="tico tico-qq"></span>
                                <strong class="visible-xs-inline"><?php _e('Signin with QQ'); ?></strong>
                            </a>
                        <?php } ?>
                        <?php if($open_weixin) { ?>
                            <a href="<?php echo tt_add_redirect(tt_url_for('oauth_weixin')); ?>" class="btn btn-lg btn-sn-weixin pull-left anchor-noborder">
                                <span class="tico tico-weixin"></span>
                                <strong class="visible-xs-inline"><?php _e('Signin with Wechat'); ?></strong>
                            </a>
                        <?php } ?>
                    </div>
                    <!-- End Open Login -->
                <?php } ?>
                <div class="note">
                    <p class="login-note"><?php _e('Already have an account? ', 'tt'); ?><a class="login-link" id="go-login" href="<?php echo tt_add_redirect(tt_url_for('signin')); ?>" rel="link"><?php _e('Sign In', 'tt'); ?></a></p>
                    <!-- Terms -->
                    <p class="terms-note"><?php echo sprintf(__('* By signing up, you agree to our <a href="%s" target="_blank"><strong>「Terms of Use, Privacy Policy」</strong></a> and to receive emails, newsletters &amp; updates.', 'tt'), tt_url_for('privacy')); ?></p>
                </div>
            </form>
        </div>
    </div>
<?php

// 引入页脚
tt_get_footer('simple');