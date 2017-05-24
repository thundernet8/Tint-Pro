/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/31 23:39
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';

import {handleLineLoading} from './modules/loading';
import {popMsgbox, msgbox} from './modules/msgbox';
import {pageSignIn} from './modules/signin';
import {pageSignUp} from './modules/signup';
import {handleSeasonalBg} from './modules/seasonalBg';
import FindPass from './modules/findPass';
import ResetPass from './modules/resetPass';

// DOM Ready
jQuery(document).ready(function ($) {
    // 隐藏加载条
    handleLineLoading();

    // 初始化popMsgbox
    popMsgbox.init();

    // 初始化msgbox
    msgbox.init();

    var body = $('body');
    if(body.hasClass('signin')) {
        // 为登录页设置可变背景
        handleSeasonalBg($('#bg-layer'));

        // 初始化登录处理
        pageSignIn.init();
    }
    
    if(body.hasClass('signup')) {
        // 初始化注册表单处理
        pageSignUp.init();
    }

    // 找回密码(发送重置链接)
    FindPass.init();
    
    // 重置密码
    ResetPass.init();
});
