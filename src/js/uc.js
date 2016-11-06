/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/12 21:29
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

import {handleLineLoading} from './modules/loading';
import {popMsgbox} from './modules/msgbox';
import {} from './modules/bootstrap-flat';
import ScrollHandler from './modules/scroll';
import FollowKit from './modules/follow';
import Pmkit from './modules/pm';
import ModalSignBox from './modules/modalSignBox';
import {} from 'lazyload/jquery.lazyload';
import SignHelp from './modules/signHelp';
import FixFooter from './modules/fixFooter';


// DOM Ready
jQuery(document).ready(function ($) {
    // 隐藏加载条
    handleLineLoading();
    
    // 初始化popMsgbox
    popMsgbox.init();
    
    // 滚动顶部底部
    ScrollHandler.initScrollTo();
    
    // 粉丝和关注
    FollowKit.init();
    
    // 私信
    Pmkit.initModalPm();
    
    // 登录弹窗
    ModalSignBox.init();
    
    // 登录界面显示方式
    SignHelp.init();
    
    // 延迟加载图片
    $('img.lazy').lazyload({
        effect: "fadeIn",
        threshold: 50
    });
    
    // 修正Footer位置
    FixFooter();
});