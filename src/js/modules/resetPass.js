/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/02 17:39
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';


import {Routes, Urls} from './globalConfig';
import Utils from './utils';
import {popMsgbox} from './msgbox'

var _body = $('body');

var _btnOriginText = '';
var _spinner = '<i class="tico tico-spinner9 spinning"></i>';

var _passInputSel = '#inputPassword';
var _passInput2Sel = '#inputPassword2';
var _btnSel = '#reset-pass';

var _submitting = false;

var _handleResetPass = function (btn) {
    if(_submitting) return false;
    var passInput = $(_passInputSel);
    var passInput2 = $(_passInput2Sel);
    
    if(passInput.length < 1 || passInput2.length < 1) return false;
    
    var pass = passInput.val();
    var pass2 = passInput2.val();
    if(pass.length < 6 || pass2.length < 6) {
        popMsgbox.warning({
            title: '密码的长度太短',
            timer: 2000,
            showConfirmButton: true
        });
        return false;
    }
    
    if(pass != pass2) {
        popMsgbox.error({
            title: '两次输入的密码不一致',
            timer: 2000,
            showConfirmButton: true
        });
        return false;
    }
    
    var data = {
        password : pass,
        key: Utils.getQueryString('key')
    };
    
    var url = Routes.users + '/key?act=resetpass';
    
    var beforeSend = function () {
        if(_submitting) return;
        passInput.prop('disabled', true);
        passInput2.prop('disabled', true);
        btn.prop('disabled', true);
        _btnOriginText = btn.html();
        btn.html(_spinner);
        _submitting = true;
    };
    
    var finishRequest = function () {
        if(!_submitting) return;
        passInput.prop('disabled', false);
        passInput2.prop('disabled', false);
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
            }, function(){
                window.location.replace(Urls.signIn);
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
    _body.on('click', _btnSel, function () {
        _handleResetPass($(this));
    });
};

var ResetPass = {
    init: _init
};

export default ResetPass;