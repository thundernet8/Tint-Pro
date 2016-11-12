/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/12 23:57
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

import {Routes} from './globalConfig';
import Utils from './utils';
import {popMsgbox} from './msgbox';

var _modalBanAnchorSel = '.ban-btn';

var _modalBanBoxSel = '#banBox';

var _banBoxNonceSel = '.ban-nonce';
var _banBoxTextareaSel = '.ban-text';

var _cancelSel = '.cancel';
var _sendSel = '.confirm';

var _spinner = '<i class="tico tico-spinner2 spinning"></i>';
var _originSendBtnText = '';


var _body = $('body');
var _banModalBox = $(_modalBanBoxSel);

var _banNonceInput;
var _banTextArea;

var _action;
var _uid;

var _sending = false;


// 打开模态对话框
var _showModalBanBox = function (btn) {
    if(!Utils.checkLogin()) return false;
    
    if(_banModalBox.length) {
        _banModalBox.modal('show');
        _action = btn.data('action');
        _action = _action == 'ban' || _action == 'unban' ? _action : 'unban';
        _uid = btn.data('uid');
        return true;
    }
    return false;
};

// 关闭模态消息框
var _closeModalBanBox = function () {
    if(_banModalBox.length) {
        _uid = 0;
        _action = null;
        _banModalBox.modal('hide');
    }
};


// 模态消息框发送消息
var _banOrUnbanUser = function (btn) {
    if(_sending || !Utils.checkLogin()) return false;
    
    _banNonceInput = $(_modalBanBoxSel + ' ' + _banBoxNonceSel);
    _banTextArea = $(_modalBanBoxSel + ' ' + _banBoxTextareaSel);
    
    if(!_banNonceInput || !_banTextArea) return false;
    
    var nonce = _banNonceInput.val();
    var reason = _banTextArea.val();
    if(nonce.length == 0) return false;
    if(reason.length == 0 && _action == 'ban') {
        _banTextArea.focus();
        _banTextArea.addClass('error');
        setTimeout(function () {
            _banTextArea.removeClass('error');
        }, 2000);
        return false
    }
    
    if(!_uid) {
        return false;
    }
    
    var url = Routes.accountStatus + '/' + _uid;
    var data = {
        action: _action,
        banNonce: nonce,
        reason: reason
    };
    
    var beforeSend = function () {
        if(_sending) return;
        _originSendBtnText = btn.text();
        btn.html(_spinner);
        btn.prop('disabled', true);
        _banTextArea.prop('disabled', true);
        _sending = true;
    };
    
    var finishRequest = function () {
        if(!_sending) return;
        btn.text(_originSendBtnText);
        btn.prop('disabled', false);
        _banTextArea.prop('disabled', false).val('');
        _closeModalBanBox();
        _sending = false;
    };
    
    var success = function (data, textStatus, xhr) {
        finishRequest();
        if(data.success && data.success == 1) {
            popMsgbox.success({
                title: data.message,
                timer: 2000,
                showConfirmButton: true
            });
            setTimeout(function () {
                window.location.replace(location.href);
            }, 2000);
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

var BanKit = {
    initModalBan: function () {
        _body.on('click', _modalBanAnchorSel, function (e) {
            e.preventDefault();
            var $this = $(this);
            _showModalBanBox($this);
        });
        _body.on('click', _modalBanBoxSel + ' ' + _cancelSel, function () {
            _closeModalBanBox();
        });
        _body.on('click', _modalBanBoxSel + ' ' + _sendSel, function () {
            var $this = $(this);
            _banOrUnbanUser($this);
        });
    },
};

export default BanKit;