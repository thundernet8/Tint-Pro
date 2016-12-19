/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/19 21:18
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';


import {Routes, Urls} from './globalConfig';
import Utils from './utils';
import {popMsgbox} from './msgbox'

var _body = $('body');

var _continueBtnSel = '.order-actions>.continue-pay';


var _submitting = false;

var _handleContinuePay = function (btn) {
    if(_submitting || !Utils.checkLogin()) return false;
    
    var orderId = parseInt(btn.data('order-id'));
    if(!orderId) {
        return false;
    }
    
    var data = {
        continuePay: true
    };

    var url = Routes.orders + '/' + orderId;
    
    var beforeSend = function () {
        if(_submitting) return;
        Utils.showFullLoader('tico-spinner2', '正在提交支付请求...');
        _submitting = true;
    };
    
    var finishRequest = function () {
        if(!_submitting) return;
        Utils.hideFullLoader();
        _submitting = false;
    };
    
    var success = function (data, textStatus, xhr) {
        finishRequest();
        if(data.success && data.success == 1) {
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


//
var _init = function () {
    _body.on('click', _continueBtnSel, function () {
        _handleContinuePay($(this));
    });
};

var ContinuePay = {
    init: _init
};

export default ContinuePay;