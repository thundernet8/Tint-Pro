/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/27 22:21
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';

import {handleLineLoading} from './modules/loading';
import {popMsgbox, msgbox} from './modules/msgbox';
import ScrollHandler from './modules/scroll';
import {} from './modules/bootstrap-flat';
import Checkout from './modules/checkout';
import FixFooter from './modules/fixFooter';
import BuyResource from './modules/buyResource';

// DOM Ready
jQuery(document).ready(function ($) {
    // 隐藏加载条
    handleLineLoading();
    
    // 初始化popMsgbox
    popMsgbox.init();
    
    // 初始化msgbox
    //msgbox.init();
    
    // 滚动顶部底部
    ScrollHandler.initScrollTo();

    // 确认订单详情和结算(输入地址信息、应用优惠码等)
    Checkout.init();
    
    // 修正Footer位置
    FixFooter();
    
    // 初始化购买文章内资源请求handler
    BuyResource.init();
});