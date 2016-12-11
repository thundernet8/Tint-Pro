/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/11 16:24
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

import {Routes} from './globalConfig';
import Utils from './utils';
import {popMsgbox} from './msgbox';


var _buyBtnSel = '.buy-resource';

var _body = $('body');

var _sending = false;


// 购买资源请求
var _buyResource = function (btn) {
    if(_sending || !Utils.checkLogin()) return false;
    
    var postId = parseInt(btn.data('post-id'));
    var resourceSeq = parseInt(btn.data('resource-seq'));

    if(!postId || !resourceSeq) return false;
    
    var url = Routes.boughtResources;
    var data = {
        postId: postId,
        resourceSeq: resourceSeq
    };
    
    var beforeSend = function () {
        if(_sending) return;
        Utils.showFullLoader('tico-spinner2', '正在请求中...');
        _sending = true;
    };
    
    var finishRequest = function () {
        if(!_sending) return;
        Utils.hideFullLoader();
        _sending = false;
    };
    
    var success = function (data, textStatus, xhr) {
        finishRequest();
        if(data.success && data.success == 1) {
            popMsgbox.success({
                title: data.message,
                text: '消费积分: ' + data.data.cost + '<br>当前积分余额: ' + data.data.balance,
                html: true,
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


/* 导出模块 */
/* ---------------------- */

var BuyResource = {
    init: function () {
        _body.on('click', _buyBtnSel, function (e) {
            e.preventDefault();
            var $this = $(this);
            _buyResource($this);
        });
    }
};

export default BuyResource;