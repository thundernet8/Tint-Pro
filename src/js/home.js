/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/31 23:38
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

import {handleLineLoading} from './modules/loading';
import {popMsgbox} from './modules/msgbox';
import {} from './modules/bootstrap-flat';
import {} from './vender/unslider';
import ScrollHandler from './modules/scroll';
import ModalSignBox from './modules/modalSignBox';
import {} from 'lazyload/jquery.lazyload';
import SignHelp from './modules/signHelp';
//import Scrollbar from 'perfect-scrollbar/jquery';
// require('./modules/smooth-scroll');
import Referral from './modules/referral';

// DOM Ready
jQuery(document).ready(function ($) {
    // 隐藏加载条
    handleLineLoading();

    // 初始化popMsgbox
    popMsgbox.init();
    
    // 滚动顶部底部
    ScrollHandler.initScrollTo();
    
    // 初始化Scrollbar - 对body 无效
    //Scrollbar(jQuery);
    //$('body').perfectScrollbar();
    
    // 登录弹窗
    ModalSignBox.init();
    
    // 登录界面显示方式
    SignHelp.init();
    
    // 启动幻灯
    (function () {
        if(window.TT && TT.isHome) {
            $('.slides-wrap').unslider({
                autoplay: true,
                animation: 'horizontal', //horizontal//vertical//fade
                animateHeight: false,
                delay: 6000,
                arrows: false,
                infinite: true,
                keys: {
                    prev: 37,
                    next: 39,
                    stop: 27 //  Example: pause when the Esc key is hit
                }
            });
        }
    })();

    // 延迟加载图片
    $('img.lazy').lazyload({
        effect: "fadeIn",
        threshold: 50
    });
    $('.sidebar img.lazy').lazyload({
        effect: "fadeIn",
        threshold: 0
    });
    
    // 设置推广信息cookie, 便于后面使用
    Referral.init();
});
