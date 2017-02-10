/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/03 20:05
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

var _oauthTypeSel = '#oauthType';
var _usernameInputSel = '#inputUsername';
var _passInputSel = '#inputPassword';
var _btnSel = '#bind-account';

var _submitting = false;

var _handleOpenBind = function (btn) {
    if(_submitting) return false;
    var oauthTypeInput = $(_oauthTypeSel);
    var usernameInput = $(_usernameInputSel);
    var passInput = $(_passInputSel);
    
    if(usernameInput.length < 1 || passInput.length < 1) return false;
    
    var oauthType = oauthTypeInput.length > 1 ? oauthTypeInput.val() : 'qq';
    var username = usernameInput.val();
    var password = passInput.val();
    
    if(username === '') {
        popMsgbox.error({
            title: '请输入邮箱',
            timer: 2000,
            showConfirmButton: true
        });
        return false;
    } else if (!Utils.isEmail(username)) {
        popMsgbox.error({
            title: '不正确的邮箱格式',
            timer: 2000,
            showConfirmButton: true
        });
        return false;
    } else if (username.length < 5) {
        popMsgbox.warning({
            title: '账户长度至少为 5',
            timer: 2000,
            showConfirmButton: true
        });
        return false;
    }
    
    if(password.length < 6) {
        popMsgbox.warning({
            title: '密码的长度太短',
            timer: 2000,
            showConfirmButton: true
        });
        return false;
    }
    
   
    var data = {
        user_login: username, // 就是Email
        password: password,
        oauth: oauthType,
        key: Utils.getQueryString('key')
    };
    
    var url = Routes.session;
    
    var beforeSend = function () {
        if(_submitting) return;
        usernameInput.prop('disabled', true);
        passInput.prop('disabled', true);
        btn.prop('disabled', true);
        _btnOriginText = btn.html();
        btn.html(_spinner);
        _submitting = true;
    };
    
    var finishRequest = function () {
        if(!_submitting) return;
        usernameInput.prop('disabled', false);
        passInput.prop('disabled', false);
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
                window.location.replace(decodeURIComponent(Utils.getQueryString('redirect')));
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
        _handleOpenBind($(this));
    });
};

var OpenBind = {
    init: _init
};

export default OpenBind;