/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/02 16:52
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';


import {Routes} from './globalConfig';
import Utils from './utils';
import {popMsgbox} from './msgbox'

var _body = $('body');

var _btnOriginText = '';
var _spinner = '<i class="tico tico-spinner9 spinning"></i>';

var _emailInputSel = '#inputEmail';
var _btnSel = '#find-pass';

var _submitting = false;

var _handleFindPass = function (btn) {
    if(_submitting) return false;
    var emailInput = $(_emailInputSel);
    if(emailInput.length < 1) return false;
    if(!Utils.isEmail(emailInput.val())) {
        popMsgbox.error({
            title: '邮箱格式不正确',
            timer: 2000,
            showConfirmButton: true
        });
        return false;
    }
    
    var data = {
        email : emailInput.val()
    };
    
    var url = Routes.users + '/email?act=findpass';
    
    var beforeSend = function () {
        if(_submitting) return;
        emailInput.prop('disabled', true);
        btn.prop('disabled', true);
        _btnOriginText = btn.html();
        btn.html(_spinner);
        _submitting = true;
    };
    
    var finishRequest = function () {
        if(!_submitting) return;
        emailInput.prop('disabled', false);
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
                window.location.replace(location.href);
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
    
    $.get({
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
        _handleFindPass($(this));
    });
};

var FindPass = {
    init: _init
};

export default FindPass;