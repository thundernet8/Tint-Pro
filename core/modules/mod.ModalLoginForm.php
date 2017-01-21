<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/13 23:29
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<!-- 登录弹窗 -->
<form class="form-signin modal fadeScale" id="modalSignBox">
    <h2><?php _e('Sign In', 'tt'); ?></h2>
    <div class="local-signin">
        <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
        <input style="display:none" type="text" name="fakeusernameremembered"/>
        <input style="display:none" type="password" name="fakepasswordremembered"/>
        <div class="form-group input-container clearfix">
            <input autocomplete="off" name="user_login" type="text" class="form-control input text-input" id="user_login-input" title="" required="required" placeholder="<?php _e('Email/Username', 'tt'); ?>">
            <span class="tip"></span>
        </div>
        <div class="form-group input-container clearfix">
            <input autocomplete="new-password" name="password" type="password" class="form-control input password-input" id="password-input" title="" required="required" placeholder="<?php _e('Password', 'tt'); ?>">
            <span class="tip"></span>
        </div>
        <input name="nonce" type="hidden" value="<?php echo wp_create_nonce('page-signin'); ?>">
        <button class="btn btn-info btn-block submit" type="submit"><?php _e('Submit', 'tt'); ?></button>
        <div class="text-center mt20 login-help">
            <a href="<?php echo tt_add_redirect(tt_url_for('signup')); ?>" id="go-register" class="mr20 register-link" rel="link"><?php _e('Register Now', 'tt'); ?></a>
            <span class="dot-separator" role="separator"></span>
            <a href="<?php echo tt_url_for('findpass'); ?>" id="go-findpass" class="ml20 findpass-link" rel="link"><?php _e('Forgot your password?', 'tt'); ?></a>
        </div>
    </div>
    <!-- Open Login -->
    <?php
    $open_weibo = tt_get_option('tt_enable_weibo_login');
    $open_qq = tt_get_option('tt_enable_qq_login');
    $open_weixin = tt_get_option('tt_enable_weixin_login');
    $has_open_login = $open_weibo || $open_qq || $open_weixin;
    ?>
    <?php if($has_open_login) { ?>
        <div class="open-login clearfix">
            <p class="mb20 hidden-xs"><?php _e('SignIn with Social Account', 'tt'); ?></p>
            <div class="social-items">
            <?php if($open_weibo) { ?>
                <a href="<?php echo tt_add_redirect(tt_url_for('oauth_weibo')); ?>" class="btn btn-sn-weibo">
                    <span class="tico tico-weibo"></span>
                </a>
            <?php } ?>
            <?php if($open_qq) { ?>
                <a href="<?php echo tt_add_redirect(tt_url_for('oauth_qq')); ?>" class="btn btn-sn-qq">
                    <span class="tico tico-qq"></span>
                </a>
            <?php } ?>
            <?php if($open_weixin) { ?>
                <a href="<?php echo tt_add_redirect(tt_url_for('oauth_weixin')); ?>" class="btn btn-sn-weixin">
                    <span class="tico tico-weixin"></span>
                </a>
            <?php } ?>
            </div>
        </div>
    <?php } ?>
    <!-- End Open Login -->
</form>