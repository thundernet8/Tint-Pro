<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/04 15:18
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

wp_no_robots();


// 提供邮箱输入框
// JS GET /api/v1/users/email:[base64 encode的email]?act=findpass
// 404表示邮箱不存在
// 200以及用户信息则表示ok并后台发送重置链接至邮箱

tt_get_header('simple');

?>

<body class="action action-page findpass">
    <div class="wrapper container no-aside">
        <div class="main inner-wrap">
            <form class="form-findpass">
                <h2 class="form-findpass-heading"><?php _e('Please input your account associated email', 'tt'); ?></h2>
                <div class="input-group">
                    <input type="email" id="inputEmail" class="form-control" placeholder="<?php _e('Email', 'tt'); ?>" required="required">
                    <span class="input-group-btn">
                        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php _e('Submit', 'tt'); ?></button>
                    </span>
                </div>
            </form>
        </div>
    </div>

<?php

tt_get_footer('simple');
