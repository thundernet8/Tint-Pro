<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/04 19:12
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

wp_no_robots();

if(!isset($_GET['key']) || empty($_GET['key']) || !tt_verify_reset_password_link($_GET['key'])) {
    wp_die(__('The link you visited is invalid or expired', 'tt'), __('Link Error', 'tt'), array('response' => tt_rest_authorization_required_code()));
}


// 链接有效提供新密码输入框
// JS PUT /api/v1/users/key:[key]
// 400 表示更新失败
// 200 表示成功并返回用户信息

tt_get_header('simple');

?>

<body class="action action-page resetpass">
    <div class="wrapper container no-aside">
        <div class="main inner-wrap">
            <form class="form-resetpass">
                <h2 class="form-resetpass-heading"><?php _e('Please input your new password', 'tt'); ?></h2>
                <label for="inputPassword" class="sr-only"><?php _e('Password', 'tt'); ?></label>
                <input type="password" id="inputPassword" class="form-control" placeholder="<?php _e('Password', 'tt'); ?>" required="required">
                <label for="inputPassword2" class="sr-only"><?php _e('Repeat Password', 'tt'); ?></label>
                <input type="password" id="inputPassword2" class="form-control" placeholder="<?php _e('Password', 'tt'); ?>" required="required">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="remember-me"> <?php _e('Remember me', 'tt'); ?>
                    </label>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit"><?php _e('Submit', 'tt'); ?></button>
            </form>
        </div>
    </div>

<?php

tt_get_footer('simple');
