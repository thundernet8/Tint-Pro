/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/10 18:21
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

import {handleLineLoading} from './modules/loading';
import {popMsgbox} from './modules/msgbox';
import {} from './modules/bootstrap-flat';
import postCommentsKit from './modules/comments';
import postStarKit from './modules/postStar';
import ScrollHandler from './modules/scroll';
import AnimateAnchor from './modules/animateAnchor';
import {} from 'lightbox2';
import FollowKit from './modules/follow';
import Pmkit from './modules/pm';
import ModalSignBox from './modules/modalSignBox';
import {} from 'lazyload/jquery.lazyload';
import SignHelp from './modules/signHelp';
import Referral from './modules/referral';
import FixFooter from './modules/fixFooter';

// DOM Ready
jQuery(document).ready(function ($) {
    // 隐藏加载条
    handleLineLoading();
    
    // 初始化popMsgbox
    popMsgbox.init();
    
    // 初始化文章点赞
    postStarKit.init();
    
    // 评论框初始化
    postCommentsKit.init();
    
    // 滚动顶部底部
    ScrollHandler.initScrollTo();
    
    // 浮动边栏
    ScrollHandler.initFloatWidget();
    
    // 粉丝和关注
    FollowKit.init();
    
    // 私信
    Pmkit.initModalPm();
    
    // 登录弹窗
    ModalSignBox.init();
    
    // 登录界面显示方式
    SignHelp.init();
    
    //  Lightbox 获取图片的title属性
    $('.lightbox-gallery').each(function () {
        var item = $(this);
        var img = item.find('img');
        if(img && img.attr('title')) {
            item.attr('data-title', img.attr('title'));
        }
    });
    
    // 延迟加载图片
    $('img.lazy').lazyload({
        effect: "fadeIn",
        threshold: 50
    });
    $('.sidebar img.lazy').lazyload({
        effect: "fadeIn",
        threshold: 0
    });
    
    // 平滑锚点
    AnimateAnchor();
    
    // 修正Footer位置
    FixFooter();
    
    // 设置推广信息cookie, 便于后面使用
    Referral.init();
});
 