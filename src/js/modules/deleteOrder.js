/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/19 21:26
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';


import {Routes, Urls} from './globalConfig';
import Utils from './utils';
import {popMsgbox} from './msgbox'

var _body = $('body');

var _continueBtnSel = '.order-actions>.delete-order';


var _submitting = false;

var _handleContinuePay = function (btn) {
    if(_submitting || !Utils.checkLogin()) return false;
    
    var orderId = parseInt(btn.data('order-id'));
    if(!orderId) {
        return false;
    }
    
    var data = {};
    
    var url = Routes.orders + '/' + orderId;
    
    var beforeSend = function () {
        if(_submitting) return;
        Utils.showFullLoader('tico-spinner2', '正在请求中...');
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
            popMsgbox.success({
                title: data.message,
                timer: 2000,
                showConfirmButton: true
            });
            $('#oid-' + data.data.order_id).remove(); // 删除行
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
        url: url + '?' + $.param(Utils.filterDataForRest(data)),
        type: 'DELETE',
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

var DeleteOrder = {
    init: _init
};

export default DeleteOrder;