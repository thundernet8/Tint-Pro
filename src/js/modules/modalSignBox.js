/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/11 22:15
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';

import Utils from './utils';
import {Routes, Urls} from './globalConfig';
import {popMsgbox} from './msgbox';

var _body = $('body');

var _modalSignBoxSel = '#modalSignBox';
var _modalSignBox = $('#modalSignBox');
var _userLoginInput = $('#user_login-input');
var _passwordInput = $('#password-input');
var _tipSel = '.tip';
var _submitBtnSel = _modalSignBoxSel + ' button.submit';

var _originSubmitBtnText = '';
var _spinner = '<i class="tico tico-spinner3 spinning"></i>';
var _submitting = false;

/* 请求登录 */
var _validate = function (input) {
    if(!input) {
        var userLoginValidated = _validateUserLogin();
        var passwordValidated = _validatePassword();
        return  userLoginValidated && passwordValidated;
    }else if(input.attr('name') === 'user_login'){
        return _validateUserLogin();
    }else if(input.attr('name') === 'password'){
        return _validatePassword();
    }
    return false;
};

var _validateUserLogin = function () {
    if(_userLoginInput.val() === '') {
        _showError(_userLoginInput, '请输入账号');
        return false;
    } else if (!Utils.isValidUserName(_userLoginInput.val()) && !Utils.isEmail(_userLoginInput.val())) {
        _showError(_userLoginInput, '邮箱或字母开头用户名');
        return false;
    } else if (_userLoginInput.val().length < 5) {
        _showError(_userLoginInput, '账户长度至少为5');
        return false;
    }
    _removeError(_userLoginInput);
    return true;
};

var _validatePassword = function () {
    if(_passwordInput.val() === '') {
        _showError(_passwordInput, '请输入密码');
        return false;
    } else if (_passwordInput.val().length < 6) {
        _showError(_passwordInput, '密码长度至少为6');
        return false;
    }
    _removeError(_passwordInput);
    return true;
};

var _showError = function (input, msg) {
    var inputName = input.attr('name');
    switch (inputName) {
        case 'user_login':
            _removeError(_userLoginInput);
            break;
        case 'password':
            _removeError(_passwordInput);
            break;
    }
    input.next(_tipSel).text(msg).show();
};

var _removeError = function (input) {
    input.next(_tipSel).hide().text('');
};

var _post = function (submitBtn) {
    // Login
    var url = Routes.signIn;
    var beforeSend = function () {
        _modalSignBox.addClass('submitting');
        _userLoginInput.prop('disabled', true);
        _passwordInput.prop('disabled', true);
        _originSubmitBtnText = submitBtn.text();
        submitBtn.prop('disabled', true).html(_spinner);
        _submitting = true;
    };
    var finishRequest = function () {
        _modalSignBox.removeClass('submitting');
        _userLoginInput.prop('disabled', false);
        _passwordInput.prop('disabled', false);
        submitBtn.text(_originSubmitBtnText).prop('disabled', false);
        _submitting = false;
    };
    var success = function (data, textStatus, xhr) {
        if(data.success && data.success == 1) {
            var redirect = Utils.getUrlPara('redirect') ? Utils.getAbsUrl(decodeURIComponent(Utils.getUrlPara('redirect'))) : '';
            popMsgbox.success({
                title: '登录成功',
                text: redirect ? '将在 2s 内跳转至 ' + redirect : '将在 2s 内刷新页面',
                timer: 2000,
                showConfirmButton: false
            });
            setTimeout(function () {
                window.location.href = redirect ? redirect : location.href;
            }, 2000);
        }else{
            popMsgbox.error({
                title: '登录错误',
                text: data.message
            });
            finishRequest();
        }
    };
    var error = function (xhr, textStatus, err) {
        popMsgbox.error({
            title: '请求登录失败, 请重新尝试',
            text: xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText
        });
        finishRequest();
    };
    $.post({
        url: url,
        data: Utils.filterDataForRest(_modalSignBox.serialize()),
        dataType: 'json',
        beforeSend: beforeSend,
        success: success,
        error: error
    });
};

/* 弹窗或关闭弹窗 */
var _showBox = function () {
    if($(window).width() < 640) {
        window.location.href = Utils.addRedirectUrl(Urls.signIn, window.location.href);
        return;
    }
    _modalSignBox.modal('show');
};

var _hideBox = function () {
    _modalSignBox.modal('hide');
};

 // TODO
var ModalSignBox = {
    init: function () {
        _body.on('click', '.modal-backdrop', function () {
            _hideBox();
        });
        // 绑定事件
        _userLoginInput.on('input', function () { //change事件只在失去焦点时触发
            _validate($(this));
        });
        _passwordInput.on('input', function () {
            _validate($(this));
        });
        _body.on('click', _submitBtnSel, function () {
            if(_validate()){
                _post($(this));
            }
        });
    },
    show: function() {
        _showBox();
    },
    hide: function() {
        _hideBox();
    }
};

export default ModalSignBox;