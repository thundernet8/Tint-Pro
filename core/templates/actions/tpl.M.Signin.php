<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/28 15:45
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

// 如果已经登录
if(is_user_logged_in()) {
    if ( isset( $_REQUEST['redirect'] ) || isset( $_REQUEST['redirect_to'] ) ){
        $redirect_to = isset($_REQUEST['redirect']) ?  $_REQUEST['redirect'] : $_REQUEST['redirect_to'];
    } else {
        $redirect_to = '/';
    }
    wp_safe_redirect($redirect_to);
    exit;
}

// 引入头部
tt_get_header('simple');
?>
<body class="is-loadingApp action-page signin">
    <div class="loading-line"></div>
    <header class="header simple-header">
        <nav id="header-nav" class="navigation container clearfix" role="navigation">

        </nav>
    </header>
	<div class="bg-layer" id="bg-layer"></div>
    <div id="content" class="wrapper container no-aside">
        <div class="main inner-wrap">
			<!-- Logo -->
			<img class="logo" src="<?php echo tt_get_option('tt_small_logo'); ?>" alt="<?php echo get_bloginfo('name'); ?>">
            <form class="form-signin">
                <div class="local-signin">
                    <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
                    <input style="display:none" type="text" name="fakeusernameremembered"/>
                    <input style="display:none" type="password" name="fakepasswordremembered"/>
					<div class="input-container clearfix">
						<input autocomplete="off" name="user_login" type="text" class="input text-input" id="user_login-input" title="Account" required="required" placeholder="<?php _e('Email/Username', 'tt'); ?>">
					</div>
                    <div class="input-container clearfix">
<!--                        <input autocomplete="off" name="password" type="text" class="input password-input" id="password-input" title="Password" required="required" onclick="this.type='password'" placeholder="--><?php //_e('Password', 'tt'); ?><!--">-->
                        <input autocomplete="new-password" name="password" type="password" class="input password-input" id="password-input" title="Password" required="required" placeholder="<?php _e('Password', 'tt'); ?>">
                        <span class="indicator spinner tico tico-spinner3"></span>
                    </div>
					<input name="nonce" type="hidden" value="<?php echo wp_create_nonce('page-signin'); ?>">
                    <!--span class="input-group-btn">
                        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php _e('Submit', 'tt'); ?></button>
                    </span-->
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
					<p class="text-white mt10 mr10 pull-left hidden-xs"><?php _e('Quick SignIn', 'tt'); ?></p>
					<?php if($open_weibo) { ?>
					<a href="<?php echo tt_add_redirect(tt_url_for('oauth_weibo')); ?>" class="btn btn-lg btn-sn-weibo pull-left anchor-noborder">
						<span class="tico tico-weibo"></span>
						<strong class="visible-xs-inline"><?php _e('Signin with Sina weibo', 'tt'); ?></strong>
					</a>
					<?php } ?>
					<?php if($open_qq) { ?>
					<a href="<?php echo tt_add_redirect(tt_url_for('oauth_qq')); ?>" class="btn btn-lg btn-sn-qq pull-left anchor-noborder">
						<span class="tico tico-qq"></span>
						<strong class="visible-xs-inline"><?php _e('Signin with QQ', 'tt'); ?></strong>
					</a>
					<?php } ?>
					<?php if($open_weixin) { ?>
					<a href="<?php echo tt_add_redirect(tt_url_for('oauth_weixin')); ?>" class="btn btn-lg btn-sn-weixin pull-left anchor-noborder">
						<span class="tico tico-weixin"></span>
						<strong class="visible-xs-inline"><?php _e('Signin with Wechat', 'tt'); ?></strong>
					</a>
					<?php } ?>
				</div>
				<?php } ?>
				<!-- End Open Login -->
				<div class="text-center mt30 login-help">
					<a href="<?php echo tt_add_redirect(tt_url_for('signup')); ?>" id="go-register" class="mr20 register-link" rel="link"><?php _e('Register Now', 'tt'); ?></a>
					<span class="dot-separator" role="separator"></span>
					<a href="<?php echo tt_url_for('findpass'); ?>" id="go-findpass" class="ml20 findpass-link" rel="link"><?php _e('Forgot your password?', 'tt'); ?></a>
				</div>
            </form>
        </div>
    </div>
<?php

// 引入页脚
tt_get_footer('simple');
if(isset($_GET['registration']) && $_GET['registration']==='disabled') { ?>
	<script>
		<!-- Remind registration disabled -->
		jQuery(function () {
			App.PopMsgbox.alert({
				title: "<?php _e('The manager has disabled the new registration, please sign in if you already have a account', 'tt'); ?>",
				timer: 6000
			});
		});
	</script>
<?php }