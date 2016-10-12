/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/16 19:28
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

// 获取 url 中的 get 参数
var _getUrlPara = function (name ,url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
  var regexS = "[\\?&]" + name + "=([^&#]*)";
  var regex = new RegExp(regexS);
  var results = regex.exec(url);
  return results == null ? null : results[1];
};

// 获取站点 url
var _getSiteUrl = function () {
  return window.location.protocol + '//' + window.location.host;
};

// 获取绝对url地址
var _getAbsUrl = function (endpoint, base) {
  if(!base) {
    base = _getSiteUrl();
  }
  if(/^http([s]?)/.test(endpoint)) {
    return endpoint;
  }
  if(/^\/\//.test(endpoint)) {
    return window.location.protocol + endpoint;
  }

  if(/^\//.test(endpoint)) {
    return base + endpoint;
  }

  return base + '/' + endpoint;
};

// 获取站点API url
var _getAPIUrl = function (endpoint) {
  var base = (TT && TT.apiRoot) ? TT.apiRoot + 'v1' : window.location.protocol + '//' + window.location.host + '/api/v1';
  if(endpoint) {
    return base + endpoint;
  }
  return base;
};

// 手机号码验证
var _isPhoneNum = function (str) {
  //手机号以13, 15, 17, 18开头, 第3位不固定, 再尾随8位数字
  var reg = /^((13[0-9])|(147)|(15[^4,\D])|(17[0-9])|(18[0,0-9]))\d{8}$/;
  if(typeof str === 'string') return reg.test(str);
  return reg.test(str.toString());
};

// 邮箱地址验证
var _isEmail = function (str) {
  var reg = /[A-Z0-9a-z._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}/;
  if(typeof str === 'string') return reg.test(str);
  return reg.test(str.toString());
};

// 网址验证
var _isUrl = function (str) {
  //http或https协议类型网址
  var reg = /^((http)|(https))+:[^\s]+\.[^\s]*$/;
  if(typeof str === 'string') return reg.test(str);
  return reg.test(str.toString());
};

// 用户名验证
var _isValidUserName = function (str) {
  var reg = /^[A-Za-z][A-Za-z0-9_]{4,}$/; //用户名以字母开头,只能包含英文/数字/下划线,长度5及5以上
  return reg.test(str);
};


// AJAX请求的Data添加_wpnonce参数, REST_API需要
var _filterDataForRest = function (data) {
    data._wpnonce = TT._wpnonce; //TODO 如果TT._wpnonce不存在，去获取
    return data;
};


var Utils = {
    getUrlPara: _getUrlPara,
    getSiteUrl: _getSiteUrl,
    getAbsUrl: _getAbsUrl,
    getAPIUrl: _getAPIUrl,
    isPhoneNum: _isPhoneNum,
    isEmail: _isEmail,
    isUrl: _isUrl,
    isValidUserName: _isValidUserName,
    filterDataForRest: _filterDataForRest
};

export default Utils;