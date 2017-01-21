/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/15 16:35
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

var _submitBtnSel = '#add-member';

var _typeRadios = 'body .member-radios';
var _userInputSel = 'input[name="user"]';

var _submitting = false;

var _error = function (title) {
    popMsgbox.error({
        title: title,
        timer: 2000,
        showConfirmButton: true
    });
    return false;
};

var _getMemberTypeRadio = function () {
    var typeRadios = $(_typeRadios);
    if(!typeRadios.length) {
        return 1;
    }
    return typeRadios.find('input[type="radio"]:checked').val();
};

var _handleAddMember = function (btn) {
    if(_submitting || !Utils.checkLogin()) return false;
    
    var data = {};
    
    var userInput = $(_userInputSel);
    if(userInput.length < 1) return false;
    if(userInput.val().length < 1) {
        return _error('请填写用户登录名或ID')
    }
    data.user = userInput.val();
    
    data.type = _getMemberTypeRadio();
    
    
    var url = Routes.members;
    
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
        _handleAddMember($(this));
    });
};

var AddMember = {
    init: _init
};

export default AddMember;