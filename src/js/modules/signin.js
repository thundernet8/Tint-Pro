/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/15 23:05
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

import {Routes} from './globalConfig';
import {popMsgbox} from './msgbox';
import Utils from './utils';

/**
 * 登录页登录表单
 */
var _form = $('.form-signin');
var _userLoginInput = $('#user_login-input');
var _passwordInput = $('#password-input');
var _submitting = false;

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
        _showError(_userLoginInput, '邮箱或者以字母开头的英文/数字/下划线组合的用户名');
        return false;
    } else if (_userLoginInput.val().length < 5) {
      _showError(_userLoginInput, '账户长度至少为 5');
      return false;
    }
    return true;
};

var _validatePassword = function () {
    if(_passwordInput.val() === '') {
        _showError(_passwordInput, '请输入密码');
        return false;
    } else if (_passwordInput.val().length < 6) {
        _showError(_passwordInput, '密码长度至少为 6');
        return false;
    }
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
    input.parent().addClass('error').append('<div class="error-tip">' + msg + '</div>');
};

var _removeError = function (input) {
    input.parent().removeClass('error').children('.error-tip').remove();
};

var _post = function () {
    // Login
    var url = Routes.signIn;
    var beforeSend = function () {
        _form.addClass('submitting');
        _userLoginInput.prop('disabled', true);
        _passwordInput.prop('disabled', true);
        _submitting = true;
    };
    var finishRequest = function () {
        _form.removeClass('submitting');
        _userLoginInput.prop('disabled', false);
        _passwordInput.prop('disabled', false);
        _submitting = false;
    };
    var success = function (data, textStatus, xhr) {
        if(data.success && data.success == 1) {
            var redirect = Utils.getUrlPara('redirect_to') ? Utils.getAbsUrl(decodeURIComponent(Utils.getUrlPara('redirect_to'))) : (Utils.getUrlPara('redirect') ? Utils.getAbsUrl(decodeURIComponent(Utils.getUrlPara('redirect'))) : Utils.getSiteUrl());
            popMsgbox.success({
                title: '登录成功',
                text: '将在 2s 内跳转至 ' + redirect,
                timer: 2000,
                showConfirmButton: false
            });
            setTimeout(function () {
                window.location.href = redirect;
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
        data: Utils.filterDataForRest(_form.serialize()),
        dataType: 'json',
        beforeSend: beforeSend,
        success: success,
        error: error
    });
};

var pageSignIn = {
    init: function () {
        // 绑定事件
        $('body').on('blur', '.local-signin>.input-container>input', function () {
            _validate($(this));
        }).on('keyup', '.local-signin>.input-container>input', function (e) {
            var $this = $(this);
            _validate($this) ? _removeError($this) : function () {}();
            if(e.keyCode === 13 && !_submitting && $this.attr('name') === 'password' && _validate()) {
                _post();
            }
        });
    }
};

/**
 * 模态登录框(任意页面)
 */

// TODO

export {pageSignIn};