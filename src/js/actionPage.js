/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/31 23:39
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

require('./modules/loading');
// require('./modules/bootstrap-flat');
require('./modules/msgbox');

var jQuery = require('jquery');

console.debug('action page');

// SignIn - input focus/blur or submit
jQuery(function ($) {
  var SignIn = {
    userLoginInput: $('#user_login-input'),
    passwordInput: $('#password-input'),
    validate: (input) => {
      if(!input) {
        var userLoginValidated = SignIn.validateUserLogin();
        var passwordValidated = SignIn.validatePassword();
        return  userLoginValidated && passwordValidated;
      }else if(input.attr('name') === 'user_login'){
        return SignIn.validateUserLogin();
      }else if(input.attr('name') === 'password'){
        return SignIn.validatePassword();
      }
      return false;
    },
    validateUserLogin: () => {
      if(SignIn.userLoginInput.val().length < 6) {
        SignIn.showError(SignIn.userLoginInput, '账户长度至少为6');
        return false;
      } else if (SignIn.userLoginInput.val() === '') {
        SignIn.showError(SignIn.userLoginInput, '请输入账号');
        return false;
      }
      return true;
    },
    validatePassword: () => {
      if(SignIn.passwordInput.val().length < 6) {
        SignIn.showError(SignIn.passwordInput, '密码长度至少为6');
        return false;
      } else if (SignIn.passwordInput.val() === '') {
        SignIn.showError(SignIn.passwordInput, '请输入密码');
        return false;
      }
      return true;
    },
    showError: (input, msg)=>{
      var inputName = input.attr('name');
      switch (inputName) {
        case 'user_login':
          SignIn.removeError(SignIn.userLoginInput);
          break;
        case 'password':
          SignIn.removeError(SignIn.passwordInput);
          break;
      }
      input.parent().addClass('error').append('<div class="error-tip">' + msg + '</div>');
    },
    removeError: (input)=>{
      input.parent().removeClass('error').children('.error-tip').remove();
    },
    go: ()=>{
      // Login
      App.Msgbox.success({
        title: 'Yes',
        text: 'test text'
      });

      // msgbox 改造loading
    }
  };

  $('body').on('focus', '.input-container>input', function () {
    $(this).parent().addClass('input-focused');
  }).on('blur', '.input-container>input', function () {
    if(!$(this).val()) {
      $(this).parent().removeClass('input-focused');
    }
    SignIn.validate($(this));
  }).on('keyup', '.input-container>input', function (e) {
    SignIn.removeError($(this));
    if(e.keyCode === 13 && $(this).attr('name') === 'password' && SignIn.validate()) {
      SignIn.go();
    }
  });
}.call(this, jQuery));
