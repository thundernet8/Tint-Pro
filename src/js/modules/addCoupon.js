/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/14 19:41
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';


import {Routes} from './globalConfig';
import Utils from './utils';
import {popMsgbox} from './msgbox'

var _body = $('body');

var _btnOriginText = '';
var _spinner = '<i class="tico tico-spinner9 spinning"></i>';

var _submitBtnSel = '#add-coupon';

var _typeRadios = 'body .coupon-radios';
var _codeInputSel = 'input[name="coupon_code"]';
var _discountInputSel = 'input[name="coupon_discount"]';
var _effectDateInputSel = 'input[name="effect_date"]';
var _expireDateInputSel = 'input[name="expire_date"]';

var _submitting = false;

var _error = function (title) {
    popMsgbox.error({
        title: title,
        timer: 2000,
        showConfirmButton: true
    });
    return false;
};

var _getCouponTypeRadio = function () {
    var typeRadios = $(_typeRadios);
    if(!typeRadios.length) {
        return 'once';
    }
    return typeRadios.find('input[type="radio"]:checked').val();
};

var _handleAddCoupon = function (btn) {
    if(_submitting || !Utils.checkLogin()) return false;
    
    var data = {};
    
    var codeInput = $(_codeInputSel);
    if(codeInput.length < 1) return false;
    if(codeInput.val().length < 4) {
        return _error('优惠码长度不能小于4')
    }
    data.code = codeInput.val();

    var discountInput = $(_discountInputSel);
    if(discountInput.length < 1) return false;
    var discount = discountInput.val();
    if(discount<0 || discount>1){
        return _error('折扣值应在0~1之间')
    }
    data.discount = discount;
    
    var effectDateInput = $(_effectDateInputSel);
    if(effectDateInput.length < 1) return false;
    var effectDate = effectDateInput.val();
    if(effectDate.length < 10) { //2017-01-01 最小10个字符
        return _error('生效日期格式不正确');
    }
    data.effectDate = effectDate.substr(0, Math.min(19, effectDate.length)).replace('T', ' ');
    
    var expireDateInput = $(_expireDateInputSel);
    if(expireDateInput.length < 1) return false;
    var expireDate = expireDateInput.val();
    if(expireDate.length < 10) {
        return _error('失效日期格式不正确');
    }
    data.expireDate = expireDate.substr(0, Math.min(19, expireDate.length)).replace('T', ' ');
    
    data.type = _getCouponTypeRadio();
    
    
    var url = Routes.coupons;
    
    var beforeSend = function () {
        if(_submitting) return;
        btn.prop('disabled', true);
        _btnOriginText = btn.html();
        btn.html(_spinner);
        _submitting = true;
    };
    
    var finishRequest = function () {
        if(!_submitting) return;
        btn.html(_btnOriginText);
        btn.prop('disabled', false);
        _submitting = false;
    };
    
    var success = function (data, textStatus, xhr) {
        finishRequest();
        if(data.success && data.success == 1) {
            popMsgbox.success({
                title: data.message,
                timer: 2000,
                showConfirmButton: true
            }, function () {
                location.replace(location.href);
            });
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


//
var _init = function () {
    _body.on('click', _submitBtnSel, function () {
        _handleAddCoupon($(this));
    });
};

var AddCoupon = {
    init: _init
};

export default AddCoupon;