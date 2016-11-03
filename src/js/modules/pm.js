/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/02 20:17
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

import Utils from './utils';
import {Routes} from './globalConfig';
import {popMsgbox} from './msgbox';

var _modalPmAnchorSel = '.pm-btn';

var _modalPmBoxSel = '#pmBox';
var _modalPmBoxReceiverSel = '.pm-info_receiver';

var _normalPmBoxSel = '#pmForm';
var _receiverIdInputSel = '.receiver-id';

var _pmBoxNonceSel = '.pm_nonce';
var _pmBoxTextareaSel = '.pm-text';

var _cancelSel = '.cancel';
var _sendSel = '.confirm';

var _spinner = '<i class="tico tico-spinner2 spinning"></i>';
var _originSendBtnText = '';

var _receiverId;

var _body = $('body');
var _pmModalBox = $(_modalPmBoxSel);
var _pmModalBoxReceiverEle = null;

var _pmReceiverIdInput;
var _pmNonceInput;
var _pmTextArea;

var _sending = false;


// 打开模态消息框
var _showModalPmBox = function (btn) {
    if(!Utils.checkLogin()) return false;
    
    var receiver = btn.data('receiver');
    var receiverId = btn.data('receiver-id');
    if(!receiver || !receiverId) return false;
    
    _receiverId = receiverId;
    
    if(!_pmModalBoxReceiverEle) _pmModalBoxReceiverEle = $(_modalPmBoxSel + ' ' + _modalPmBoxReceiverSel);
    
    _pmModalBoxReceiverEle.text(receiver);
    
    _pmModalBox.modal('show');
};

// 关闭模态消息框
var _closeModalPmBox = function () {
    if(!_pmModalBoxReceiverEle) _pmModalBoxReceiverEle = $(_modalPmBoxSel + ' ' + _modalPmBoxReceiverSel);
    
    _pmModalBoxReceiverEle.text('');
    
    _pmModalBox.modal('hide');
};


// 模态消息框发送消息
var _sendMsgInModalBox = function (btn) {
    if(_sending || !_receiverId || !Utils.checkLogin()) return false;
    
    _pmNonceInput = $(_modalPmBoxSel + ' ' + _pmBoxNonceSel);
    _pmTextArea = $(_modalPmBoxSel + ' ' + _pmBoxTextareaSel);
    
    if(!_pmNonceInput || !_pmTextArea) return false;
    
    var nonce = _pmNonceInput.val();
    var message = _pmTextArea.val();
    if(nonce.length == 0) return false;
    if(message.length == 0) {
        _pmTextArea.focus();
        _pmTextArea.addClass('error');
        setTimeout(function () {
            _pmTextArea.removeClass('error');
        }, 2000);
        return false
    }
    
    var url = Routes.pm;
    var data = {
        receiverId: _receiverId,
        pmNonce: nonce,
        message: message
    };
    
    var beforeSend = function () {
        if(_sending) return;
        _originSendBtnText = btn.text();
        btn.html(_spinner);
        btn.prop('disabled', true);
        _pmTextArea.prop('disabled', true);
        _sending = true;
    };
    
    var finishRequest = function () {
        if(!_sending) return;
        btn.text(_originSendBtnText);
        btn.prop('disabled', false);
        _pmTextArea.prop('disabled', false).val('');
        _closeModalPmBox();
        _sending = false;
    };
    
    var success = function (data, textStatus, xhr) {
        finishRequest();
        if(data.success && data.success == 1) {
            popMsgbox.success({
                title: data.message,
                text: '<a href="' + data.data['chatUrl'] + '">查看对话</a>',
                html: true,
                showConfirmButton: true
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


// 普通消息框发送消息
var _sendMsgInNormalForm = function (btn) {
    if(_sending || !Utils.checkLogin()) return false;
    
    _pmReceiverIdInput = $(_normalPmBoxSel + ' ' + _receiverIdInputSel);
    _pmNonceInput = $(_normalPmBoxSel + ' ' + _pmBoxNonceSel);
    _pmTextArea = $(_normalPmBoxSel + ' ' + _pmBoxTextareaSel);
    
    if(!_pmReceiverIdInput || !_pmNonceInput || !_pmTextArea) return false;
    
    var receiverId = _pmReceiverIdInput.val();
    var nonce = _pmNonceInput.val();
    var message = _pmTextArea.val();
    if(!receiverId || nonce.length == 0) return false;
    if(message.length == 0) {
        _pmTextArea.focus();
        _pmTextArea.addClass('error');
        setTimeout(function () {
            _pmTextArea.removeClass('error');
        }, 2000);
        return false
    }
    
    var url = Routes.pm;
    var data = {
        receiverId: receiverId,
        pmNonce: nonce,
        message: message
    };
    
    var beforeSend = function () {
        if(_sending) return;
        _originSendBtnText = btn.text();
        btn.html(_spinner);
        btn.prop('disabled', true);
        _pmTextArea.prop('disabled', true);
    };
    
    var finishRequest = function () {
        if(!_sending) return;
        btn.text(_originSendBtnText);
        btn.prop('disabled', false);
        _pmTextArea.prop('disabled', false).val('');
    };
    
    var success = function (data, textStatus, xhr) {
        finishRequest();
        if(data.success && data.success == 1) {
            popMsgbox.success({
                title: data.message,
                timer: 2000,
                showConfirmButton: true
            });
            _prependNewMsg(data.data);
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

var _prependNewMsg = function (msg) {
    //TODO prepend new pm to the message list
};


/* 导出模块 */
/* ---------------------- */

var PmKit = {
    initModalPm: function () {
        _body.on('click', _modalPmAnchorSel, function (e) {
            e.preventDefault();
            var $this = $(this);
            _showModalPmBox($this);
        });
        _body.on('click', _modalPmBoxSel + ' ' + _cancelSel, function () {
            _closeModalPmBox();
        });
        _body.on('click', _modalPmBoxSel + ' ' + _sendSel, function () {
            var $this = $(this);
            _sendMsgInModalBox($this);
        });
    },
    initNormalPm: function () {
        _body.on('click', _normalPmBoxSel + ' ' + _sendSel, function () {
            var $this = $(this);
            _sendMsgInNormalForm($this);
        });
    }
};

export default PmKit;