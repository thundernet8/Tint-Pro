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
import postCommentsKit from './modules/comments';
import postStarKit from './modules/postStar';
import ScrollHandler from './modules/scroll';
import AnimateAnchor from './modules/animateAnchor';
import {} from 'lightbox2';

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
    
    // 分享条自适应位置
    ScrollHandler.initShareBar();
    
    // 浮动边栏
    ScrollHandler.initFloatWidget();
    
    //  Lightbox 获取图片的title属性
    $('.lightbox-gallery').each(function () {
        var item = $(this);
        var img = item.find('img');
        if(img && img.attr('title')) {
            item.attr('data-title', img.attr('title'));
        }
    });
    
    // 平滑锚点
    AnimateAnchor();
});