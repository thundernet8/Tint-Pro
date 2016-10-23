/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/17 22:13
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

import {Routes} from './globalConfig';
import {popMsgbox, msgbox} from './msgbox';
import Utils from './utils';

/**
 * 注册页注册表单
 */

var _form = $('.form-signup');
var _msgSibling = $('#default-tip');
var _userLoginInput = $('#user_login-input');
var _emailInput = $('#email-input');
var _passwordInput = $('#password-input');
var _captchaInput = $('#captcha-input');
var _captchaImg = $('img#captcha');
var _submitBtn = $('button#signup-btn');
var _submitBtnText = _submitBtn.text();
var _submitting = false;

var _validate = function (input, showMsg = true) {
    if(!input) {
        return _validateUserLogin(showMsg) && _validateEmail(showMsg) && _validatePassword(showMsg) && _validateCaptcha(showMsg);
    } else {
        var inputName = input.attr('name');
        switch (inputName) {
            case 'user_login':
                return _validateUserLogin(showMsg);
                break;
            case 'email':
                return _validateEmail(showMsg);
                break;
            case 'password':
                return _validatePassword(showMsg);
                break;
            case 'captcha':
                return _validateCaptcha(showMsg);
                break;
            default:
                return false;
        }
    }
};

var _validateUserLogin = function (showMsg) {
    if(_userLoginInput.val() === '') {
        if(showMsg) {
            msgbox.show('请输入用户名', 'danger', _msgSibling);
        }
        _userLoginInput.parent().addClass('has-error');
        return false;
    } else if (!Utils.isValidUserName(_userLoginInput.val()) && !Utils.isEmail(_userLoginInput.val())) {
        if(showMsg) {
            msgbox.show('用户名必须以字母开头, 英文/数字/下划线组合', 'danger', _msgSibling);
        }
        _userLoginInput.parent().addClass('has-error');
        return false;
    } else if (_userLoginInput.val().length < 5) {
        if(showMsg) {
            msgbox.show('账户长度至少为 5', 'danger', _msgSibling);
        }
        _userLoginInput.parent().addClass('has-error');
        return false;
    }
    _userLoginInput.parent().removeClass('has-error');
    return true;
};

var _validateEmail = function (showMsg) {
    if(_emailInput.val() === '') {
        if(showMsg) {
            msgbox.show('请填写邮箱', 'danger', _msgSibling);
        }
        _emailInput.parent().addClass('has-error');
        return false;
    } else if(!(Utils.isEmail(_emailInput.val()))) {
        if(showMsg) {
            msgbox.show('邮箱格式不正确', 'danger', _msgSibling);
        }
        _emailInput.parent().addClass('has-error');
        return false;
    }
    _emailInput.parent().removeClass('has-error');
    return true;
};

var _validatePassword = function (showMsg) {
    if(_passwordInput.val() === '') {
        if(showMsg) {
            msgbox.show('请输入密码', 'danger', _msgSibling);
        }
        _passwordInput.parent().addClass('has-error');
        return false;
    } else if (_passwordInput.val().length < 6) {
        if(showMsg) {
            msgbox.show('密码长度至少为 6', 'danger', _msgSibling);
        }
        _passwordInput.parent().addClass('has-error');
        return false;
    }
    _passwordInput.parent().removeClass('has-error');
    return true;
};

var _validateCaptcha = function (showMsg) {
    if(_captchaInput.val() === '') {
        if(showMsg) {
            msgbox.show('验证码不能为空', 'danger', _msgSibling);
        }
        _captchaInput.parent().addClass('has-error');
        return false;
    } else if (_captchaInput.val().length != 4) {
        if(showMsg) {
            msgbox.show('验证码长度必须为 4 位', 'danger', _msgSibling);
        }
        _captchaInput.parent().addClass('has-error');
        return false;
    }
    _captchaInput.parent().removeClass('has-error');
    return true;
};
 
var _removeMsg = function () {
    $('.form-signup>.msg').remove();
};

var _handleInputStatus = function (disable) {
    _userLoginInput.prop('disabled', disable);
    _emailInput.prop('disabled', disable);
    _passwordInput.prop('disabled', disable);
    _captchaInput.prop('disabled', disable);
};

// 刷新验证码
var _handleCaptchaRefresh = function (captcha) {
    var captchaSel = captcha ? captcha : _captchaImg;
    var originCaptchaUrl = captchaSel.attr('src');
    var date = new Date();
    var tQueryStr = date.getMilliseconds()/1000 + '00000_' +date.getTime();
    var newCaptchaUrl = originCaptchaUrl.replace(/\?t=([0-9_\.]+)/, '?t=' + tQueryStr);
    captchaSel.attr('src', newCaptchaUrl);
};

// 按钮状态
var _handleSubmitBtnStatus = function (disable) {
    var status = !!disable;
    _submitBtn.prop('disabled', status);
};

// 按钮提交
var _handleSubmitBtnHtml = function (submitting) {
    if(submitting) {
        _submitBtn.html('<span class="indicator spinner tico tico-spinner3"></span>')
    } else {
        _submitBtn.html('').text(_submitBtnText);
    }
};

// 注册成功后移除表单显示提示信息
var _handleSuccess = function () {
    var title = '注册完成';
    var message = '还差一步您就能正式拥有一个本站账户，请立即访问你注册时提供的邮箱，点击激活链接完成最终账户注册.<br>如果您没有收到邮件，请查看垃圾箱或邮箱拦截记录，如果仍未获得激活链接，请联系网站管理员.';
    _form.html('<h2 class="title signup-title mb30">' + title + '</h2>' +
               '<p id="default-tip">' + message + '</p>');
};

var _post = function () {
    // TODO
    // Register
    var url = Routes.signUp;
    var beforeSend = function () {
        _handleInputStatus(true);
        _handleSubmitBtnStatus(true);
        _submitting = true;
        _handleSubmitBtnHtml(true);
    };
    var finishRequest = function () {
        _handleInputStatus(false);
        _handleSubmitBtnStatus(false);
        _submitting = false;
        _handleSubmitBtnHtml(false);
    };
    var success = function (data, textStatus, xhr) {
        if(data.success && data.success == 1) {
            var redirect = Utils.getUrlPara('redirect') ? Utils.getAbsUrl(decodeURIComponent(Utils.getUrlPara('redirect'))) : Utils.getSiteUrl();
            popMsgbox.success({
                title: '请求注册成功',
                text: '请至您的邮箱查询并访问账户激活链接以最终完成账户的注册.',
                showConfirmButton: true
            });
            _handleSuccess();
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
            text: xhr.responseJSON.message
        });
        finishRequest();
    };
    $.post({
        url: url,
        data: _form.serialize(),
        dataType: 'json',
        beforeSend: beforeSend,
        success: success,
        error: error
    });
};

var pageSignUp = {
    init: function () {
        var body = $('body');
        // 绑定事件
        body.on('blur', '.local-signup>.input-container input', function () {
            _validate($(this));
            // console.debug('blur ' + $(this).attr('name'));
        }).on('keyup', '.local-signup>.input-container input', function () {
            var validateResult = _validate(null, false);
            _handleSubmitBtnStatus(!validateResult);
            if(validateResult) {
                _removeMsg();
            }
        });
        
        // 验证码点击刷新绑定
        body.on('click', 'img.captcha', function () {
            _handleCaptchaRefresh($(this));
        });
        
        // 按钮提交
        body.on('click', '.local-signup>#signup-btn', function () {
            if(_validate()) {
                _post();
            }
            
            return false;
        });
    }
};

export {pageSignUp};