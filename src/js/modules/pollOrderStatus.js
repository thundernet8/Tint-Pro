/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/02/15 22:35
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';


import {Routes} from './globalConfig';
import Utils from './utils';
import {popMsgbox} from './msgbox';

var _body = $('body');

var _polling = false;

var _pollOrderStatus = function (orderId) {
    
    var url = Routes.orders + '/' + orderId;
    var data = {
        
    };
    
    var beforeSend = function () {
        // return false; // Note: return false 会导致AJAX请求取消, return 空或true则继续请求
        if(_polling) return false;
        _polling = true;
    };
    var finishRequest = function () {
        if(!_polling) return;
        _polling = false;
    };
    var success = function (data, textStatus, xhr) {
        if(data.success && data.success == 1) {
            popMsgbox.success({
                title: data.message,
                text: '即将跳转至订单详情页面',
                showConfirmButton: true
            }, function(){
                window.location.replace(data.url)
            });
        }else{
            setTimeout(function () {
                _pollOrderStatus(orderId);
            }, 6000);
        }
        finishRequest();
    };
    var error = function (xhr, textStatus, err) {
        // TODO
        finishRequest();
        setTimeout(function () {
            _pollOrderStatus(orderId);
        }, 6000);
    };
    $.get({
        url: url,
        data: Utils.filterDataForRest(data),
        dataType: 'json',
        beforeSend: beforeSend,
        success: success,
        error: error
    });
};


/* 导出模块 */
/* ---------------------- */

var PollOrderStatus = {
    init: function () {
        if(!_body.hasClass('qrpay') || !Utils.getQueryString('oid') || !Utils.checkLogin()) return false;
    
        setTimeout(function () {
            _pollOrderStatus(Utils.getQueryString('oid'));
        }, 30000);
    }
};

export default PollOrderStatus;