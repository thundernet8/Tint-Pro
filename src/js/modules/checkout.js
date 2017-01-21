/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/30 20:27
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';


import {Routes, Urls} from './globalConfig';
import Utils from './utils';
import {popMsgbox} from './msgbox'

var _body = $('body');

var _memoTextarea = $('#memo-textarea');
var _addressListSel = '.address-list>li';
var _addressListActiveSel = '.address-list>li.active';
var _receiverNameInput = $('input[name="receiver-name"]');
var _receiverEmailInput = $('input[name="receiver-email"]');
var _receiverPhoneInput = $('input[name="receiver-phone"]');
var _receiverAddrInput = $('input[name="receiver-address"]');
var _receiverZipInput = $('input[name="receiver-zip"]');

var _addNewAddrSel = '.add-new-address';

var _payMethodListSel = '.pay-method-list';

var _couponInput = $('input[name="coupon"]');
var _couponApplyBtnSel = '#apply-coupon';

var _realPriceSel = '.real-price';

var _submitBtnSel = '#submit-order';
var _originSendBtnText = '';
var _spinner = '<i class="tico tico-spinner spinning"></i>';

var _submitting = false;

var _validateRequiredInputs = function () {
    if(_receiverNameInput && _receiverEmailInput) {
        var name = _receiverNameInput.val();
        var email = _receiverEmailInput.val();
        return name.length && Utils.isEmail(email)
    }
    return true;
};

var _handleCheckout = function (btn) {
    if(_submitting || !Utils.checkLogin() || !_validateRequiredInputs()) return false;
    
    var orderId = parseInt(btn.data('order-id'));
    if(!orderId) {
        return false;
    }
    
    var data = {
        checkout: true
    };
    //data.orderId = orderId;
    data.userMessage = _memoTextarea.length ? _memoTextarea.val() : '';
    
    var addressList = $(_addressListActiveSel);
    if(addressList.length){
        data.addressId = addressList.data('address-id');
    }else{
        if(_receiverNameInput.length && _receiverEmailInput.length && _receiverNameInput.val() && _receiverEmailInput.val() ){
            data.receiverName = _receiverNameInput.val();
            data.receiverEmail = _receiverEmailInput.val();
            data.receiverPhone = _receiverPhoneInput.length ? _receiverPhoneInput.val() : '';
            data.receiverAddr = _receiverAddrInput.length ? _receiverAddrInput.val() : '';
            data.receiverZip = _receiverZipInput.length ? _receiverZipInput.val() : '';
        }else{
            return false;
        }
    }
    
    var paymentList = $(_payMethodListSel);
    
    if(paymentList.length){
        var checkedMethod = paymentList.find('input[type="radio"]:checked');
        data.payMethod = checkedMethod.length ? checkedMethod.val() : 'qrcode';
    }
    
    var url = Routes.orders + '/' + orderId;
    
    var beforeSend = function () {
        if(_submitting) return;
        _originSendBtnText = btn.text();
        btn.html(_spinner);
        btn.prop('disabled', true);
        Utils.showFullLoader('tico-spinner9 spinning', '正在更新订单信息...');
        _submitting = true;
    };
    
    var finishRequest = function () {
        if(!_submitting) return;
        btn.text(_originSendBtnText);
        btn.prop('disabled', false);
        Utils.hideFullLoader();
        _submitting = false;
    };
    
    var success = function (data, textStatus, xhr) {
        finishRequest();
        if(data.success && data.success == 1) {
            // popMsgbox.success({
            //     title: data.message,
            //     //text: '<a href="' + data.data['chatUrl'] + '">查看对话</a>',
            //     showConfirmButton: true
            // });
            location.href = data.data.url;
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

var _initCheckout = function () {
    _body.on('click', _submitBtnSel, function () {
        _handleCheckout($(this));
    });
};


// 选择地址
var _initChooseAddr = function () {
    if($(_addressListActiveSel).length < 1){
        $(_addressListSel).first().addClass('active');
    }
    _body.on('click', _addressListSel, function () {
        $(this).siblings().removeClass('active').end().addClass('active');
    });
};


// 应用优惠码
var _handleApplyCoupon = function (btn) {
    if(_submitting || !Utils.checkLogin() || !_couponInput || !(_couponInput.val())) return false;
    
    var orderId = parseInt(btn.data('order-id'));
    if(!orderId) {
        return false;
    }
    
    var data = {
        coupon: _couponInput.val()
    };
    
    var url = Routes.orders + '/' + orderId;
    
    var beforeSend = function () {
        if(_submitting) return;
        _originSendBtnText = btn.text();
        btn.html(_spinner);
        btn.prop('disabled', true);
        $(_submitBtnSel).prop('disabled', true);
        _couponInput.prop('disabled', true);
        //Utils.showFullLoader('tico-spinner9 spinning', '正在更新订单信息...');
        _submitting = true;
    };
    
    var finishRequest = function () {
        if(!_submitting) return;
        btn.text(_originSendBtnText);
        btn.prop('disabled', false);
        $(_submitBtnSel).prop('disabled', false);
        _couponInput.prop('disabled', false);
        //Utils.hideFullLoader();
        _submitting = false;
    };
    
    var success = function (data, textStatus, xhr) {
        finishRequest();
        if(data.success && data.success == 1) {
            popMsgbox.success({
                title: data.message,
                timer: 2000,
                showConfirmButton: true
            });
            $(_realPriceSel).text(data.data.realPrice);
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

var _initApplyCoupon = function () {
    _body.on('click', _couponApplyBtnSel, function () {
        _handleApplyCoupon($(this));
    });
};

// 地址添加
var _initAddAddress = function () {
    _body.on('click', _addNewAddrSel, function () {
        var addrInputGroup = $($(this).data('show-sel'));
        var addrList = $($(this).data('hide-sel'));
        if(addrInputGroup.length) {
            addrInputGroup.show();
        }
        if(addrList.length) {
            addrList.remove();
        }
        $(this).remove();
    });
};

//
var _init = function () {
    _initCheckout();
    _initChooseAddr();
    _initApplyCoupon();
    _initAddAddress();
};

var Checkout = {
    init: _init
};

export default Checkout;