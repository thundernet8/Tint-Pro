/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/17 14:31
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';


import {Routes} from './globalConfig';
import Utils from './utils';
import {popMsgbox} from './msgbox';

var _body = $('body');
var _apiurl = Routes.userProfiles;

var _sending = false;

// 三个类型的保存按钮
var _saveBtnSel = '.btn-save-settings';

var _avatarTypeSel = 'input[type="radio"][name="avatar"]:checked';
var _nicknameSel = 'input[name="nickname"]';
var _siteSel = 'input[name="user_url"]';
var _descriptionSel = 'textarea[name="description"]';

var _qqSel = 'input[name="tt_qq"]';
var _weiboSel = 'input[name="tt_weibo"]';
var _weixinSel = 'input[name="tt_weixin"]';
var _twitterSel = 'input[name="tt_twitter"]';
var _facebookSel = 'input[name="tt_facebook"]';
var _googleplusSel = 'input[name="tt_googleplus"]';
var _alipaySel = 'input[name="tt_alipay_email"]';
var _alipayPaySel = 'input[name="tt_alipay_pay_qr"]';
var _weixinPaySel = 'input[name="tt_wechat_pay_qr"]';

var _emailSel = 'input[name="user_email"]';
var _passwordSel = 'input[name="password"]';
var _password2Sel = 'input[name="password2"]';

var _init = function () {
    _body.on('click', _saveBtnSel, function () {
        _handleSave($(this));
    });
};


var _handleSave = function (btn) {
    var type = btn.data('save-info');
    switch (type){
        case 'basis':
            _saveBasicProfile();
            break;
        case 'extends':
            _saveExtendProfile();
            break;
        case 'security':
            _saveSecurityInfo();
            break;
        default:
            return;
    }
};


var _saveBasicProfile = function () {
    var nickname = $(_nicknameSel).val();
    if(nickname.length == 0) {
        popMsgbox.alert({
            title: '昵称不能为空',
            timer: 2000
        });
        return false;
    }
    var data = {
        type: 'basis',
        avatarType: $(_avatarTypeSel).val(),
        nickname: nickname,
        site: $(_siteSel).val(),
        description: $(_descriptionSel).val()
    };
    
    var beforeSend = function () {
        if(_sending) return;
        Utils.showFullLoader('tico-spinner2', '正在更新基本资料...');
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
                timer: 2000,
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
        url: _apiurl,
        data: Utils.filterDataForRest(data),
        dataType: 'json',
        beforeSend: beforeSend,
        success: success,
        error: error
    });
};


var _saveExtendProfile = function () {
    var data = {
        type: 'extends',
        qq: $(_qqSel).val(),
        weibo: $(_weiboSel).val(),
        weixin: $(_weixinSel).val(),
        twitter: $(_twitterSel).val(),
        facebook: $(_facebookSel).val(),
        googleplus: $(_googleplusSel).val(),
        alipay: $(_alipaySel).val(),
        alipayPay: $(_alipayPaySel).val(),
        weixinPay: $(_weixinPaySel).val()
    };
    
    var beforeSend = function () {
        if(_sending) return;
        Utils.showFullLoader('tico-spinner2', '正在更新扩展资料...');
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
                timer: 2000,
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
        url: _apiurl,
        data: Utils.filterDataForRest(data),
        dataType: 'json',
        beforeSend: beforeSend,
        success: success,
        error: error
    });
};


var _saveSecurityInfo = function () {
    var email = $(_emailSel).val();
    var password = $(_passwordSel).val();
    var password2 = $(_password2Sel).val();
    
    if(email.length == 0) {
        popMsgbox.alert({
            title: '邮箱不能为空',
            timer: 2000
        });
        return false;
    }
    if(!Utils.isEmail(email)) {
        popMsgbox.alert({
            title: '邮箱格式不正确',
            timer: 2000
        });
        return false;
    }
    if(password.length > 0 && password.length < 6) {
        popMsgbox.alert({
            title: '密码位数太短',
            timer: 2000
        });
        return false;
    }
    if(password != password2) {
        popMsgbox.alert({
            title: '两次输入的密码不一致',
            timer: 2000
        });
        return false;
    }
    
    var data = {
        type: 'security',
        email: email
    };
    if(password.length > 0) {
        data.password = password;
    }
    
    var beforeSend = function () {
        if(_sending) return;
        Utils.showFullLoader('tico-spinner2', '正在更新安全信息...');
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
                timer: 2000,
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
        url: _apiurl,
        data: Utils.filterDataForRest(data),
        dataType: 'json',
        beforeSend: beforeSend,
        success: success,
        error: error
    });
};


// 导出模块

var SaveSettingsKit = {
    init: _init
};

export default SaveSettingsKit;