/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/23 21:23
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

import {Routes, Urls} from './globalConfig';
import Utils from './utils';
import {popMsgbox} from './msgbox'

var _body = $('body');
var _baseApiUrl = Routes.shoppingCart;
var _spinner = '<i class="tico tico-spinner spinning"></i>';
var _originSendBtnText = '';
var _productIDInput = $('input[name="product_id"]');
var _quantityInput = $('input[name="quantity"]');
var _sending = false;

var _widgetCartContainerSel = '.widget_shopping_cart>ul';
var _headerCartContainerSel = '.header_shopping_cart>ul';

var _cartItemRemoveSel = '.cart-item .delete';
var _cartItemsClearSel = '.cart-actions .clear-act';
var _cartCheckOutSel = '.cart-actions .check-act';

var _handleAddCart = function (btn) {
    if(_sending || !Utils.checkLogin()) return false;
    
    var productId = parseInt(_productIDInput.val());
    
    if(!productId) return false;
    
    var quantity = Math.abs(parseInt(_quantityInput.val()));
    if(!quantity){
        quantity = 1;
    }
    
    var url = _baseApiUrl + '/' + productId;
    var data = {
        quantity: quantity
    };
    
    var beforeSend = function () {
        if(_sending) return;
        _originSendBtnText = btn.text();
        btn.html(_spinner);
        btn.parent().children('.btn-buy').prop('disabled', true);
        _sending = true;
    };
    
    var finishRequest = function () {
        if(!_sending) return;
        btn.text(_originSendBtnText);
        btn.parent().children('.btn-buy').prop('disabled', false);
        _sending = false;
    };
    
    var success = function (data, textStatus, xhr) {
        finishRequest();
        if(data.success && data.success == 1) {
            popMsgbox.success({
                title: data.message,
                //text: '<a href="' + data.data['chatUrl'] + '">查看对话</a>',
                //html: true,
                showConfirmButton: true
            });
            _updateSidebarCart(data.data);
        }else{
            popMsgbox.error({
                title: data.message,
                timer: 2000,
                showConfirmButton: true
            });
        }
    };
    var error = function (xhr, textStatus, err) {
        finishRequest();
        popMsgbox.error({
            title: xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText,
            timer: 2000,
            showConfirmButton: true
        });
    };
    
    $.post({
        url: url,
        data: Utils.filterDataForRest(data),
        dataType: 'json',
        beforeSend: beforeSend,
        success: success,
        error: error
    });
};

var _handleQuantityInput = function (input, max) {
    var value = Math.abs(parseInt(input.val()));
    value = Math.max(1, Math.min(value, max));
    input.val(value);
};

// 更新边栏购物车
var _updateSidebarCart = function (data) {
    var dom = '';
    var total = 0.00;
    data.forEach(function (item) {
        dom += '<li class="cart-item" data-product-id="' + item.id + '"><a href="' + item.permalink + '" title="' + item.name + '"><img class="thumbnail" src="' + item.thumb + '"><span class="product-title">' + item.name + '</span></a><div class="price"><i class="tico tico-cny"></i>' + item.price + ' x ' + item.quantity + '</div><i class="tico tico-close delete"></i></li>';
        total += parseFloat(item.price) * parseInt(item.quantity);
    });
    dom += '<div class="cart-amount">合计: <i class="tico tico-cny"></i><span>' + total + '</span></div>';
    
    var widgetContainer = $(_widgetCartContainerSel);
    var headerContainer = $(_headerCartContainerSel);
    var parent;
    if(widgetContainer.length){
        widgetContainer.html(dom);
        parent = widgetContainer.parent();
        parent.addClass(parent.hasClass('active') ? '':'active');
    }
    if(headerContainer.length){
        headerContainer.html(dom);
        parent = headerContainer.parent();
        parent.addClass(parent.hasClass('active') ? '':'active');
    }
};


var _initQuantityInput = function () {
    var amountInput = $('input[name="product_amount"]');
    var maxLimit = amountInput ? Math.abs(parseInt(amountInput.val())) : 1;
    _body.on('input', 'input[name="quantity"]', function () {
        _handleQuantityInput($(this), maxLimit);
    });
};


//
var _initImmediatelyBuy = function (btn) {
    //TODO
};


// 已售完, 点击提示消息
var _initSoldOutNotice = function (btn) {
    popMsgbox.info({
        title: btn.data('msg-title'),
        text: btn.data('msg-text'),
        timer: 2000,
        showConfirmButton: true
    });
};


// 添加至购物车或立即购买(跳转至填写订单页面)
var _initAddCartOrImmediatelyBuy = function () {
    _body.on('click', '.btn-buy', function () {
        var btn = $(this);
        if(btn.data('buy-action') == 'addcart'){
            _handleAddCart(btn);
        }else if(btn.data('buy-action') == 'checkout'){
            _initImmediatelyBuy(btn);
        }else{
            _initSoldOutNotice(btn);
        }
    });
};

// 删除购物车项目
var _initCartItemRemove = function () {
    _body.on('click', _cartItemRemoveSel, function () {
        _handleCartItemRemove($(this).parent());
    });
};

var _handleCartItemRemove = function (item) {
    var productId = item.data('product-id');
    // item.remove();
    // var _headerCartContainer = $(_headerCartContainerSel);
    // var _widgetCartContainer = $(_widgetCartContainerSel);
    // if(_headerCartContainer){
    //     _headerCartContainer.children('li').each(function (index) {
    //         var $this = $(this);
    //         if($this.data('product-id') == productId){
    //             $this.remove();
    //         }
    //     });
    //     if(_headerCartContainer.children('li').length < 1){
    //         _headerCartContainer.removeClass('active');
    //     }
    // }
    // if(_widgetCartContainer){
    //     _widgetCartContainer.children('li').each(function (index) {
    //         var $this = $(this);
    //         if($this.data('product-id') == productId){
    //             $this.remove();
    //         }
    //     });
    //     if(_widgetCartContainer.children('li').length < 1){
    //         _widgetCartContainer.removeClass('active');
    //     }
    // }
    
    var url = Routes.shoppingCart + '/' + productId;
    var data = {
        
    };
    var success = function (data, textStatus, xhr) {
        if(data.success && data.success == 1) {
            _updateSidebarCart(data.data);
        }
    };
    $.post({
        url: url + '?' + $.param(Utils.filterDataForRest(data)),
        type: 'DELETE',
        dataType: 'json',
        //beforeSend: beforeSend,
        success: success,
        //error: error
    });
};

// 清空购物车
var _initCartItemsRemove = function () {
    _body.on('click', _cartItemsClearSel, function () {
        _handleCartItemsRemove();
    });
};

var _handleCartItemsRemove = function () {
    var _headerCartContainer = $(_headerCartContainerSel);
    var _widgetCartContainer = $(_widgetCartContainerSel);
    if(_headerCartContainer){
        _headerCartContainer.html('').parent().removeClass('active');
    }
    if(_widgetCartContainer){
        _widgetCartContainer.html('').parent().removeClass('active');
    }
    
    var url = Routes.shoppingCart;
    var data = {
        
    };
    $.post({
        url: url + '?' + $.param(Utils.filterDataForRest(data)),
        type: 'DELETE',
        dataType: 'json',
        //beforeSend: beforeSend,
        success: success,
        //error: error
    });
};

// 结算购物车
var _initCartCheckOut = function () {
    _body.on('click', _cartCheckOutSel, function () {
        location.href = Urls.cartCheckOut;
    });
};

var _initCartHandle = function () {
    _initCartItemRemove();
    _initCartItemsRemove();
    _initCartCheckOut();
};

var Buy = {
    initQuantityInput: _initQuantityInput,
    initAddCartOrImmediatelyBuy: _initAddCartOrImmediatelyBuy,
    initCartHandle: _initCartHandle
};

export default Buy;