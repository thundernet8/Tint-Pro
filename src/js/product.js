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
import FollowKit from './modules/follow';
import Pmkit from './modules/pm';
import ModalSignBox from './modules/modalSignBox';
import {} from 'lazyload/jquery.lazyload';
import SignHelp from './modules/signHelp';
import FixFooter from './modules/fixFooter';
import Toggle from './modules/toggle';
import {} from './modules/cookie';
import Usermeta from './modules/usermeta';
import Buy from './modules/buy';

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
    //ScrollHandler.initShareBar();
    
    // 浮动边栏
    //ScrollHandler.initFloatWidget();
    
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
    $('#primary img.lazy').lazyload({
        effect: "fadeIn",
        threshold: 50
    });
    $('#secondary img.lazy').lazyload({
        effect: "fadeIn",
        threshold: 0
    });
    $('#tertiary img.lazy').lazyload({
        effect: "fadeIn",
        threshold: 0
    });
    
    // 平滑锚点
    AnimateAnchor(120, false); // 顶部保留120px高度,不显示hash值在url中
    
    // 修正Footer位置
    FixFooter();
    
    // 折叠左菜单
    Toggle.initShopLeftMenuToggle();
    
    // Tab Show
    var hash = location.hash;
    if(hash=='#tab-description' || hash=='#tab-reviews') {
        $('a[data-target="' + hash + '"]').tab('show');
    }
    $('a[href="#reviews"]').on('click', function () {
        $('a[data-target="#tab-reviews"]').tab('show');
    });
    
    // 设置浏览历史至cookie
    var viewHistory = $.cookie('tt_view_product_history');
    if(viewHistory && viewHistory.length) {
        var pids = viewHistory.split('_');
        var index = viewHistory.indexOf(TT.pid);
        if(index > -1){
            pids.splice(index, 1);
        }
        pids.unshift(TT.pid);
        var newViewHistory = pids.join('_');
        $.cookie('tt_view_product_history', newViewHistory, {expires: 365, path: '/'});
        if(viewHistory != newViewHistory) {
            Usermeta.updateMeta('tt_view_product_history', newViewHistory);
        }
    }else{
        $.cookie('tt_view_product_history', TT.pid, {expires: 365, path: '/'});
        Usermeta.updateMeta('tt_view_product_history', TT.pid);
    }
    
    // 同步购物车数据
    
    
    // 购买动作
    Buy.initAddCartOrImmediatelyBuy();
    Buy.initQuantityInput();
    Buy.initCartHandle();
});