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

/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/17 22:02
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

import {popMsgbox, msgbox} from './msgbox';
import {Utils} from './utils';

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
        return false;
    } else if (!Utils.isValidUserName(_userLoginInput.val()) && !Utils.isEmail(_userLoginInput.val())) {
        if(showMsg) {
            msgbox.show('用户名必须以字母开头, 英文/数字/下划线组合', 'danger', _msgSibling);
        }
        return false;
    } else if (_userLoginInput.val().length < 5) {
        if(showMsg) {
            msgbox.show('账户长度至少为 5', 'danger', _msgSibling);
        }
        return false;
    }
    return true;
};

var _validateEmail = function (showMsg) {
    if(_emailInput.val() === '') {
        if(showMsg) {
            msgbox.show('请填写邮箱', 'danger', _msgSibling);
        }
        return false;
    } else if(!(Utils.isEmail(_emailInput.val()))) {
        if(showMsg) {
            msgbox.show('邮箱格式不正确', 'danger', _msgSibling);
        }
        return false;
    }
    return true;
};

var _validatePassword = function (showMsg) {
    if(_passwordInput.val() === '') {
        if(showMsg) {
            msgbox.show('请输入密码', 'danger', _msgSibling);
        }
        return false;
    } else if (_passwordInput.val().length < 6) {
        if(showMsg) {
            msgbox.show('密码长度至少为 6', 'danger', _msgSibling);
        }
        return false;
    }
    return true;
};

var _validateCaptcha = function (showMsg) {
    if(_captchaInput.val() === '') {
        if(showMsg) {
            msgbox.show('验证码不能为空', 'danger', _msgSibling);
        }
        return false;
    } else if (_captchaInput.val().length != 4) {
        if(showMsg) {
            msgbox.show('验证码长度必须为 4 位', 'danger', _msgSibling);
        }
        return false;
    }
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

var _post = function () {
    // TODO
    // Register
    var url = Utils.getAPIUrl('/users');
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
        _submitBtn.html().text(_submitBtnText);
    }
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