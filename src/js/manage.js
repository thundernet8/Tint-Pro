/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/07 22:42
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';


import {handleLineLoading} from './modules/loading';
import {popMsgbox} from './modules/msgbox';
import {} from './modules/bootstrap-flat';
import ScrollHandler from './modules/scroll';
import ModalSignBox from './modules/modalSignBox';
import {} from 'lazyload/jquery.lazyload';
import SignHelp from './modules/signHelp';
import FixFooter from './modules/fixFooter';
import DeleteOrder from './modules/deleteOrder';
import ManagePosts from './modules/managePosts';
import ManageProducts from './modules/manageProducts';
import AddCoupon from './modules/addCoupon';
import DeleteCoupon from './modules/deleteCoupon';
import AddMember from './modules/addMember';
import DeleteMember from './modules/deleteMember';
import ManageOrderStatus from './modules/manageOrderStatus';
import PromoteVip from './modules/promoteVip';
import AddCredits from './modules/addCredits';

// DOM Ready
jQuery(document).ready(function ($) {
    // 隐藏加载条
    handleLineLoading();
    
    // 初始化popMsgbox
    popMsgbox.init();
    
    // 滚动顶部底部
    ScrollHandler.initScrollTo();
    
    // 登录弹窗
    ModalSignBox.init();
    
    // 登录界面显示方式
    SignHelp.init();
    
    // 延迟加载图片
    $('img.lazy').lazyload({
        //effect: "fadeIn",
        threshold: 50,
        load: function () {
            $(this).addClass('show');
        }
    });
    
    // 修正Footer位置
    FixFooter();
    
    // 删除订单
    DeleteOrder.init();
    
    // 文章管理
    ManagePosts.init();
    
    // 商品管理
    ManageProducts.init();
    
    // 添加优惠码
    AddCoupon.init();
    
    // 删除优惠码
    DeleteCoupon.init();
    
    // 添加会员
    AddMember.init();
    
    // 删除会员
    DeleteMember.init();
    
    // 管理订单状态
    ManageOrderStatus.init();
    
    // 提升会员
    PromoteVip.init();
    
    // 添加积分
    AddCredits.init();
});