/**
 * Generated on Wed Nov 30 2016 23:33:17 GMT+0800 (中国标准时间) by Zhiyan
 *
 * @package   Tint
 * @version   v2.0.0
 * @author    Zhiyan <mail@webapproach.net>
 * @site      WebApproach <www.webapproach.net>
 * @copyright Copyright (c) 2014-2016, Zhiyan
 * @license   https://opensource.org/licenses/gpl-3.0.html GPL v3
 * @link      http://www.webapproach.net/tint.html
 *
**/
 
(function (modules) {
    var installedModules = {};
    function __webpack_require__(moduleId) {
        if (installedModules[moduleId])
            return installedModules[moduleId][['exports']];
        var module = installedModules[moduleId] = {
            exports: {},
            id: moduleId,
            loaded: false
        };
        modules[moduleId][['call']](module[['exports']], module, module[['exports']], __webpack_require__);
        module[['loaded']] = true;
        return module[['exports']];
    }
    __webpack_require__[['m']] = modules;
    __webpack_require__[['c']] = installedModules;
    __webpack_require__[['p']] = 'assets/js/';
    return __webpack_require__(0);
}([
    function (module, exports, __webpack_require__) {
        (function (jQuery) {
            'use strict';
            var _globalConfig = __webpack_require__(2);
            var _loading = __webpack_require__(8);
            __webpack_require__(9);
            jQuery(document)[['ready']](function ($) {
                (0, _loading[['handleLineLoading']])();
                var _redirectBtn = $('#linkBackHome');
                var _numSpan = _redirectBtn[['children']]('span.num');
                var _countNum = function _countNum(span) {
                    var sec = parseInt(span[['text']]());
                    if (sec - 1 <= 0) {
                        clearInterval(_interval);
                        _redirectBtn[['html']]('\u8df3\u8f6c\u4e2d...');
                        window[['location']][['href']] = _globalConfig[['Urls']][['site']];
                    } else {
                        span[['text']](sec - 1);
                    }
                };
                var _interval = setInterval(_countNum[['bind']](this, _numSpan), 1000);
            });
        }[['call']](exports, __webpack_require__(1)));
    },
    function (module, exports) {
        module[['exports']] = jQuery;
    },
    function (module, exports, __webpack_require__) {
        'use strict';
        Object[['defineProperty']](exports, '__esModule', { value: true });
        exports[['Classes']] = exports[['Urls']] = exports[['Routes']] = undefined;
        var _utils = __webpack_require__(3);
        var _utils2 = _interopRequireDefault(_utils);
        function _interopRequireDefault(obj) {
            return obj && obj[['__esModule']] ? obj : { default: obj };
        }
        var routes = {
            signIn: _utils2[['default']][['getAPIUrl']]('/session'),
            session: _utils2[['default']][['getAPIUrl']]('/session'),
            signUp: _utils2[['default']][['getAPIUrl']]('/users'),
            users: _utils2[['default']][['getAPIUrl']]('/users'),
            comments: _utils2[['default']][['getAPIUrl']]('/comments'),
            commentStars: _utils2[['default']][['getAPIUrl']]('/comment/stars'),
            postStars: _utils2[['default']][['getAPIUrl']]('/post/stars'),
            myFollower: _utils2[['default']][['getAPIUrl']]('/users/me/followers'),
            myFollowing: _utils2[['default']][['getAPIUrl']]('/users/me/following'),
            follower: _utils2[['default']][['getAPIUrl']]('/users/{{uid}}/followers'),
            following: _utils2[['default']][['getAPIUrl']]('/users/{{uid}}/following'),
            pm: _utils2[['default']][['getAPIUrl']]('/messages'),
            accountStatus: _utils2[['default']][['getAPIUrl']]('/users/status'),
            userMeta: _utils2[['default']][['getAPIUrl']]('/users/metas'),
            shoppingCart: _utils2[['default']][['getAPIUrl']]('/shoppingcart'),
            orders: _utils2[['default']][['getAPIUrl']]('/orders'),
            coupons: _utils2[['default']][['getAPIUrl']]('/coupons')
        };
        var urls = {
            site: _utils2[['default']][['getSiteUrl']](),
            signIn: _utils2[['default']][['getSiteUrl']]() + '/m/signin',
            cartCheckOut: _utils2[['default']][['getSiteUrl']]() + '/site/cartcheckout',
            checkOut: _utils2[['default']][['getSiteUrl']]() + '/site/checkout'
        };
        var classes = { appLoading: 'is-loadingApp' };
        exports[['Routes']] = routes;
        exports[['Urls']] = urls;
        exports[['Classes']] = classes;
    },
    function (module, exports, __webpack_require__) {
        (function (TT, $) {
            'use strict';
            Object[['defineProperty']](exports, '__esModule', { value: true });
            var _typeof = typeof Symbol === 'function' && typeof Symbol[['iterator']] === 'symbol' ? function (obj) {
                return typeof obj;
            } : function (obj) {
                return obj && typeof Symbol === 'function' && obj[['constructor']] === Symbol ? 'symbol' : typeof obj;
            };
            var _modalSignBox = __webpack_require__(5);
            var _modalSignBox2 = _interopRequireDefault(_modalSignBox);
            function _interopRequireDefault(obj) {
                return obj && obj[['__esModule']] ? obj : { default: obj };
            }
            var _getUrlPara = function _getUrlPara(name, url) {
                if (!url)
                    url = window[['location']][['href']];
                name = name[['replace']](/[\[]/, '\\[')[['replace']](/[\]]/, '\\]');
                var regexS = '[\\?&]' + name + '=([^&#]*)';
                var regex = new RegExp(regexS);
                var results = regex[['exec']](url);
                return results == null ? null : results[1];
            };
            var _getSiteUrl = function _getSiteUrl() {
                return window[['location']][['protocol']] + '//' + window[['location']][['host']];
            };
            var _getAbsUrl = function _getAbsUrl(endpoint, base) {
                if (!base) {
                    base = _getSiteUrl();
                }
                if (/^http([s]?)/[['test']](endpoint)) {
                    return endpoint;
                }
                if (/^\/\//[['test']](endpoint)) {
                    return window[['location']][['protocol']] + endpoint;
                }
                if (/^\//[['test']](endpoint)) {
                    return base + endpoint;
                }
                return base + '/' + endpoint;
            };
            var _getAPIUrl = function _getAPIUrl(endpoint) {
                var base = TT && TT[['apiRoot']] ? TT[['apiRoot']] + 'v1' : window[['location']][['protocol']] + '//' + window[['location']][['host']] + '/api/v1';
                if (endpoint) {
                    return base + endpoint;
                }
                return base;
            };
            var _addRedirectUrl = function _addRedirectUrl(base, redirect) {
                if (!base) {
                    base = _getSiteUrl();
                }
                if (/^(.*)\?(.*)$/[['test']](base)) {
                    return base + '&redirect=' + encodeURIComponent(redirect);
                }
                return base + '?redirect=' + encodeURIComponent(redirect);
            };
            var _isPhoneNum = function _isPhoneNum(str) {
                var reg = /^((13[0-9])|(147)|(15[^4,\D])|(17[0-9])|(18[0,0-9]))\d{8}$/;
                if (typeof str === 'string')
                    return reg[['test']](str);
                return reg[['test']](str[['toString']]());
            };
            var _isEmail = function _isEmail(str) {
                var reg = /[A-Z0-9a-z._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}/;
                if (typeof str === 'string')
                    return reg[['test']](str);
                return reg[['test']](str[['toString']]());
            };
            var _isUrl = function _isUrl(str) {
                var reg = /^((http)|(https))+:[^\s]+\.[^\s]*$/;
                if (typeof str === 'string')
                    return reg[['test']](str);
                return reg[['test']](str[['toString']]());
            };
            var _isValidUserName = function _isValidUserName(str) {
                var reg = /^[A-Za-z][A-Za-z0-9_]{4,}$/;
                return reg[['test']](str);
            };
            var _filterDataForRest = function _filterDataForRest(data) {
                if (typeof data == 'string') {
                    data += '&_wpnonce=' + TT[['_wpnonce']];
                } else if ((typeof data === 'undefined' ? 'undefined' : _typeof(data)) == 'object') {
                    data[['_wpnonce']] = TT[['_wpnonce']];
                }
                return data;
            };
            var _store = function _store(namespace, data) {
                if (data) {
                    return localStorage[['setItem']](namespace, JSON[['stringify']](data));
                }
                var store = localStorage[['getItem']](namespace);
                return store && JSON[['parse']](store) || {};
            };
            var _checkLogin = function _checkLogin() {
                if (TT && TT[['uid']] && parseInt(TT[['uid']]) > 0) {
                    return true;
                }
                _modalSignBox2[['default']][['show']]();
                return false;
            };
            var _showFullLoader = function _showFullLoader(icon, text) {
                var loaderContainer = $('#fullLoader-container');
                if (!loaderContainer[['length']]) {
                    $('<div id="fullLoader-container"><div class="loader"><i class="tico ' + icon + '"></i></div><p>' + text + '</p></div>')[['appendTo']]('body')[['fadeIn']]();
                } else {
                    loaderContainer[['children']]('p')[['text']](text);
                    var iconEle = loaderContainer[['find']]('i');
                    iconEle[['attr']]('class', 'tico ' + icon);
                    loaderContainer[['fadeIn']]();
                }
            };
            var _hideFullLoader = function _hideFullLoader() {
                var loaderContainer = $('#fullLoader-container');
                if (loaderContainer[['length']]) {
                    loaderContainer[['fadeOut']](500, function () {
                        loaderContainer[['remove']]();
                    });
                }
            };
            var Utils = {
                getUrlPara: _getUrlPara,
                getSiteUrl: _getSiteUrl,
                getAbsUrl: _getAbsUrl,
                getAPIUrl: _getAPIUrl,
                addRedirectUrl: _addRedirectUrl,
                isPhoneNum: _isPhoneNum,
                isEmail: _isEmail,
                isUrl: _isUrl,
                isValidUserName: _isValidUserName,
                filterDataForRest: _filterDataForRest,
                store: _store,
                checkLogin: _checkLogin,
                showFullLoader: _showFullLoader,
                hideFullLoader: _hideFullLoader
            };
            exports[['default']] = Utils;
        }[['call']](exports, __webpack_require__(4), __webpack_require__(1)));
    },
    function (module, exports) {
        module[['exports']] = TT;
    },
    function (module, exports, __webpack_require__) {
        (function ($) {
            'use strict';
            Object[['defineProperty']](exports, '__esModule', { value: true });
            var _utils = __webpack_require__(3);
            var _utils2 = _interopRequireDefault(_utils);
            var _globalConfig = __webpack_require__(2);
            var _msgbox = __webpack_require__(6);
            function _interopRequireDefault(obj) {
                return obj && obj[['__esModule']] ? obj : { default: obj };
            }
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
            var _validate = function _validate(input) {
                if (!input) {
                    var userLoginValidated = _validateUserLogin();
                    var passwordValidated = _validatePassword();
                    return userLoginValidated && passwordValidated;
                } else if (input[['attr']]('name') === 'user_login') {
                    return _validateUserLogin();
                } else if (input[['attr']]('name') === 'password') {
                    return _validatePassword();
                }
                return false;
            };
            var _validateUserLogin = function _validateUserLogin() {
                if (_userLoginInput[['val']]() === '') {
                    _showError(_userLoginInput, '\u8bf7\u8f93\u5165\u8d26\u53f7');
                    return false;
                } else if (!_utils2[['default']][['isValidUserName']](_userLoginInput[['val']]()) && !_utils2[['default']][['isEmail']](_userLoginInput[['val']]())) {
                    _showError(_userLoginInput, '\u90ae\u7bb1\u6216\u5b57\u6bcd\u5f00\u5934\u7528\u6237\u540d');
                    return false;
                } else if (_userLoginInput[['val']]()[['length']] < 5) {
                    _showError(_userLoginInput, '\u8d26\u6237\u957f\u5ea6\u81f3\u5c11\u4e3a5');
                    return false;
                }
                _removeError(_userLoginInput);
                return true;
            };
            var _validatePassword = function _validatePassword() {
                if (_passwordInput[['val']]() === '') {
                    _showError(_passwordInput, '\u8bf7\u8f93\u5165\u5bc6\u7801');
                    return false;
                } else if (_passwordInput[['val']]()[['length']] < 6) {
                    _showError(_passwordInput, '\u5bc6\u7801\u957f\u5ea6\u81f3\u5c11\u4e3a6');
                    return false;
                }
                _removeError(_passwordInput);
                return true;
            };
            var _showError = function _showError(input, msg) {
                var inputName = input[['attr']]('name');
                switch (inputName) {
                case 'user_login':
                    _removeError(_userLoginInput);
                    break;
                case 'password':
                    _removeError(_passwordInput);
                    break;
                }
                input[['next']](_tipSel)[['text']](msg)[['show']]();
            };
            var _removeError = function _removeError(input) {
                input[['next']](_tipSel)[['hide']]()[['text']]('');
            };
            var _post = function _post(submitBtn) {
                var url = _globalConfig[['Routes']][['signIn']];
                var beforeSend = function beforeSend() {
                    _modalSignBox[['addClass']]('submitting');
                    _userLoginInput[['prop']]('disabled', true);
                    _passwordInput[['prop']]('disabled', true);
                    _originSubmitBtnText = submitBtn[['text']]();
                    submitBtn[['prop']]('disabled', true)[['html']](_spinner);
                    _submitting = true;
                };
                var finishRequest = function finishRequest() {
                    _modalSignBox[['removeClass']]('submitting');
                    _userLoginInput[['prop']]('disabled', false);
                    _passwordInput[['prop']]('disabled', false);
                    submitBtn[['text']](_originSubmitBtnText)[['prop']]('disabled', false);
                    _submitting = false;
                };
                var success = function success(data, textStatus, xhr) {
                    if (data[['success']] && data[['success']] == 1) {
                        var redirect = _utils2[['default']][['getUrlPara']]('redirect') ? _utils2[['default']][['getAbsUrl']](decodeURIComponent(_utils2[['default']][['getUrlPara']]('redirect'))) : '';
                        _msgbox[['popMsgbox']][['success']]({
                            title: '\u767b\u5f55\u6210\u529f',
                            text: redirect ? '\u5c06\u5728 2s \u5185\u8df3\u8f6c\u81f3 ' + redirect : '\u5c06\u5728 2s \u5185\u5237\u65b0\u9875\u9762',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(function () {
                            window[['location']][['href']] = redirect ? redirect : location[['href']];
                        }, 2000);
                    } else {
                        _msgbox[['popMsgbox']][['error']]({
                            title: '\u767b\u5f55\u9519\u8bef',
                            text: data[['message']]
                        });
                        finishRequest();
                    }
                };
                var error = function error(xhr, textStatus, err) {
                    _msgbox[['popMsgbox']][['error']]({
                        title: '\u8bf7\u6c42\u767b\u5f55\u5931\u8d25, \u8bf7\u91cd\u65b0\u5c1d\u8bd5',
                        text: xhr[['responseJSON']] ? xhr[['responseJSON']][['message']] : xhr[['responseText']]
                    });
                    finishRequest();
                };
                $[['post']]({
                    url: url,
                    data: _utils2[['default']][['filterDataForRest']](_modalSignBox[['serialize']]()),
                    dataType: 'json',
                    beforeSend: beforeSend,
                    success: success,
                    error: error
                });
            };
            var _showBox = function _showBox() {
                if ($(window)[['width']]() < 640) {
                    window[['location']][['href']] = _utils2[['default']][['addRedirectUrl']](_globalConfig[['Urls']][['signIn']], window[['location']][['href']]);
                    return;
                }
                _modalSignBox[['modal']]('show');
            };
            var _hideBox = function _hideBox() {
                _modalSignBox[['modal']]('hide');
            };
            var ModalSignBox = {
                init: function init() {
                    _body[['on']]('click', '.modal-backdrop', function () {
                        _hideBox();
                    });
                    _userLoginInput[['on']]('input', function () {
                        _validate($(this));
                    });
                    _passwordInput[['on']]('input', function () {
                        _validate($(this));
                    });
                    _body[['on']]('click', _submitBtnSel, function () {
                        if (_validate()) {
                            _post($(this));
                        }
                    });
                },
                show: function show() {
                    _showBox();
                },
                hide: function hide() {
                    _hideBox();
                }
            };
            exports[['default']] = ModalSignBox;
        }[['call']](exports, __webpack_require__(1)));
    },
    function (module, exports, __webpack_require__) {
        (function (jQuery, $) {
            'use strict';
            Object[['defineProperty']](exports, '__esModule', { value: true });
            var swal = __webpack_require__(7);
            var app = window[['App']] || (window[['App']] = {});
            var popMsgbox = app[['PopMsgbox']] || (app[['PopMsgbox']] = {});
            var popMsgbox = {};
            popMsgbox[['basic']] = function (options) {
                options[['customClass']] = 'swal-basic';
                options[['type']] = '';
                options[['confirmButtonColor']] = '#1abc9c';
                options[['confirmButtonClass']] = 'btn-primary';
                swal(options);
            };
            popMsgbox[['alert']] = popMsgbox[['warning']] = function (options, callback) {
                options[['customClass']] = 'swal-alert';
                options[['type']] = 'warning';
                options[['confirmButtonColor']] = '#3498db';
                options[['confirmButtonClass']] = 'btn-info';
                swal(options, callback);
            };
            popMsgbox[['error']] = function (options, callback) {
                options[['customClass']] = 'swal-error';
                options[['type']] = 'error';
                options[['confirmButtonColor']] = '#e74c3c';
                options[['confirmButtonClass']] = 'btn-danger';
                swal(options, callback);
            };
            popMsgbox[['success']] = function (options, callback) {
                options[['customClass']] = 'swal-success';
                options[['type']] = 'success';
                options[['confirmButtonColor']] = '#2ecc71';
                options[['confirmButtonClass']] = 'btn-success';
                swal(options, callback);
            };
            popMsgbox[['info']] = function (options, callback) {
                options[['customClass']] = 'swal-info';
                options[['type']] = 'info';
                options[['confirmButtonColor']] = '#3498db';
                options[['confirmButtonClass']] = 'btn-info';
                swal(options, callback);
            };
            popMsgbox[['input']] = function (options, callback) {
                options[['customClass']] = 'swal-input';
                options[['type']] = 'input';
                options[['confirmButtonColor']] = '#34495e';
                options[['confirmButtonClass']] = 'btn-inverse';
                options[['animation']] = options[['animation']] ? options[['animation']] : 'slide-from-top';
                swal(options, callback);
            };
            popMsgbox[['init']] = function () {
                jQuery(document)[['on']]('click.tt.popMsgbox.show', '[data-toggle="msgbox"]', function (e) {
                    var $this = $(this);
                    var title = $this[['attr']]('title');
                    var text = $this[['data']]('content');
                    var type = $this[['data']]('msgtype') ? $this[['data']]('msgtype') : 'info';
                    var animation = $this[['data']]('animation') ? $this[['data']]('animation') : 'pop';
                    popMsgbox[type]({
                        title: title,
                        text: text,
                        type: type,
                        animation: animation,
                        confirmButtonText: 'OK',
                        showCancelButton: true
                    });
                });
            };
            app[['PopMsgbox']] = popMsgbox;
            window[['App']] = app;
            var msgbox = {};
            msgbox[['show']] = function (str, type, beforeSel) {
                var $msg = $('.msg'), tpl = '<button type="button" class="btn-close">\xD7</button><ul><li></li></ul>';
                var $txt = $(tpl);
                if ($msg[['length']] === 0) {
                    $msg = $('<div class="msg"></div>');
                    beforeSel[['before']]($msg);
                } else {
                    $msg[['find']]('li')[['remove']]();
                }
                $txt[['find']]('li')[['text']](str);
                $msg[['append']]($txt)[['addClass']](type)[['show']]();
            };
            msgbox[['init']] = function () {
                $('body')[['on']]('click.tt.msgbox.close', '.msg > .btn-close', function () {
                    var $this = $(this), $msgbox = $this[['parent']]();
                    $msgbox[['slideUp']](function () {
                        $msgbox[['remove']]();
                    });
                });
            };
            exports[['popMsgbox']] = popMsgbox;
            exports[['msgbox']] = msgbox;
        }[['call']](exports, __webpack_require__(1), __webpack_require__(1)));
    },
    function (module, exports, __webpack_require__) {
        var require;
        var require;
        var __WEBPACK_AMD_DEFINE_RESULT__;
        'use strict';
        var _typeof = typeof Symbol === 'function' && typeof Symbol[['iterator']] === 'symbol' ? function (obj) {
            return typeof obj;
        } : function (obj) {
            return obj && typeof Symbol === 'function' && obj[['constructor']] === Symbol ? 'symbol' : typeof obj;
        };
        (function (window, document, undefined) {
            (function e(t, n, r) {
                function s(o, u) {
                    if (!n[o]) {
                        if (!t[o]) {
                            var a = typeof require == 'function' && require;
                            if (!u && a)
                                return require(o, !0);
                            if (i)
                                return i(o, !0);
                            var f = new Error('Cannot find module \'' + o + '\'');
                            throw f[['code']] = 'MODULE_NOT_FOUND', f;
                        }
                        var l = n[o] = { exports: {} };
                        t[o][0][['call']](l[['exports']], function (e) {
                            var n = t[o][1][e];
                            return s(n ? n : e);
                        }, l, l[['exports']], e, t, n, r);
                    }
                    return n[o][['exports']];
                }
                var i = typeof require == 'function' && require;
                for (var o = 0; o < r[['length']]; o++) {
                    s(r[o]);
                }
                return s;
            }({
                1: [
                    function (require, module, exports) {
                        var _interopRequireWildcard = function _interopRequireWildcard(obj) {
                            return obj && obj[['__esModule']] ? obj : { 'default': obj };
                        };
                        Object[['defineProperty']](exports, '__esModule', { value: true });
                        var _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation = require('./modules/handle-dom');
                        var _extend$hexToRgb$isIE8$logStr$colorLuminance = require('./modules/utils');
                        var _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition = require('./modules/handle-swal-dom');
                        var _handleButton$handleConfirm$handleCancel = require('./modules/handle-click');
                        var _handleKeyDown = require('./modules/handle-key');
                        var _handleKeyDown2 = _interopRequireWildcard(_handleKeyDown);
                        var _defaultParams = require('./modules/default-params');
                        var _defaultParams2 = _interopRequireWildcard(_defaultParams);
                        var _setParameters = require('./modules/set-params');
                        var _setParameters2 = _interopRequireWildcard(_setParameters);
                        var previousWindowKeyDown;
                        var lastFocusedButton;
                        var sweetAlert, swal;
                        exports['default'] = sweetAlert = swal = function (_swal) {
                            function swal() {
                                return _swal[['apply']](this, arguments);
                            }
                            swal[['toString']] = function () {
                                return _swal[['toString']]();
                            };
                            return swal;
                        }(function () {
                            var customizations = arguments[0];
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['addClass']](document[['body']], 'stop-scrolling');
                            _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition[['resetInput']]();
                            function argumentOrDefault(key) {
                                var args = customizations;
                                return args[key] === undefined ? _defaultParams2['default'][key] : args[key];
                            }
                            if (customizations === undefined) {
                                _extend$hexToRgb$isIE8$logStr$colorLuminance[['logStr']]('SweetAlert expects at least 1 attribute!');
                                return false;
                            }
                            var params = _extend$hexToRgb$isIE8$logStr$colorLuminance[['extend']]({}, _defaultParams2['default']);
                            switch (typeof customizations === 'undefined' ? 'undefined' : _typeof(customizations)) {
                            case 'string':
                                params[['title']] = customizations;
                                params[['text']] = arguments[1] || '';
                                params[['type']] = arguments[2] || '';
                                break;
                            case 'object':
                                if (customizations[['title']] === undefined) {
                                    _extend$hexToRgb$isIE8$logStr$colorLuminance[['logStr']]('Missing "title" argument!');
                                    return false;
                                }
                                params[['title']] = customizations[['title']];
                                for (var customName in _defaultParams2['default']) {
                                    params[customName] = argumentOrDefault(customName);
                                }
                                params[['confirmButtonText']] = params[['showCancelButton']] ? 'Confirm' : _defaultParams2['default'][['confirmButtonText']];
                                params[['confirmButtonText']] = argumentOrDefault('confirmButtonText');
                                params[['doneFunction']] = arguments[1] || null;
                                break;
                            default:
                                _extend$hexToRgb$isIE8$logStr$colorLuminance[['logStr']]('Unexpected type of argument! Expected "string" or "object", got ' + (typeof customizations === 'undefined' ? 'undefined' : _typeof(customizations)));
                                return false;
                            }
                            _setParameters2['default'](params);
                            _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition[['fixVerticalPosition']]();
                            _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition[['openModal']](arguments[1]);
                            var modal = _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition[['getModal']]();
                            var $buttons = modal[['querySelectorAll']]('button');
                            var buttonEvents = [
                                'onclick',
                                'onmouseover',
                                'onmouseout',
                                'onmousedown',
                                'onmouseup',
                                'onfocus'
                            ];
                            var onButtonEvent = function onButtonEvent(e) {
                                return _handleButton$handleConfirm$handleCancel[['handleButton']](e, params, modal);
                            };
                            for (var btnIndex = 0; btnIndex < $buttons[['length']]; btnIndex++) {
                                for (var evtIndex = 0; evtIndex < buttonEvents[['length']]; evtIndex++) {
                                    var btnEvt = buttonEvents[evtIndex];
                                    $buttons[btnIndex][btnEvt] = onButtonEvent;
                                }
                            }
                            _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition[['getOverlay']]()[['onclick']] = onButtonEvent;
                            previousWindowKeyDown = window[['onkeydown']];
                            var onKeyEvent = function onKeyEvent(e) {
                                return _handleKeyDown2['default'](e, params, modal);
                            };
                            window[['onkeydown']] = onKeyEvent;
                            window[['onfocus']] = function () {
                                setTimeout(function () {
                                    if (lastFocusedButton !== undefined) {
                                        lastFocusedButton[['focus']]();
                                        lastFocusedButton = undefined;
                                    }
                                }, 0);
                            };
                            swal[['enableButtons']]();
                        });
                        sweetAlert[['setDefaults']] = swal[['setDefaults']] = function (userParams) {
                            if (!userParams) {
                                throw new Error('userParams is required');
                            }
                            if ((typeof userParams === 'undefined' ? 'undefined' : _typeof(userParams)) !== 'object') {
                                throw new Error('userParams has to be a object');
                            }
                            _extend$hexToRgb$isIE8$logStr$colorLuminance[['extend']](_defaultParams2['default'], userParams);
                        };
                        sweetAlert[['close']] = swal[['close']] = function () {
                            var modal = _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition[['getModal']]();
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['fadeOut']](_sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition[['getOverlay']](), 5);
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['fadeOut']](modal, 5);
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['removeClass']](modal, 'showSweetAlert');
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['addClass']](modal, 'hideSweetAlert');
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['removeClass']](modal, 'visible');
                            var $successIcon = modal[['querySelector']]('.sa-icon.sa-success');
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['removeClass']]($successIcon, 'animate');
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['removeClass']]($successIcon[['querySelector']]('.sa-tip'), 'animateSuccessTip');
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['removeClass']]($successIcon[['querySelector']]('.sa-long'), 'animateSuccessLong');
                            var $errorIcon = modal[['querySelector']]('.sa-icon.sa-error');
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['removeClass']]($errorIcon, 'animateErrorIcon');
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['removeClass']]($errorIcon[['querySelector']]('.sa-x-mark'), 'animateXMark');
                            var $warningIcon = modal[['querySelector']]('.sa-icon.sa-warning');
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['removeClass']]($warningIcon, 'pulseWarning');
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['removeClass']]($warningIcon[['querySelector']]('.sa-body'), 'pulseWarningIns');
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['removeClass']]($warningIcon[['querySelector']]('.sa-dot'), 'pulseWarningIns');
                            setTimeout(function () {
                                var customClass = modal[['getAttribute']]('data-custom-class');
                                _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['removeClass']](modal, customClass);
                            }, 300);
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['removeClass']](document[['body']], 'stop-scrolling');
                            window[['onkeydown']] = previousWindowKeyDown;
                            if (window[['previousActiveElement']]) {
                                window[['previousActiveElement']][['focus']]();
                            }
                            lastFocusedButton = undefined;
                            clearTimeout(modal[['timeout']]);
                            return true;
                        };
                        sweetAlert[['showInputError']] = swal[['showInputError']] = function (errorMessage) {
                            var modal = _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition[['getModal']]();
                            var $errorIcon = modal[['querySelector']]('.sa-input-error');
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['addClass']]($errorIcon, 'show');
                            var $errorContainer = modal[['querySelector']]('.sa-error-container');
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['addClass']]($errorContainer, 'show');
                            $errorContainer[['querySelector']]('p')[['innerHTML']] = errorMessage;
                            setTimeout(function () {
                                sweetAlert[['enableButtons']]();
                            }, 1);
                            modal[['querySelector']]('input')[['focus']]();
                        };
                        sweetAlert[['resetInputError']] = swal[['resetInputError']] = function (event) {
                            if (event && event[['keyCode']] === 13) {
                                return false;
                            }
                            var $modal = _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition[['getModal']]();
                            var $errorIcon = $modal[['querySelector']]('.sa-input-error');
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['removeClass']]($errorIcon, 'show');
                            var $errorContainer = $modal[['querySelector']]('.sa-error-container');
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide$isDescendant$getTopMargin$fadeIn$fadeOut$fireClick$stopEventPropagation[['removeClass']]($errorContainer, 'show');
                        };
                        sweetAlert[['disableButtons']] = swal[['disableButtons']] = function (event) {
                            var modal = _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition[['getModal']]();
                            var $confirmButton = modal[['querySelector']]('button.confirm');
                            var $cancelButton = modal[['querySelector']]('button.cancel');
                            $confirmButton[['disabled']] = true;
                            $cancelButton[['disabled']] = true;
                        };
                        sweetAlert[['enableButtons']] = swal[['enableButtons']] = function (event) {
                            var modal = _sweetAlertInitialize$getModal$getOverlay$getInput$setFocusStyle$openModal$resetInput$fixVerticalPosition[['getModal']]();
                            var $confirmButton = modal[['querySelector']]('button.confirm');
                            var $cancelButton = modal[['querySelector']]('button.cancel');
                            $confirmButton[['disabled']] = false;
                            $cancelButton[['disabled']] = false;
                        };
                        if (typeof window !== 'undefined') {
                            window[['sweetAlert']] = window[['swal']] = sweetAlert;
                        } else {
                            _extend$hexToRgb$isIE8$logStr$colorLuminance[['logStr']]('SweetAlert is a frontend module!');
                        }
                        module[['exports']] = exports['default'];
                    },
                    {
                        './modules/default-params': 2,
                        './modules/handle-click': 3,
                        './modules/handle-dom': 4,
                        './modules/handle-key': 5,
                        './modules/handle-swal-dom': 6,
                        './modules/set-params': 8,
                        './modules/utils': 9
                    }
                ],
                2: [
                    function (require, module, exports) {
                        'use strict';
                        Object[['defineProperty']](exports, '__esModule', { value: true });
                        var defaultParams = {
                            title: '',
                            text: '',
                            type: null,
                            allowOutsideClick: false,
                            showConfirmButton: true,
                            showCancelButton: false,
                            closeOnConfirm: true,
                            closeOnCancel: true,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#8CD4F5',
                            confirmButtonClass: 'btn-inverse',
                            cancelButtonText: 'Cancel',
                            imageUrl: null,
                            imageSize: null,
                            timer: null,
                            customClass: '',
                            html: false,
                            animation: true,
                            allowEscapeKey: true,
                            inputType: 'text',
                            inputPlaceholder: '',
                            inputValue: '',
                            showLoaderOnConfirm: false
                        };
                        exports['default'] = defaultParams;
                        module[['exports']] = exports['default'];
                    },
                    {}
                ],
                3: [
                    function (require, module, exports) {
                        'use strict';
                        Object[['defineProperty']](exports, '__esModule', { value: true });
                        var _colorLuminance = require('./utils');
                        var _getModal = require('./handle-swal-dom');
                        var _hasClass$isDescendant = require('./handle-dom');
                        var handleButton = function handleButton(event, params, modal) {
                            var e = event || window[['event']];
                            var target = e[['target']] || e[['srcElement']];
                            var targetedConfirm = target[['className']][['indexOf']]('confirm') !== -1;
                            var targetedOverlay = target[['className']][['indexOf']]('sweet-overlay') !== -1;
                            var modalIsVisible = _hasClass$isDescendant[['hasClass']](modal, 'visible');
                            var doneFunctionExists = params[['doneFunction']] && modal[['getAttribute']]('data-has-done-function') === 'true';
                            var normalColor, hoverColor, activeColor;
                            if (targetedConfirm && params[['confirmButtonColor']]) {
                                normalColor = params[['confirmButtonColor']];
                                hoverColor = _colorLuminance[['colorLuminance']](normalColor, -0.04);
                                activeColor = _colorLuminance[['colorLuminance']](normalColor, -0.14);
                            }
                            function shouldSetConfirmButtonColor(color) {
                                if (targetedConfirm && params[['confirmButtonColor']]) {
                                    target[['style']][['backgroundColor']] = color;
                                }
                            }
                            switch (e[['type']]) {
                            case 'mouseover':
                                shouldSetConfirmButtonColor(hoverColor);
                                break;
                            case 'mouseout':
                                shouldSetConfirmButtonColor(normalColor);
                                break;
                            case 'mousedown':
                                shouldSetConfirmButtonColor(activeColor);
                                break;
                            case 'mouseup':
                                shouldSetConfirmButtonColor(hoverColor);
                                break;
                            case 'focus':
                                var $confirmButton = modal[['querySelector']]('button.confirm');
                                var $cancelButton = modal[['querySelector']]('button.cancel');
                                if (targetedConfirm) {
                                    $cancelButton[['style']][['boxShadow']] = 'none';
                                } else {
                                    $confirmButton[['style']][['boxShadow']] = 'none';
                                }
                                break;
                            case 'click':
                                var clickedOnModal = modal === target;
                                var clickedOnModalChild = _hasClass$isDescendant[['isDescendant']](modal, target);
                                if (!clickedOnModal && !clickedOnModalChild && modalIsVisible && !params[['allowOutsideClick']]) {
                                    break;
                                }
                                if (targetedConfirm && doneFunctionExists && modalIsVisible) {
                                    handleConfirm(modal, params);
                                } else if (doneFunctionExists && modalIsVisible || targetedOverlay) {
                                    handleCancel(modal, params);
                                } else if (_hasClass$isDescendant[['isDescendant']](modal, target) && target[['tagName']] === 'BUTTON') {
                                    sweetAlert[['close']]();
                                }
                                break;
                            }
                        };
                        var handleConfirm = function handleConfirm(modal, params) {
                            var callbackValue = true;
                            if (_hasClass$isDescendant[['hasClass']](modal, 'show-input')) {
                                callbackValue = modal[['querySelector']]('input')[['value']];
                                if (!callbackValue) {
                                    callbackValue = '';
                                }
                            }
                            params[['doneFunction']](callbackValue);
                            if (params[['closeOnConfirm']]) {
                                sweetAlert[['close']]();
                            }
                            if (params[['showLoaderOnConfirm']]) {
                                sweetAlert[['disableButtons']]();
                            }
                        };
                        var handleCancel = function handleCancel(modal, params) {
                            var functionAsStr = String(params[['doneFunction']])[['replace']](/\s/g, '');
                            var functionHandlesCancel = functionAsStr[['substring']](0, 9) === 'function(' && functionAsStr[['substring']](9, 10) !== ')';
                            if (functionHandlesCancel) {
                                params[['doneFunction']](false);
                            }
                            if (params[['closeOnCancel']]) {
                                sweetAlert[['close']]();
                            }
                        };
                        exports['default'] = {
                            handleButton: handleButton,
                            handleConfirm: handleConfirm,
                            handleCancel: handleCancel
                        };
                        module[['exports']] = exports['default'];
                    },
                    {
                        './handle-dom': 4,
                        './handle-swal-dom': 6,
                        './utils': 9
                    }
                ],
                4: [
                    function (require, module, exports) {
                        'use strict';
                        Object[['defineProperty']](exports, '__esModule', { value: true });
                        var hasClass = function hasClass(elem, className) {
                            return new RegExp(' ' + className + ' ')[['test']](' ' + elem[['className']] + ' ');
                        };
                        var addClass = function addClass(elem, className) {
                            if (!hasClass(elem, className)) {
                                elem[['className']] += ' ' + className;
                            }
                        };
                        var removeClass = function removeClass(elem, className) {
                            var newClass = ' ' + elem[['className']][['replace']](/[\t\r\n]/g, ' ') + ' ';
                            if (hasClass(elem, className)) {
                                while (newClass[['indexOf']](' ' + className + ' ') >= 0) {
                                    newClass = newClass[['replace']](' ' + className + ' ', ' ');
                                }
                                elem[['className']] = newClass[['replace']](/^\s+|\s+$/g, '');
                            }
                        };
                        var escapeHtml = function escapeHtml(str) {
                            var div = document[['createElement']]('div');
                            div[['appendChild']](document[['createTextNode']](str));
                            return div[['innerHTML']];
                        };
                        var _show = function _show(elem) {
                            elem[['style']][['opacity']] = '';
                            elem[['style']][['display']] = 'block';
                        };
                        var show = function show(elems) {
                            if (elems && !elems[['length']]) {
                                return _show(elems);
                            }
                            for (var i = 0; i < elems[['length']]; ++i) {
                                _show(elems[i]);
                            }
                        };
                        var _hide = function _hide(elem) {
                            elem[['style']][['opacity']] = '';
                            elem[['style']][['display']] = 'none';
                        };
                        var hide = function hide(elems) {
                            if (elems && !elems[['length']]) {
                                return _hide(elems);
                            }
                            for (var i = 0; i < elems[['length']]; ++i) {
                                _hide(elems[i]);
                            }
                        };
                        var isDescendant = function isDescendant(parent, child) {
                            var node = child[['parentNode']];
                            while (node !== null) {
                                if (node === parent) {
                                    return true;
                                }
                                node = node[['parentNode']];
                            }
                            return false;
                        };
                        var getTopMargin = function getTopMargin(elem) {
                            elem[['style']][['left']] = '-9999px';
                            elem[['style']][['display']] = 'block';
                            var height = elem[['clientHeight']], padding;
                            if (typeof getComputedStyle !== 'undefined') {
                                padding = parseInt(getComputedStyle(elem)[['getPropertyValue']]('padding-top'), 10);
                            } else {
                                padding = parseInt(elem[['currentStyle']][['padding']]);
                            }
                            elem[['style']][['left']] = '';
                            elem[['style']][['display']] = 'none';
                            return '-' + parseInt((height + padding) / 2) + 'px';
                        };
                        var fadeIn = function fadeIn(elem, interval) {
                            if (+elem[['style']][['opacity']] < 1) {
                                interval = interval || 16;
                                elem[['style']][['opacity']] = 0;
                                elem[['style']][['display']] = 'block';
                                var last = +new Date();
                                var tick = function (_tick) {
                                    function tick() {
                                        return _tick[['apply']](this, arguments);
                                    }
                                    tick[['toString']] = function () {
                                        return _tick[['toString']]();
                                    };
                                    return tick;
                                }(function () {
                                    elem[['style']][['opacity']] = +elem[['style']][['opacity']] + (new Date() - last) / 100;
                                    last = +new Date();
                                    if (+elem[['style']][['opacity']] < 1) {
                                        setTimeout(tick, interval);
                                    }
                                });
                                tick();
                            }
                            elem[['style']][['display']] = 'block';
                        };
                        var fadeOut = function fadeOut(elem, interval) {
                            interval = interval || 16;
                            elem[['style']][['opacity']] = 1;
                            var last = +new Date();
                            var tick = function (_tick2) {
                                function tick() {
                                    return _tick2[['apply']](this, arguments);
                                }
                                tick[['toString']] = function () {
                                    return _tick2[['toString']]();
                                };
                                return tick;
                            }(function () {
                                elem[['style']][['opacity']] = +elem[['style']][['opacity']] - (new Date() - last) / 100;
                                last = +new Date();
                                if (+elem[['style']][['opacity']] > 0) {
                                    setTimeout(tick, interval);
                                } else {
                                    elem[['style']][['display']] = 'none';
                                }
                            });
                            tick();
                        };
                        var fireClick = function fireClick(node) {
                            if (typeof MouseEvent === 'function') {
                                var mevt = new MouseEvent('click', {
                                    view: window,
                                    bubbles: false,
                                    cancelable: true
                                });
                                node[['dispatchEvent']](mevt);
                            } else if (document[['createEvent']]) {
                                var evt = document[['createEvent']]('MouseEvents');
                                evt[['initEvent']]('click', false, false);
                                node[['dispatchEvent']](evt);
                            } else if (document[['createEventObject']]) {
                                node[['fireEvent']]('onclick');
                            } else if (typeof node[['onclick']] === 'function') {
                                node[['onclick']]();
                            }
                        };
                        var stopEventPropagation = function stopEventPropagation(e) {
                            if (typeof e[['stopPropagation']] === 'function') {
                                e[['stopPropagation']]();
                                e[['preventDefault']]();
                            } else if (window[['event']] && window[['event']][['hasOwnProperty']]('cancelBubble')) {
                                window[['event']][['cancelBubble']] = true;
                            }
                        };
                        exports[['hasClass']] = hasClass;
                        exports[['addClass']] = addClass;
                        exports[['removeClass']] = removeClass;
                        exports[['escapeHtml']] = escapeHtml;
                        exports[['_show']] = _show;
                        exports[['show']] = show;
                        exports[['_hide']] = _hide;
                        exports[['hide']] = hide;
                        exports[['isDescendant']] = isDescendant;
                        exports[['getTopMargin']] = getTopMargin;
                        exports[['fadeIn']] = fadeIn;
                        exports[['fadeOut']] = fadeOut;
                        exports[['fireClick']] = fireClick;
                        exports[['stopEventPropagation']] = stopEventPropagation;
                    },
                    {}
                ],
                5: [
                    function (require, module, exports) {
                        'use strict';
                        Object[['defineProperty']](exports, '__esModule', { value: true });
                        var _stopEventPropagation$fireClick = require('./handle-dom');
                        var _setFocusStyle = require('./handle-swal-dom');
                        var handleKeyDown = function handleKeyDown(event, params, modal) {
                            var e = event || window[['event']];
                            var keyCode = e[['keyCode']] || e[['which']];
                            var $okButton = modal[['querySelector']]('button.confirm');
                            var $cancelButton = modal[['querySelector']]('button.cancel');
                            var $modalButtons = modal[['querySelectorAll']]('button[tabindex]');
                            if ([
                                    9,
                                    13,
                                    32,
                                    27
                                ][['indexOf']](keyCode) === -1) {
                                return;
                            }
                            var $targetElement = e[['target']] || e[['srcElement']];
                            var btnIndex = -1;
                            for (var i = 0; i < $modalButtons[['length']]; i++) {
                                if ($targetElement === $modalButtons[i]) {
                                    btnIndex = i;
                                    break;
                                }
                            }
                            if (keyCode === 9) {
                                if (btnIndex === -1) {
                                    $targetElement = $okButton;
                                } else {
                                    if (btnIndex === $modalButtons[['length']] - 1) {
                                        $targetElement = $modalButtons[0];
                                    } else {
                                        $targetElement = $modalButtons[btnIndex + 1];
                                    }
                                }
                                _stopEventPropagation$fireClick[['stopEventPropagation']](e);
                                $targetElement[['focus']]();
                                if (params[['confirmButtonColor']]) {
                                    _setFocusStyle[['setFocusStyle']]($targetElement, params[['confirmButtonColor']]);
                                }
                            } else {
                                if (keyCode === 13) {
                                    if ($targetElement[['tagName']] === 'INPUT') {
                                        $targetElement = $okButton;
                                        $okButton[['focus']]();
                                    }
                                    if (btnIndex === -1) {
                                        $targetElement = $okButton;
                                    } else {
                                        $targetElement = undefined;
                                    }
                                } else if (keyCode === 27 && params[['allowEscapeKey']] === true) {
                                    $targetElement = $cancelButton;
                                    _stopEventPropagation$fireClick[['fireClick']]($targetElement, e);
                                } else {
                                    $targetElement = undefined;
                                }
                            }
                        };
                        exports['default'] = handleKeyDown;
                        module[['exports']] = exports['default'];
                    },
                    {
                        './handle-dom': 4,
                        './handle-swal-dom': 6
                    }
                ],
                6: [
                    function (require, module, exports) {
                        'use strict';
                        var _interopRequireWildcard = function _interopRequireWildcard(obj) {
                            return obj && obj[['__esModule']] ? obj : { 'default': obj };
                        };
                        Object[['defineProperty']](exports, '__esModule', { value: true });
                        var _hexToRgb = require('./utils');
                        var _removeClass$getTopMargin$fadeIn$show$addClass = require('./handle-dom');
                        var _defaultParams = require('./default-params');
                        var _defaultParams2 = _interopRequireWildcard(_defaultParams);
                        var _injectedHTML = require('./injected-html');
                        var _injectedHTML2 = _interopRequireWildcard(_injectedHTML);
                        var modalClass = '.sweet-alert';
                        var overlayClass = '.sweet-overlay';
                        var sweetAlertInitialize = function sweetAlertInitialize() {
                            var sweetWrap = document[['createElement']]('div');
                            sweetWrap[['innerHTML']] = _injectedHTML2['default'];
                            while (sweetWrap[['firstChild']]) {
                                document[['body']][['appendChild']](sweetWrap[['firstChild']]);
                            }
                        };
                        var getModal = function (_getModal) {
                            function getModal() {
                                return _getModal[['apply']](this, arguments);
                            }
                            getModal[['toString']] = function () {
                                return _getModal[['toString']]();
                            };
                            return getModal;
                        }(function () {
                            var $modal = document[['querySelector']](modalClass);
                            if (!$modal) {
                                sweetAlertInitialize();
                                $modal = getModal();
                            }
                            return $modal;
                        });
                        var getInput = function getInput() {
                            var $modal = getModal();
                            if ($modal) {
                                return $modal[['querySelector']]('input');
                            }
                        };
                        var getOverlay = function getOverlay() {
                            return document[['querySelector']](overlayClass);
                        };
                        var setFocusStyle = function setFocusStyle($button, bgColor) {
                            var rgbColor = _hexToRgb[['hexToRgb']](bgColor);
                        };
                        var openModal = function openModal(callback) {
                            var $modal = getModal();
                            _removeClass$getTopMargin$fadeIn$show$addClass[['fadeIn']](getOverlay(), 10);
                            _removeClass$getTopMargin$fadeIn$show$addClass[['show']]($modal);
                            _removeClass$getTopMargin$fadeIn$show$addClass[['addClass']]($modal, 'showSweetAlert');
                            _removeClass$getTopMargin$fadeIn$show$addClass[['removeClass']]($modal, 'hideSweetAlert');
                            window[['previousActiveElement']] = document[['activeElement']];
                            var $okButton = $modal[['querySelector']]('button.confirm');
                            $okButton[['focus']]();
                            setTimeout(function () {
                                _removeClass$getTopMargin$fadeIn$show$addClass[['addClass']]($modal, 'visible');
                            }, 500);
                            var timer = $modal[['getAttribute']]('data-timer');
                            if (timer !== 'null' && timer !== '') {
                                var timerCallback = callback;
                                $modal[['timeout']] = setTimeout(function () {
                                    var doneFunctionExists = (timerCallback || null) && $modal[['getAttribute']]('data-has-done-function') === 'true';
                                    if (doneFunctionExists) {
                                        timerCallback(null);
                                    } else {
                                        sweetAlert[['close']]();
                                    }
                                }, timer);
                            }
                        };
                        var resetInput = function resetInput() {
                            var $modal = getModal();
                            var $input = getInput();
                            _removeClass$getTopMargin$fadeIn$show$addClass[['removeClass']]($modal, 'show-input');
                            $input[['value']] = _defaultParams2['default'][['inputValue']];
                            $input[['setAttribute']]('type', _defaultParams2['default'][['inputType']]);
                            $input[['setAttribute']]('placeholder', _defaultParams2['default'][['inputPlaceholder']]);
                            resetInputError();
                        };
                        var resetInputError = function resetInputError(event) {
                            if (event && event[['keyCode']] === 13) {
                                return false;
                            }
                            var $modal = getModal();
                            var $errorIcon = $modal[['querySelector']]('.sa-input-error');
                            _removeClass$getTopMargin$fadeIn$show$addClass[['removeClass']]($errorIcon, 'show');
                            var $errorContainer = $modal[['querySelector']]('.sa-error-container');
                            _removeClass$getTopMargin$fadeIn$show$addClass[['removeClass']]($errorContainer, 'show');
                        };
                        var fixVerticalPosition = function fixVerticalPosition() {
                            var $modal = getModal();
                            $modal[['style']][['marginTop']] = _removeClass$getTopMargin$fadeIn$show$addClass[['getTopMargin']](getModal());
                        };
                        exports[['sweetAlertInitialize']] = sweetAlertInitialize;
                        exports[['getModal']] = getModal;
                        exports[['getOverlay']] = getOverlay;
                        exports[['getInput']] = getInput;
                        exports[['setFocusStyle']] = setFocusStyle;
                        exports[['openModal']] = openModal;
                        exports[['resetInput']] = resetInput;
                        exports[['resetInputError']] = resetInputError;
                        exports[['fixVerticalPosition']] = fixVerticalPosition;
                    },
                    {
                        './default-params': 2,
                        './handle-dom': 4,
                        './injected-html': 7,
                        './utils': 9
                    }
                ],
                7: [
                    function (require, module, exports) {
                        'use strict';
                        Object[['defineProperty']](exports, '__esModule', { value: true });
                        var injectedHTML = '<div class="sweet-overlay" tabIndex="-1"></div>' + '<div class="sweet-alert">' + '<div class="sa-icon sa-error">\n      <span class="sa-x-mark">\n        <span class="sa-line sa-left"></span>\n        <span class="sa-line sa-right"></span>\n      </span>\n    </div>' + '<div class="sa-icon sa-warning">\n      <span class="sa-body"></span>\n      <span class="sa-dot"></span>\n    </div>' + '<div class="sa-icon sa-info"></div>' + '<div class="sa-icon sa-success">\n      <span class="sa-line sa-tip"></span>\n      <span class="sa-line sa-long"></span>\n\n      <div class="sa-placeholder"></div>\n      <div class="sa-fix"></div>\n    </div>' + '<div class="sa-icon sa-custom"></div>' + '<h2>Title</h2>\n    <p>Text</p>\n    <fieldset>\n      <input type="text" tabIndex="3" />\n      <div class="sa-input-error"></div>\n    </fieldset>' + '<div class="sa-error-container">\n      <div class="icon">!</div>\n      <p>Not valid!</p>\n    </div>' + '<div class="sa-button-container">\n      <button class="cancel btn btn-default" tabIndex="2">Cancel</button>\n      <div class="sa-confirm-button-container">\n        <button class="confirm btn btn-wide" tabIndex="1">OK</button>' + '<div class="la-ball-fall">\n          <div></div>\n          <div></div>\n          <div></div>\n        </div>\n      </div>\n    </div>' + '</div>';
                        exports['default'] = injectedHTML;
                        module[['exports']] = exports['default'];
                    },
                    {}
                ],
                8: [
                    function (require, module, exports) {
                        'use strict';
                        Object[['defineProperty']](exports, '__esModule', { value: true });
                        var _isIE8 = require('./utils');
                        var _getModal$getInput$setFocusStyle = require('./handle-swal-dom');
                        var _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide = require('./handle-dom');
                        var alertTypes = [
                            'error',
                            'warning',
                            'info',
                            'success',
                            'input',
                            'prompt'
                        ];
                        var setParameters = function setParameters(params) {
                            var modal = _getModal$getInput$setFocusStyle[['getModal']]();
                            var $title = modal[['querySelector']]('h2');
                            var $text = modal[['querySelector']]('p');
                            var $cancelBtn = modal[['querySelector']]('button.cancel');
                            var $confirmBtn = modal[['querySelector']]('button.confirm');
                            $title[['innerHTML']] = params[['html']] ? params[['title']] : _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['escapeHtml']](params[['title']])[['split']]('\n')[['join']]('<br>');
                            $text[['innerHTML']] = params[['html']] ? params[['text']] : _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['escapeHtml']](params[['text']] || '')[['split']]('\n')[['join']]('<br>');
                            if (params[['text']])
                                _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['show']]($text);
                            if (params[['customClass']]) {
                                _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['addClass']](modal, params[['customClass']]);
                                modal[['setAttribute']]('data-custom-class', params[['customClass']]);
                            } else {
                                var customClass = modal[['getAttribute']]('data-custom-class');
                                _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['removeClass']](modal, customClass);
                                modal[['setAttribute']]('data-custom-class', '');
                            }
                            _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['hide']](modal[['querySelectorAll']]('.sa-icon'));
                            if (params[['type']] && !_isIE8[['isIE8']]()) {
                                var _ret = function () {
                                    var validType = false;
                                    for (var i = 0; i < alertTypes[['length']]; i++) {
                                        if (params[['type']] === alertTypes[i]) {
                                            validType = true;
                                            break;
                                        }
                                    }
                                    if (!validType) {
                                        logStr('Unknown alert type: ' + params[['type']]);
                                        return { v: false };
                                    }
                                    var typesWithIcons = [
                                        'success',
                                        'error',
                                        'warning',
                                        'info'
                                    ];
                                    var $icon = undefined;
                                    if (typesWithIcons[['indexOf']](params[['type']]) !== -1) {
                                        $icon = modal[['querySelector']]('.sa-icon.' + 'sa-' + params[['type']]);
                                        _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['show']]($icon);
                                    }
                                    var $input = _getModal$getInput$setFocusStyle[['getInput']]();
                                    switch (params[['type']]) {
                                    case 'success':
                                        _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['addClass']]($icon, 'animate');
                                        _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['addClass']]($icon[['querySelector']]('.sa-tip'), 'animateSuccessTip');
                                        _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['addClass']]($icon[['querySelector']]('.sa-long'), 'animateSuccessLong');
                                        break;
                                    case 'error':
                                        _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['addClass']]($icon, 'animateErrorIcon');
                                        _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['addClass']]($icon[['querySelector']]('.sa-x-mark'), 'animateXMark');
                                        break;
                                    case 'warning':
                                        _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['addClass']]($icon, 'pulseWarning');
                                        _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['addClass']]($icon[['querySelector']]('.sa-body'), 'pulseWarningIns');
                                        _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['addClass']]($icon[['querySelector']]('.sa-dot'), 'pulseWarningIns');
                                        break;
                                    case 'input':
                                    case 'prompt':
                                        $input[['setAttribute']]('type', params[['inputType']]);
                                        $input[['value']] = params[['inputValue']];
                                        $input[['setAttribute']]('placeholder', params[['inputPlaceholder']]);
                                        _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['addClass']](modal, 'show-input');
                                        setTimeout(function () {
                                            $input[['focus']]();
                                            $input[['addEventListener']]('keyup', swal[['resetInputError']]);
                                        }, 400);
                                        break;
                                    }
                                }();
                                if ((typeof _ret === 'undefined' ? 'undefined' : _typeof(_ret)) === 'object') {
                                    return _ret[['v']];
                                }
                            }
                            if (params[['imageUrl']]) {
                                var $customIcon = modal[['querySelector']]('.sa-icon.sa-custom');
                                $customIcon[['style']][['backgroundImage']] = 'url(' + params[['imageUrl']] + ')';
                                _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['show']]($customIcon);
                                var _imgWidth = 80;
                                var _imgHeight = 80;
                                if (params[['imageSize']]) {
                                    var dimensions = params[['imageSize']][['toString']]()[['split']]('x');
                                    var imgWidth = dimensions[0];
                                    var imgHeight = dimensions[1];
                                    if (!imgWidth || !imgHeight) {
                                        logStr('Parameter imageSize expects value with format WIDTHxHEIGHT, got ' + params[['imageSize']]);
                                    } else {
                                        _imgWidth = imgWidth;
                                        _imgHeight = imgHeight;
                                    }
                                }
                                $customIcon[['setAttribute']]('style', $customIcon[['getAttribute']]('style') + 'width:' + _imgWidth + 'px; height:' + _imgHeight + 'px');
                            }
                            modal[['setAttribute']]('data-has-cancel-button', params[['showCancelButton']]);
                            if (params[['showCancelButton']]) {
                                $cancelBtn[['style']][['display']] = 'inline-block';
                            } else {
                                _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['hide']]($cancelBtn);
                            }
                            modal[['setAttribute']]('data-has-confirm-button', params[['showConfirmButton']]);
                            if (params[['showConfirmButton']]) {
                                $confirmBtn[['style']][['display']] = 'inline-block';
                                _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['addClass']]($confirmBtn, params[['confirmButtonClass']]);
                            } else {
                                _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['hide']]($confirmBtn);
                            }
                            if (params[['cancelButtonText']]) {
                                $cancelBtn[['innerHTML']] = _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['escapeHtml']](params[['cancelButtonText']]);
                            }
                            if (params[['confirmButtonText']]) {
                                $confirmBtn[['innerHTML']] = _hasClass$addClass$removeClass$escapeHtml$_show$show$_hide$hide[['escapeHtml']](params[['confirmButtonText']]);
                            }
                            if (params[['confirmButtonColor']]) {
                                $confirmBtn[['style']][['backgroundColor']] = params[['confirmButtonColor']];
                                $confirmBtn[['style']][['borderLeftColor']] = params[['confirmLoadingButtonColor']];
                                $confirmBtn[['style']][['borderRightColor']] = params[['confirmLoadingButtonColor']];
                                _getModal$getInput$setFocusStyle[['setFocusStyle']]($confirmBtn, params[['confirmButtonColor']]);
                            }
                            modal[['setAttribute']]('data-allow-outside-click', params[['allowOutsideClick']]);
                            var hasDoneFunction = params[['doneFunction']] ? true : false;
                            modal[['setAttribute']]('data-has-done-function', hasDoneFunction);
                            if (!params[['animation']]) {
                                modal[['setAttribute']]('data-animation', 'none');
                            } else if (typeof params[['animation']] === 'string') {
                                modal[['setAttribute']]('data-animation', params[['animation']]);
                            } else {
                                modal[['setAttribute']]('data-animation', 'pop');
                            }
                            modal[['setAttribute']]('data-timer', params[['timer']]);
                        };
                        exports['default'] = setParameters;
                        module[['exports']] = exports['default'];
                    },
                    {
                        './handle-dom': 4,
                        './handle-swal-dom': 6,
                        './utils': 9
                    }
                ],
                9: [
                    function (require, module, exports) {
                        'use strict';
                        Object[['defineProperty']](exports, '__esModule', { value: true });
                        var extend = function extend(a, b) {
                            for (var key in b) {
                                if (b[['hasOwnProperty']](key)) {
                                    a[key] = b[key];
                                }
                            }
                            return a;
                        };
                        var hexToRgb = function hexToRgb(hex) {
                            var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i[['exec']](hex);
                            return result ? parseInt(result[1], 16) + ', ' + parseInt(result[2], 16) + ', ' + parseInt(result[3], 16) : null;
                        };
                        var isIE8 = function isIE8() {
                            return window[['attachEvent']] && !window[['addEventListener']];
                        };
                        var logStr = function logStr(string) {
                            if (window[['console']]) {
                                window[['console']][['log']]('SweetAlert: ' + string);
                            }
                        };
                        var colorLuminance = function colorLuminance(hex, lum) {
                            hex = String(hex)[['replace']](/[^0-9a-f]/gi, '');
                            if (hex[['length']] < 6) {
                                hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
                            }
                            lum = lum || 0;
                            var rgb = '#';
                            var c;
                            var i;
                            for (i = 0; i < 3; i++) {
                                c = parseInt(hex[['substr']](i * 2, 2), 16);
                                c = Math[['round']](Math[['min']](Math[['max']](0, c + c * lum), 255))[['toString']](16);
                                rgb += ('00' + c)[['substr']](c[['length']]);
                            }
                            return rgb;
                        };
                        exports[['extend']] = extend;
                        exports[['hexToRgb']] = hexToRgb;
                        exports[['isIE8']] = isIE8;
                        exports[['logStr']] = logStr;
                        exports[['colorLuminance']] = colorLuminance;
                    },
                    {}
                ]
            }, {}, [1]));
            if (true) {
                !(__WEBPACK_AMD_DEFINE_RESULT__ = function () {
                    return sweetAlert;
                }[['call']](exports, __webpack_require__, exports, module), __WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module[['exports']] = __WEBPACK_AMD_DEFINE_RESULT__));
            } else if (typeof module !== 'undefined' && module[['exports']]) {
                module[['exports']] = sweetAlert;
            }
        }(window, document));
    },
    function (module, exports, __webpack_require__) {
        (function ($) {
            'use strict';
            Object[['defineProperty']](exports, '__esModule', { value: true });
            var handleLineLoading = function handleLineLoading() {
                var body = $('body');
                if (body[['hasClass']]('is-loadingApp')) {
                    setTimeout(function () {
                        body[['removeClass']]('is-loadingApp');
                    }, 2000);
                }
            };
            var handleSpinLoading = function handleSpinLoading() {
                console[['log']]('10000');
            };
            exports[['handleLineLoading']] = handleLineLoading;
            exports[['handleSpinLoading']] = handleSpinLoading;
        }[['call']](exports, __webpack_require__(1)));
    },
    function (module, exports, __webpack_require__) {
        'use strict';
        var _typeof = typeof Symbol === 'function' && typeof Symbol[['iterator']] === 'symbol' ? function (obj) {
            return typeof obj;
        } : function (obj) {
            return obj && typeof Symbol === 'function' && obj[['constructor']] === Symbol ? 'symbol' : typeof obj;
        };
        var jQuery = __webpack_require__(1);
        (function (global, $) {
            'use strict';
            var Radiocheck = function Radiocheck(element, options) {
                this[['init']]('radiocheck', element, options);
            };
            Radiocheck[['DEFAULTS']] = {
                checkboxClass: 'custom-checkbox',
                radioClass: 'custom-radio',
                checkboxTemplate: '<span class="icons"><span class="icon-unchecked"></span><span class="icon-checked"></span></span>',
                radioTemplate: '<span class="icons"><span class="icon-unchecked"></span><span class="icon-checked"></span></span>'
            };
            Radiocheck[['prototype']][['init']] = function (type, element, options) {
                this[['$element']] = $(element);
                this[['options']] = $[['extend']]({}, Radiocheck[['DEFAULTS']], this[['$element']][['data']](), options);
                if (this[['$element']][['attr']]('type') == 'checkbox') {
                    this[['$element']][['addClass']](this[['options']][['checkboxClass']]);
                    this[['$element']][['after']](this[['options']][['checkboxTemplate']]);
                } else if (this[['$element']][['attr']]('type') == 'radio') {
                    this[['$element']][['addClass']](this[['options']][['radioClass']]);
                    this[['$element']][['after']](this[['options']][['radioTemplate']]);
                }
            };
            Radiocheck[['prototype']][['check']] = function () {
                this[['$element']][['prop']]('checked', true);
                this[['$element']][['trigger']]('change.radiocheck')[['trigger']]('checked.radiocheck');
            }, Radiocheck[['prototype']][['uncheck']] = function () {
                this[['$element']][['prop']]('checked', false);
                this[['$element']][['trigger']]('change.radiocheck')[['trigger']]('unchecked.radiocheck');
            }, Radiocheck[['prototype']][['toggle']] = function () {
                this[['$element']][['prop']]('checked', function (i, value) {
                    return !value;
                });
                this[['$element']][['trigger']]('change.radiocheck')[['trigger']]('toggled.radiocheck');
            }, Radiocheck[['prototype']][['indeterminate']] = function () {
                this[['$element']][['prop']]('indeterminate', true);
                this[['$element']][['trigger']]('change.radiocheck')[['trigger']]('indeterminated.radiocheck');
            }, Radiocheck[['prototype']][['determinate']] = function () {
                this[['$element']][['prop']]('indeterminate', false);
                this[['$element']][['trigger']]('change.radiocheck')[['trigger']]('determinated.radiocheck');
            }, Radiocheck[['prototype']][['disable']] = function () {
                this[['$element']][['prop']]('disabled', true);
                this[['$element']][['trigger']]('change.radiocheck')[['trigger']]('disabled.radiocheck');
            }, Radiocheck[['prototype']][['enable']] = function () {
                this[['$element']][['prop']]('disabled', false);
                this[['$element']][['trigger']]('change.radiocheck')[['trigger']]('enabled.radiocheck');
            }, Radiocheck[['prototype']][['destroy']] = function () {
                this[['$element']][['removeData']]()[['removeClass']](this[['options']][['checkboxClass']] + ' ' + this[['options']][['radioClass']])[['next']]('.icons')[['remove']]();
                this[['$element']][['trigger']]('destroyed.radiocheck');
            };
            function Plugin(option) {
                return this[['each']](function () {
                    var $this = $(this);
                    var data = $this[['data']]('radiocheck');
                    var options = (typeof option === 'undefined' ? 'undefined' : _typeof(option)) == 'object' && option;
                    if (!data && option == 'destroy') {
                        return;
                    }
                    if (!data) {
                        $this[['data']]('radiocheck', data = new Radiocheck(this, options));
                    }
                    if (typeof option == 'string') {
                        data[option]();
                    }
                    var mobile = /mobile|tablet|phone|ip(ad|od)|android|silk|webos/i[['test']](global[['navigator']][['userAgent']]);
                    if (mobile === true) {
                        $this[['parent']]()[['hover']](function () {
                            $this[['addClass']]('nohover');
                        }, function () {
                            $this[['removeClass']]('nohover');
                        });
                    }
                });
            }
            var old = $[['fn']][['radiocheck']];
            $[['fn']][['radiocheck']] = Plugin;
            $[['fn']][['radiocheck']][['Constructor']] = Radiocheck;
            $[['fn']][['radiocheck']][['noConflict']] = function () {
                $[['fn']][['radiocheck']] = old;
                return this;
            };
        }(undefined, jQuery));
        (function ($) {
            'use strict';
            var Tooltip = function Tooltip(element, options) {
                this[['type']] = this[['options']] = this[['enabled']] = this[['timeout']] = this[['hoverState']] = this[['$element']] = null;
                this[['init']]('tooltip', element, options);
            };
            Tooltip[['VERSION']] = '3.2.0';
            Tooltip[['DEFAULTS']] = {
                animation: true,
                placement: 'top',
                selector: false,
                template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
                trigger: 'hover focus',
                title: '',
                delay: 0,
                html: false,
                container: false,
                viewport: {
                    selector: 'body',
                    padding: 0
                }
            };
            Tooltip[['prototype']][['init']] = function (type, element, options) {
                this[['enabled']] = true;
                this[['type']] = type;
                this[['$element']] = $(element);
                this[['options']] = this[['getOptions']](options);
                this[['$viewport']] = this[['options']][['viewport']] && $(this[['options']][['viewport']][['selector']] || this[['options']][['viewport']]);
                var triggers = this[['options']][['trigger']][['split']](' ');
                for (var i = triggers[['length']]; i--;) {
                    var trigger = triggers[i];
                    if (trigger == 'click') {
                        this[['$element']][['on']]('click.' + this[['type']], this[['options']][['selector']], $[['proxy']](this[['toggle']], this));
                    } else if (trigger != 'manual') {
                        var eventIn = trigger == 'hover' ? 'mouseenter' : 'focusin';
                        var eventOut = trigger == 'hover' ? 'mouseleave' : 'focusout';
                        this[['$element']][['on']](eventIn + '.' + this[['type']], this[['options']][['selector']], $[['proxy']](this[['enter']], this));
                        this[['$element']][['on']](eventOut + '.' + this[['type']], this[['options']][['selector']], $[['proxy']](this[['leave']], this));
                    }
                }
                this[['options']][['selector']] ? this[['_options']] = $[['extend']]({}, this[['options']], {
                    trigger: 'manual',
                    selector: ''
                }) : this[['fixTitle']]();
            };
            Tooltip[['prototype']][['getDefaults']] = function () {
                return Tooltip[['DEFAULTS']];
            };
            Tooltip[['prototype']][['getOptions']] = function (options) {
                options = $[['extend']]({}, this[['getDefaults']](), this[['$element']][['data']](), options);
                if (options[['delay']] && typeof options[['delay']] == 'number') {
                    options[['delay']] = {
                        show: options[['delay']],
                        hide: options[['delay']]
                    };
                }
                return options;
            };
            Tooltip[['prototype']][['getDelegateOptions']] = function () {
                var options = {};
                var defaults = this[['getDefaults']]();
                this[['_options']] && $[['each']](this[['_options']], function (key, value) {
                    if (defaults[key] != value)
                        options[key] = value;
                });
                return options;
            };
            Tooltip[['prototype']][['enter']] = function (obj) {
                var self = obj instanceof this[['constructor']] ? obj : $(obj[['currentTarget']])[['data']]('bs.' + this[['type']]);
                if (!self) {
                    self = new this[['constructor']](obj[['currentTarget']], this[['getDelegateOptions']]());
                    $(obj[['currentTarget']])[['data']]('bs.' + this[['type']], self);
                }
                clearTimeout(self[['timeout']]);
                self[['hoverState']] = 'in';
                if (!self[['options']][['delay']] || !self[['options']][['delay']][['show']])
                    return self[['show']]();
                self[['timeout']] = setTimeout(function () {
                    if (self[['hoverState']] == 'in')
                        self[['show']]();
                }, self[['options']][['delay']][['show']]);
            };
            Tooltip[['prototype']][['leave']] = function (obj) {
                var self = obj instanceof this[['constructor']] ? obj : $(obj[['currentTarget']])[['data']]('bs.' + this[['type']]);
                if (!self) {
                    self = new this[['constructor']](obj[['currentTarget']], this[['getDelegateOptions']]());
                    $(obj[['currentTarget']])[['data']]('bs.' + this[['type']], self);
                }
                clearTimeout(self[['timeout']]);
                self[['hoverState']] = 'out';
                if (!self[['options']][['delay']] || !self[['options']][['delay']][['hide']])
                    return self[['hide']]();
                self[['timeout']] = setTimeout(function () {
                    if (self[['hoverState']] == 'out')
                        self[['hide']]();
                }, self[['options']][['delay']][['hide']]);
            };
            Tooltip[['prototype']][['show']] = function () {
                var e = $[['Event']]('show.bs.' + this[['type']]);
                if (this[['hasContent']]() && this[['enabled']]) {
                    this[['$element']][['trigger']](e);
                    var inDom = $[['contains']](document[['documentElement']], this[['$element']][0]);
                    if (e[['isDefaultPrevented']]() || !inDom)
                        return;
                    var that = this;
                    var $tip = this[['tip']]();
                    var tipId = this[['getUID']](this[['type']]);
                    this[['setContent']]();
                    $tip[['attr']]('id', tipId);
                    this[['$element']][['attr']]('aria-describedby', tipId);
                    if (this[['options']][['animation']])
                        $tip[['addClass']]('fade');
                    var placement = typeof this[['options']][['placement']] == 'function' ? this[['options']][['placement']][['call']](this, $tip[0], this[['$element']][0]) : this[['options']][['placement']];
                    var autoToken = /\s?auto?\s?/i;
                    var autoPlace = autoToken[['test']](placement);
                    if (autoPlace)
                        placement = placement[['replace']](autoToken, '') || 'top';
                    $tip[['detach']]()[['css']]({
                        top: 0,
                        left: 0,
                        display: 'block'
                    })[['addClass']](placement)[['data']]('bs.' + this[['type']], this);
                    this[['options']][['container']] ? $tip[['appendTo']](this[['options']][['container']]) : $tip[['insertAfter']](this[['$element']]);
                    var pos = this[['getPosition']]();
                    var actualWidth = $tip[0][['offsetWidth']];
                    var actualHeight = $tip[0][['offsetHeight']];
                    if (autoPlace) {
                        var orgPlacement = placement;
                        var $parent = this[['$element']][['parent']]();
                        var parentDim = this[['getPosition']]($parent);
                        placement = placement == 'bottom' && pos[['top']] + pos[['height']] + actualHeight - parentDim[['scroll']] > parentDim[['height']] ? 'top' : placement == 'top' && pos[['top']] - parentDim[['scroll']] - actualHeight < 0 ? 'bottom' : placement == 'right' && pos[['right']] + actualWidth > parentDim[['width']] ? 'left' : placement == 'left' && pos[['left']] - actualWidth < parentDim[['left']] ? 'right' : placement;
                        $tip[['removeClass']](orgPlacement)[['addClass']](placement);
                    }
                    var calculatedOffset = this[['getCalculatedOffset']](placement, pos, actualWidth, actualHeight);
                    this[['applyPlacement']](calculatedOffset, placement);
                    var complete = function complete() {
                        that[['$element']][['trigger']]('shown.bs.' + that[['type']]);
                        that[['hoverState']] = null;
                    };
                    $[['support']][['transition']] && this[['$tip']][['hasClass']]('fade') ? $tip[['one']]('bsTransitionEnd', complete)[['emulateTransitionEnd']](150) : complete();
                }
            };
            Tooltip[['prototype']][['applyPlacement']] = function (offset, placement) {
                var $tip = this[['tip']]();
                var width = $tip[0][['offsetWidth']];
                var height = $tip[0][['offsetHeight']];
                var marginTop = parseInt($tip[['css']]('margin-top'), 10);
                var marginLeft = parseInt($tip[['css']]('margin-left'), 10);
                if (isNaN(marginTop))
                    marginTop = 0;
                if (isNaN(marginLeft))
                    marginLeft = 0;
                offset[['top']] = offset[['top']] + marginTop;
                offset[['left']] = offset[['left']] + marginLeft;
                $[['offset']][['setOffset']]($tip[0], $[['extend']]({
                    using: function using(props) {
                        $tip[['css']]({
                            top: Math[['round']](props[['top']]),
                            left: Math[['round']](props[['left']])
                        });
                    }
                }, offset), 0);
                $tip[['addClass']]('in');
                var actualWidth = $tip[0][['offsetWidth']];
                var actualHeight = $tip[0][['offsetHeight']];
                if (placement == 'top' && actualHeight != height) {
                    offset[['top']] = offset[['top']] + height - actualHeight;
                }
                var delta = this[['getViewportAdjustedDelta']](placement, offset, actualWidth, actualHeight);
                if (delta[['left']])
                    offset[['left']] += delta[['left']];
                else
                    offset[['top']] += delta[['top']];
                var arrowDelta = delta[['left']] ? delta[['left']] * 2 - width + actualWidth : delta[['top']] * 2 - height + actualHeight;
                var arrowPosition = delta[['left']] ? 'left' : 'top';
                var arrowOffsetPosition = delta[['left']] ? 'offsetWidth' : 'offsetHeight';
                $tip[['offset']](offset);
                this[['replaceArrow']](arrowDelta, $tip[0][arrowOffsetPosition], arrowPosition);
            };
            Tooltip[['prototype']][['replaceArrow']] = function (delta, dimension, position) {
                this[['arrow']]()[['css']](position, delta ? 50 * (1 - delta / dimension) + '%' : '');
            };
            Tooltip[['prototype']][['setContent']] = function () {
                var $tip = this[['tip']]();
                var title = this[['getTitle']]();
                $tip[['find']]('.tooltip-inner')[this[['options']][['html']] ? 'html' : 'text'](title);
                $tip[['removeClass']]('fade in top bottom left right');
            };
            Tooltip[['prototype']][['hide']] = function () {
                var that = this;
                var $tip = this[['tip']]();
                var e = $[['Event']]('hide.bs.' + this[['type']]);
                this[['$element']][['removeAttr']]('aria-describedby');
                function complete() {
                    if (that[['hoverState']] != 'in')
                        $tip[['detach']]();
                    that[['$element']][['trigger']]('hidden.bs.' + that[['type']]);
                }
                this[['$element']][['trigger']](e);
                if (e[['isDefaultPrevented']]())
                    return;
                $tip[['removeClass']]('in');
                $[['support']][['transition']] && this[['$tip']][['hasClass']]('fade') ? $tip[['one']]('bsTransitionEnd', complete)[['emulateTransitionEnd']](150) : complete();
                this[['hoverState']] = null;
                return this;
            };
            Tooltip[['prototype']][['fixTitle']] = function () {
                var $e = this[['$element']];
                if ($e[['attr']]('title') || typeof $e[['attr']]('data-original-title') != 'string') {
                    $e[['attr']]('data-original-title', $e[['attr']]('title') || '')[['attr']]('title', '');
                }
            };
            Tooltip[['prototype']][['hasContent']] = function () {
                return this[['getTitle']]();
            };
            Tooltip[['prototype']][['getPosition']] = function ($element) {
                $element = $element || this[['$element']];
                var el = $element[0];
                var isBody = el[['tagName']] == 'BODY';
                return $[['extend']]({}, typeof el[['getBoundingClientRect']] == 'function' ? el[['getBoundingClientRect']]() : null, {
                    scroll: isBody ? document[['documentElement']][['scrollTop']] || document[['body']][['scrollTop']] : $element[['scrollTop']](),
                    width: isBody ? $(window)[['width']]() : $element[['outerWidth']](),
                    height: isBody ? $(window)[['height']]() : $element[['outerHeight']]()
                }, isBody ? {
                    top: 0,
                    left: 0
                } : $element[['offset']]());
            };
            Tooltip[['prototype']][['getCalculatedOffset']] = function (placement, pos, actualWidth, actualHeight) {
                return placement == 'bottom' ? {
                    top: pos[['top']] + pos[['height']],
                    left: pos[['left']] + pos[['width']] / 2 - actualWidth / 2
                } : placement == 'top' ? {
                    top: pos[['top']] - actualHeight,
                    left: pos[['left']] + pos[['width']] / 2 - actualWidth / 2
                } : placement == 'left' ? {
                    top: pos[['top']] + pos[['height']] / 2 - actualHeight / 2,
                    left: pos[['left']] - actualWidth
                } : {
                    top: pos[['top']] + pos[['height']] / 2 - actualHeight / 2,
                    left: pos[['left']] + pos[['width']]
                };
            };
            Tooltip[['prototype']][['getViewportAdjustedDelta']] = function (placement, pos, actualWidth, actualHeight) {
                var delta = {
                    top: 0,
                    left: 0
                };
                if (!this[['$viewport']])
                    return delta;
                var viewportPadding = this[['options']][['viewport']] && this[['options']][['viewport']][['padding']] || 0;
                var viewportDimensions = this[['getPosition']](this[['$viewport']]);
                if (/right|left/[['test']](placement)) {
                    var topEdgeOffset = pos[['top']] - viewportPadding - viewportDimensions[['scroll']];
                    var bottomEdgeOffset = pos[['top']] + viewportPadding - viewportDimensions[['scroll']] + actualHeight;
                    if (topEdgeOffset < viewportDimensions[['top']]) {
                        delta[['top']] = viewportDimensions[['top']] - topEdgeOffset;
                    } else if (bottomEdgeOffset > viewportDimensions[['top']] + viewportDimensions[['height']]) {
                        delta[['top']] = viewportDimensions[['top']] + viewportDimensions[['height']] - bottomEdgeOffset;
                    }
                } else {
                    var leftEdgeOffset = pos[['left']] - viewportPadding;
                    var rightEdgeOffset = pos[['left']] + viewportPadding + actualWidth;
                    if (leftEdgeOffset < viewportDimensions[['left']]) {
                        delta[['left']] = viewportDimensions[['left']] - leftEdgeOffset;
                    } else if (rightEdgeOffset > viewportDimensions[['width']]) {
                        delta[['left']] = viewportDimensions[['left']] + viewportDimensions[['width']] - rightEdgeOffset;
                    }
                }
                return delta;
            };
            Tooltip[['prototype']][['getTitle']] = function () {
                var title;
                var $e = this[['$element']];
                var o = this[['options']];
                title = $e[['attr']]('data-original-title') || (typeof o[['title']] == 'function' ? o[['title']][['call']]($e[0]) : o[['title']]);
                return title;
            };
            Tooltip[['prototype']][['getUID']] = function (prefix) {
                do {
                    prefix += ~~(Math[['random']]() * 1000000);
                } while (document[['getElementById']](prefix));
                return prefix;
            };
            Tooltip[['prototype']][['tip']] = function () {
                return this[['$tip']] = this[['$tip']] || $(this[['options']][['template']]);
            };
            Tooltip[['prototype']][['arrow']] = function () {
                return this[['$arrow']] = this[['$arrow']] || this[['tip']]()[['find']]('.tooltip-arrow');
            };
            Tooltip[['prototype']][['validate']] = function () {
                if (!this[['$element']][0][['parentNode']]) {
                    this[['hide']]();
                    this[['$element']] = null;
                    this[['options']] = null;
                }
            };
            Tooltip[['prototype']][['enable']] = function () {
                this[['enabled']] = true;
            };
            Tooltip[['prototype']][['disable']] = function () {
                this[['enabled']] = false;
            };
            Tooltip[['prototype']][['toggleEnabled']] = function () {
                this[['enabled']] = !this[['enabled']];
            };
            Tooltip[['prototype']][['toggle']] = function (e) {
                var self = this;
                if (e) {
                    self = $(e[['currentTarget']])[['data']]('bs.' + this[['type']]);
                    if (!self) {
                        self = new this[['constructor']](e[['currentTarget']], this[['getDelegateOptions']]());
                        $(e[['currentTarget']])[['data']]('bs.' + this[['type']], self);
                    }
                }
                self[['tip']]()[['hasClass']]('in') ? self[['leave']](self) : self[['enter']](self);
            };
            Tooltip[['prototype']][['destroy']] = function () {
                clearTimeout(this[['timeout']]);
                this[['hide']]()[['$element']][['off']]('.' + this[['type']])[['removeData']]('bs.' + this[['type']]);
            };
            function Plugin(option) {
                return this[['each']](function () {
                    var $this = $(this);
                    var data = $this[['data']]('bs.tooltip');
                    var options = (typeof option === 'undefined' ? 'undefined' : _typeof(option)) == 'object' && option;
                    if (!data && option == 'destroy')
                        return;
                    if (!data)
                        $this[['data']]('bs.tooltip', data = new Tooltip(this, options));
                    if (typeof option == 'string')
                        data[option]();
                });
            }
            var old = $[['fn']][['tooltip']];
            $[['fn']][['tooltip']] = Plugin;
            $[['fn']][['tooltip']][['Constructor']] = Tooltip;
            $[['fn']][['tooltip']][['noConflict']] = function () {
                $[['fn']][['tooltip']] = old;
                return this;
            };
        }(jQuery));
        (function ($) {
            'use strict';
            var Button = function Button(element, options) {
                this[['$element']] = $(element);
                this[['options']] = $[['extend']]({}, Button[['DEFAULTS']], options);
                this[['isLoading']] = false;
            };
            Button[['VERSION']] = '3.2.0';
            Button[['DEFAULTS']] = { loadingText: 'loading...' };
            Button[['prototype']][['setState']] = function (state) {
                var d = 'disabled';
                var $el = this[['$element']];
                var val = $el[['is']]('input') ? 'val' : 'html';
                var data = $el[['data']]();
                state = state + 'Text';
                if (data[['resetText']] == null)
                    $el[['data']]('resetText', $el[val]());
                $el[val](data[state] == null ? this[['options']][state] : data[state]);
                setTimeout($[['proxy']](function () {
                    if (state == 'loadingText') {
                        this[['isLoading']] = true;
                        $el[['addClass']](d)[['attr']](d, d);
                    } else if (this[['isLoading']]) {
                        this[['isLoading']] = false;
                        $el[['removeClass']](d)[['removeAttr']](d);
                    }
                }, this), 0);
            };
            Button[['prototype']][['toggle']] = function () {
                var changed = true;
                var $parent = this[['$element']][['closest']]('[data-toggle="buttons"]');
                if ($parent[['length']]) {
                    var $input = this[['$element']][['find']]('input');
                    if ($input[['prop']]('type') == 'radio') {
                        if ($input[['prop']]('checked') && this[['$element']][['hasClass']]('active'))
                            changed = false;
                        else
                            $parent[['find']]('.active')[['removeClass']]('active');
                    }
                    if (changed)
                        $input[['prop']]('checked', !this[['$element']][['hasClass']]('active'))[['trigger']]('change');
                }
                if (changed)
                    this[['$element']][['toggleClass']]('active');
            };
            function Plugin(option) {
                return this[['each']](function () {
                    var $this = $(this);
                    var data = $this[['data']]('bs.button');
                    var options = (typeof option === 'undefined' ? 'undefined' : _typeof(option)) == 'object' && option;
                    if (!data)
                        $this[['data']]('bs.button', data = new Button(this, options));
                    if (option == 'toggle')
                        data[['toggle']]();
                    else if (option)
                        data[['setState']](option);
                });
            }
            var old = $[['fn']][['button']];
            $[['fn']][['button']] = Plugin;
            $[['fn']][['button']][['Constructor']] = Button;
            $[['fn']][['button']][['noConflict']] = function () {
                $[['fn']][['button']] = old;
                return this;
            };
            $(document)[['on']]('click.bs.button.data-api', '[data-toggle^="button"]', function (e) {
                var $btn = $(e[['target']]);
                if (!$btn[['hasClass']]('btn'))
                    $btn = $btn[['closest']]('.btn');
                Plugin[['call']]($btn, 'toggle');
                e[['preventDefault']]();
            });
        }(jQuery));
        (function ($) {
            'use strict';
            var backdrop = '.dropdown-backdrop';
            var toggle = '[data-toggle="dropdown"]';
            var Dropdown = function Dropdown(element) {
                $(element)[['on']]('click.bs.dropdown', this[['toggle']]);
            };
            Dropdown[['VERSION']] = '3.2.0';
            Dropdown[['prototype']][['toggle']] = function (e) {
                var $this = $(this);
                if ($this[['is']]('.disabled, :disabled'))
                    return;
                var $parent = getParent($this);
                var isActive = $parent[['hasClass']]('open');
                clearMenus();
                if (!isActive) {
                    if ('ontouchstart' in document[['documentElement']] && !$parent[['closest']]('.navbar-nav')[['length']]) {
                        $('<div class="dropdown-backdrop"/>')[['insertAfter']]($(this))[['on']]('click', clearMenus);
                    }
                    var relatedTarget = { relatedTarget: this };
                    $parent[['trigger']](e = $[['Event']]('show.bs.dropdown', relatedTarget));
                    if (e[['isDefaultPrevented']]())
                        return;
                    $this[['trigger']]('focus');
                    $parent[['toggleClass']]('open')[['trigger']]('shown.bs.dropdown', relatedTarget);
                }
                return false;
            };
            Dropdown[['prototype']][['keydown']] = function (e) {
                if (!/(38|40|27)/[['test']](e[['keyCode']]))
                    return;
                var $this = $(this);
                e[['preventDefault']]();
                e[['stopPropagation']]();
                if ($this[['is']]('.disabled, :disabled'))
                    return;
                var $parent = getParent($this);
                var isActive = $parent[['hasClass']]('open');
                if (!isActive || isActive && e[['keyCode']] == 27) {
                    if (e[['which']] == 27)
                        $parent[['find']](toggle)[['trigger']]('focus');
                    return $this[['trigger']]('click');
                }
                var desc = ' li:not(.divider):visible a';
                var $items = $parent[['find']]('[role="menu"]' + desc + ', [role="listbox"]' + desc);
                if (!$items[['length']])
                    return;
                var index = $items[['index']]($items[['filter']](':focus'));
                if (e[['keyCode']] == 38 && index > 0)
                    index--;
                if (e[['keyCode']] == 40 && index < $items[['length']] - 1)
                    index++;
                if (!~index)
                    index = 0;
                $items[['eq']](index)[['trigger']]('focus');
            };
            function clearMenus(e) {
                if (e && e[['which']] === 3)
                    return;
                $(backdrop)[['remove']]();
                $(toggle)[['each']](function () {
                    var $parent = getParent($(this));
                    var relatedTarget = { relatedTarget: this };
                    if (!$parent[['hasClass']]('open'))
                        return;
                    $parent[['trigger']](e = $[['Event']]('hide.bs.dropdown', relatedTarget));
                    if (e[['isDefaultPrevented']]())
                        return;
                    $parent[['removeClass']]('open')[['trigger']]('hidden.bs.dropdown', relatedTarget);
                });
            }
            function getParent($this) {
                var selector = $this[['attr']]('data-target');
                if (!selector) {
                    selector = $this[['attr']]('href');
                    selector = selector && /#[A-Za-z]/[['test']](selector) && selector[['replace']](/.*(?=#[^\s]*$)/, '');
                }
                var $parent = selector && $(selector);
                return $parent && $parent[['length']] ? $parent : $this[['parent']]();
            }
            function Plugin(option) {
                return this[['each']](function () {
                    var $this = $(this);
                    var data = $this[['data']]('bs.dropdown');
                    if (!data)
                        $this[['data']]('bs.dropdown', data = new Dropdown(this));
                    if (typeof option == 'string')
                        data[option][['call']]($this);
                });
            }
            var old = $[['fn']][['dropdown']];
            $[['fn']][['dropdown']] = Plugin;
            $[['fn']][['dropdown']][['Constructor']] = Dropdown;
            $[['fn']][['dropdown']][['noConflict']] = function () {
                $[['fn']][['dropdown']] = old;
                return this;
            };
            $(document)[['on']]('click.bs.dropdown.data-api', clearMenus)[['on']]('click.bs.dropdown.data-api', '.dropdown form', function (e) {
                e[['stopPropagation']]();
            })[['on']]('click.bs.dropdown.data-api', toggle, Dropdown[['prototype']][['toggle']])[['on']]('keydown.bs.dropdown.data-api', toggle + ', [role="menu"], [role="listbox"]', Dropdown[['prototype']][['keydown']]);
        }(jQuery));
        (function ($) {
            'use strict';
            var Popover = function Popover(element, options) {
                this[['init']]('popover', element, options);
            };
            if (!$[['fn']][['tooltip']])
                throw new Error('Popover requires tooltip.js');
            Popover[['VERSION']] = '3.2.0';
            Popover[['DEFAULTS']] = $[['extend']]({}, $[['fn']][['tooltip']][['Constructor']][['DEFAULTS']], {
                placement: 'right',
                trigger: 'click',
                content: '',
                template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
            });
            Popover[['prototype']] = $[['extend']]({}, $[['fn']][['tooltip']][['Constructor']][['prototype']]);
            Popover[['prototype']][['constructor']] = Popover;
            Popover[['prototype']][['getDefaults']] = function () {
                return Popover[['DEFAULTS']];
            };
            Popover[['prototype']][['setContent']] = function () {
                var $tip = this[['tip']]();
                var title = this[['getTitle']]();
                var content = this[['getContent']]();
                $tip[['find']]('.popover-title')[this[['options']][['html']] ? 'html' : 'text'](title);
                $tip[['find']]('.popover-content')[['empty']]()[this[['options']][['html']] ? typeof content == 'string' ? 'html' : 'append' : 'text'](content);
                $tip[['removeClass']]('fade top bottom left right in');
                if (!$tip[['find']]('.popover-title')[['html']]())
                    $tip[['find']]('.popover-title')[['hide']]();
            };
            Popover[['prototype']][['hasContent']] = function () {
                return this[['getTitle']]() || this[['getContent']]();
            };
            Popover[['prototype']][['getContent']] = function () {
                var $e = this[['$element']];
                var o = this[['options']];
                return $e[['attr']]('data-content') || (typeof o[['content']] == 'function' ? o[['content']][['call']]($e[0]) : o[['content']]);
            };
            Popover[['prototype']][['arrow']] = function () {
                return this[['$arrow']] = this[['$arrow']] || this[['tip']]()[['find']]('.arrow');
            };
            Popover[['prototype']][['tip']] = function () {
                if (!this[['$tip']])
                    this[['$tip']] = $(this[['options']][['template']]);
                return this[['$tip']];
            };
            function Plugin(option) {
                return this[['each']](function () {
                    var $this = $(this);
                    var data = $this[['data']]('bs.popover');
                    var options = (typeof option === 'undefined' ? 'undefined' : _typeof(option)) == 'object' && option;
                    if (!data && option == 'destroy')
                        return;
                    if (!data)
                        $this[['data']]('bs.popover', data = new Popover(this, options));
                    if (typeof option == 'string')
                        data[option]();
                });
            }
            var old = $[['fn']][['popover']];
            $[['fn']][['popover']] = Plugin;
            $[['fn']][['popover']][['Constructor']] = Popover;
            $[['fn']][['popover']][['noConflict']] = function () {
                $[['fn']][['popover']] = old;
                return this;
            };
        }(jQuery));
        +function ($) {
            'use strict';
            var Modal = function Modal(element, options) {
                this[['options']] = options;
                this[['$body']] = $(document[['body']]);
                this[['$element']] = $(element);
                this[['$dialog']] = this[['$element']][['find']]('.modal-dialog');
                this[['$backdrop']] = null;
                this[['isShown']] = null;
                this[['originalBodyPad']] = null;
                this[['scrollbarWidth']] = 0;
                this[['ignoreBackdropClick']] = false;
                if (this[['options']][['remote']]) {
                    this[['$element']][['find']]('.modal-content')[['load']](this[['options']][['remote']], $[['proxy']](function () {
                        this[['$element']][['trigger']]('loaded.bs.modal');
                    }, this));
                }
            };
            Modal[['VERSION']] = '3.3.7';
            Modal[['TRANSITION_DURATION']] = 300;
            Modal[['BACKDROP_TRANSITION_DURATION']] = 150;
            Modal[['DEFAULTS']] = {
                backdrop: true,
                keyboard: true,
                show: true
            };
            Modal[['prototype']][['toggle']] = function (_relatedTarget) {
                return this[['isShown']] ? this[['hide']]() : this[['show']](_relatedTarget);
            };
            Modal[['prototype']][['show']] = function (_relatedTarget) {
                var that = this;
                var e = $[['Event']]('show.bs.modal', { relatedTarget: _relatedTarget });
                this[['$element']][['trigger']](e);
                if (this[['isShown']] || e[['isDefaultPrevented']]())
                    return;
                this[['isShown']] = true;
                this[['checkScrollbar']]();
                this[['setScrollbar']]();
                this[['$body']][['addClass']]('modal-open');
                this[['escape']]();
                this[['resize']]();
                this[['$element']][['on']]('click.dismiss.bs.modal', '[data-dismiss="modal"]', $[['proxy']](this[['hide']], this));
                this[['$dialog']][['on']]('mousedown.dismiss.bs.modal', function () {
                    that[['$element']][['one']]('mouseup.dismiss.bs.modal', function (e) {
                        if ($(e[['target']])[['is']](that[['$element']]))
                            that[['ignoreBackdropClick']] = true;
                    });
                });
                this[['backdrop']](function () {
                    var transition = $[['support']][['transition']] && that[['$element']][['hasClass']]('fade');
                    if (!that[['$element']][['parent']]()[['length']]) {
                        that[['$element']][['appendTo']](that[['$body']]);
                    }
                    that[['$element']][['show']]()[['scrollTop']](0);
                    that[['adjustDialog']]();
                    if (transition) {
                        that[['$element']][0][['offsetWidth']];
                    }
                    that[['$element']][['addClass']]('in');
                    that[['enforceFocus']]();
                    var e = $[['Event']]('shown.bs.modal', { relatedTarget: _relatedTarget });
                    transition ? that[['$dialog']][['one']]('bsTransitionEnd', function () {
                        that[['$element']][['trigger']]('focus')[['trigger']](e);
                    })[['emulateTransitionEnd']](Modal[['TRANSITION_DURATION']]) : that[['$element']][['trigger']]('focus')[['trigger']](e);
                });
            };
            Modal[['prototype']][['hide']] = function (e) {
                if (e)
                    e[['preventDefault']]();
                e = $[['Event']]('hide.bs.modal');
                this[['$element']][['trigger']](e);
                if (!this[['isShown']] || e[['isDefaultPrevented']]())
                    return;
                this[['isShown']] = false;
                this[['escape']]();
                this[['resize']]();
                $(document)[['off']]('focusin.bs.modal');
                this[['$element']][['removeClass']]('in')[['off']]('click.dismiss.bs.modal')[['off']]('mouseup.dismiss.bs.modal');
                this[['$dialog']][['off']]('mousedown.dismiss.bs.modal');
                $[['support']][['transition']] && this[['$element']][['hasClass']]('fade') ? this[['$element']][['one']]('bsTransitionEnd', $[['proxy']](this[['hideModal']], this))[['emulateTransitionEnd']](Modal[['TRANSITION_DURATION']]) : this[['hideModal']]();
            };
            Modal[['prototype']][['enforceFocus']] = function () {
                $(document)[['off']]('focusin.bs.modal')[['on']]('focusin.bs.modal', $[['proxy']](function (e) {
                    if (document !== e[['target']] && this[['$element']][0] !== e[['target']] && !this[['$element']][['has']](e[['target']])[['length']]) {
                        this[['$element']][['trigger']]('focus');
                    }
                }, this));
            };
            Modal[['prototype']][['escape']] = function () {
                if (this[['isShown']] && this[['options']][['keyboard']]) {
                    this[['$element']][['on']]('keydown.dismiss.bs.modal', $[['proxy']](function (e) {
                        e[['which']] == 27 && this[['hide']]();
                    }, this));
                } else if (!this[['isShown']]) {
                    this[['$element']][['off']]('keydown.dismiss.bs.modal');
                }
            };
            Modal[['prototype']][['resize']] = function () {
                if (this[['isShown']]) {
                    $(window)[['on']]('resize.bs.modal', $[['proxy']](this[['handleUpdate']], this));
                } else {
                    $(window)[['off']]('resize.bs.modal');
                }
            };
            Modal[['prototype']][['hideModal']] = function () {
                var that = this;
                this[['$element']][['hide']]();
                this[['backdrop']](function () {
                    that[['$body']][['removeClass']]('modal-open');
                    that[['resetAdjustments']]();
                    that[['resetScrollbar']]();
                    that[['$element']][['trigger']]('hidden.bs.modal');
                });
            };
            Modal[['prototype']][['removeBackdrop']] = function () {
                this[['$backdrop']] && this[['$backdrop']][['remove']]();
                this[['$backdrop']] = null;
            };
            Modal[['prototype']][['backdrop']] = function (callback) {
                var that = this;
                var animate = this[['$element']][['hasClass']]('fade') ? 'fade' : '';
                if (this[['isShown']] && this[['options']][['backdrop']]) {
                    var doAnimate = $[['support']][['transition']] && animate;
                    this[['$backdrop']] = $(document[['createElement']]('div'))[['addClass']]('modal-backdrop ' + animate)[['appendTo']](this[['$body']]);
                    this[['$element']][['on']]('click.dismiss.bs.modal', $[['proxy']](function (e) {
                        if (this[['ignoreBackdropClick']]) {
                            this[['ignoreBackdropClick']] = false;
                            return;
                        }
                        if (e[['target']] !== e[['currentTarget']])
                            return;
                        this[['options']][['backdrop']] == 'static' ? this[['$element']][0][['focus']]() : this[['hide']]();
                    }, this));
                    if (doAnimate)
                        this[['$backdrop']][0][['offsetWidth']];
                    this[['$backdrop']][['addClass']]('in');
                    if (!callback)
                        return;
                    doAnimate ? this[['$backdrop']][['one']]('bsTransitionEnd', callback)[['emulateTransitionEnd']](Modal[['BACKDROP_TRANSITION_DURATION']]) : callback();
                } else if (!this[['isShown']] && this[['$backdrop']]) {
                    this[['$backdrop']][['removeClass']]('in');
                    var callbackRemove = function callbackRemove() {
                        that[['removeBackdrop']]();
                        callback && callback();
                    };
                    $[['support']][['transition']] && this[['$element']][['hasClass']]('fade') ? this[['$backdrop']][['one']]('bsTransitionEnd', callbackRemove)[['emulateTransitionEnd']](Modal[['BACKDROP_TRANSITION_DURATION']]) : callbackRemove();
                } else if (callback) {
                    callback();
                }
            };
            Modal[['prototype']][['handleUpdate']] = function () {
                this[['adjustDialog']]();
            };
            Modal[['prototype']][['adjustDialog']] = function () {
                var modalIsOverflowing = this[['$element']][0][['scrollHeight']] > document[['documentElement']][['clientHeight']];
                this[['$element']][['css']]({
                    paddingLeft: !this[['bodyIsOverflowing']] && modalIsOverflowing ? this[['scrollbarWidth']] : '',
                    paddingRight: this[['bodyIsOverflowing']] && !modalIsOverflowing ? this[['scrollbarWidth']] : ''
                });
            };
            Modal[['prototype']][['resetAdjustments']] = function () {
                this[['$element']][['css']]({
                    paddingLeft: '',
                    paddingRight: ''
                });
            };
            Modal[['prototype']][['checkScrollbar']] = function () {
                var fullWindowWidth = window[['innerWidth']];
                if (!fullWindowWidth) {
                    var documentElementRect = document[['documentElement']][['getBoundingClientRect']]();
                    fullWindowWidth = documentElementRect[['right']] - Math[['abs']](documentElementRect[['left']]);
                }
                this[['bodyIsOverflowing']] = document[['body']][['clientWidth']] < fullWindowWidth;
                this[['scrollbarWidth']] = this[['measureScrollbar']]();
            };
            Modal[['prototype']][['setScrollbar']] = function () {
                var bodyPad = parseInt(this[['$body']][['css']]('padding-right') || 0, 10);
                this[['originalBodyPad']] = document[['body']][['style']][['paddingRight']] || '';
                if (this[['bodyIsOverflowing']])
                    this[['$body']][['css']]('padding-right', bodyPad + this[['scrollbarWidth']]);
            };
            Modal[['prototype']][['resetScrollbar']] = function () {
                this[['$body']][['css']]('padding-right', this[['originalBodyPad']]);
            };
            Modal[['prototype']][['measureScrollbar']] = function () {
                var scrollDiv = document[['createElement']]('div');
                scrollDiv[['className']] = 'modal-scrollbar-measure';
                this[['$body']][['append']](scrollDiv);
                var scrollbarWidth = scrollDiv[['offsetWidth']] - scrollDiv[['clientWidth']];
                this[['$body']][0][['removeChild']](scrollDiv);
                return scrollbarWidth;
            };
            function Plugin(option, _relatedTarget) {
                return this[['each']](function () {
                    var $this = $(this);
                    var data = $this[['data']]('bs.modal');
                    var options = $[['extend']]({}, Modal[['DEFAULTS']], $this[['data']](), (typeof option === 'undefined' ? 'undefined' : _typeof(option)) == 'object' && option);
                    if (!data)
                        $this[['data']]('bs.modal', data = new Modal(this, options));
                    if (typeof option == 'string')
                        data[option](_relatedTarget);
                    else if (options[['show']])
                        data[['show']](_relatedTarget);
                });
            }
            var old = $[['fn']][['modal']];
            $[['fn']][['modal']] = Plugin;
            $[['fn']][['modal']][['Constructor']] = Modal;
            $[['fn']][['modal']][['noConflict']] = function () {
                $[['fn']][['modal']] = old;
                return this;
            };
            $(document)[['on']]('click.bs.modal.data-api', '[data-toggle="modal"]', function (e) {
                var $this = $(this);
                var href = $this[['attr']]('href');
                var $target = $($this[['attr']]('data-target') || href && href[['replace']](/.*(?=#[^\s]+$)/, ''));
                var option = $target[['data']]('bs.modal') ? 'toggle' : $[['extend']]({ remote: !/#/[['test']](href) && href }, $target[['data']](), $this[['data']]());
                if ($this[['is']]('a'))
                    e[['preventDefault']]();
                $target[['one']]('show.bs.modal', function (showEvent) {
                    if (showEvent[['isDefaultPrevented']]())
                        return;
                    $target[['one']]('hidden.bs.modal', function () {
                        $this[['is']](':visible') && $this[['trigger']]('focus');
                    });
                });
                Plugin[['call']]($target, option, this);
            });
        }(jQuery);
        (function ($) {
            'use strict';
            var Tab = function Tab(element) {
                this[['element']] = $(element);
            };
            Tab[['VERSION']] = '3.2.0';
            Tab[['prototype']][['show']] = function () {
                var $this = this[['element']];
                var $ul = $this[['closest']]('ul:not(.dropdown-menu)');
                var selector = $this[['data']]('target');
                if (!selector) {
                    selector = $this[['attr']]('href');
                    selector = selector && selector[['replace']](/.*(?=#[^\s]*$)/, '');
                }
                if ($this[['parent']]('li')[['hasClass']]('active'))
                    return;
                var previous = $ul[['find']]('.active:last a')[0];
                var e = $[['Event']]('show.bs.tab', { relatedTarget: previous });
                $this[['trigger']](e);
                if (e[['isDefaultPrevented']]())
                    return;
                var $target = $(selector);
                this[['activate']]($this[['closest']]('li'), $ul);
                this[['activate']]($target, $target[['parent']](), function () {
                    $this[['trigger']]({
                        type: 'shown.bs.tab',
                        relatedTarget: previous
                    });
                });
            };
            Tab[['prototype']][['activate']] = function (element, container, callback) {
                var $active = container[['find']]('> .active');
                var transition = callback && $[['support']][['transition']] && $active[['hasClass']]('fade');
                function next() {
                    $active[['removeClass']]('active')[['find']]('> .dropdown-menu > .active')[['removeClass']]('active');
                    element[['addClass']]('active');
                    if (transition) {
                        element[0][['offsetWidth']];
                        element[['addClass']]('in');
                    } else {
                        element[['removeClass']]('fade');
                    }
                    if (element[['parent']]('.dropdown-menu')) {
                        element[['closest']]('li.dropdown')[['addClass']]('active');
                    }
                    callback && callback();
                }
                transition ? $active[['one']]('bsTransitionEnd', next)[['emulateTransitionEnd']](150) : next();
                $active[['removeClass']]('in');
            };
            function Plugin(option) {
                return this[['each']](function () {
                    var $this = $(this);
                    var data = $this[['data']]('bs.tab');
                    if (!data)
                        $this[['data']]('bs.tab', data = new Tab(this));
                    if (typeof option == 'string')
                        data[option]();
                });
            }
            var old = $[['fn']][['tab']];
            $[['fn']][['tab']] = Plugin;
            $[['fn']][['tab']][['Constructor']] = Tab;
            $[['fn']][['tab']][['noConflict']] = function () {
                $[['fn']][['tab']] = old;
                return this;
            };
            $(document)[['on']]('click.bs.tab.data-api', '[data-toggle="tab"], [data-toggle="pill"]', function (e) {
                e[['preventDefault']]();
                Plugin[['call']]($(this), 'show');
            });
        }(jQuery));
        (function (global, $) {
            $('.input-group')[['on']]('focus', '.form-control', function () {
                $(this)[['closest']]('.input-group, .form-group')[['addClass']]('focus');
            })[['on']]('blur', '.form-control', function () {
                $(this)[['closest']]('.input-group, .form-group')[['removeClass']]('focus');
            });
        }(undefined, jQuery));
        jQuery(function ($) {
            $('[data-toggle="tooltip"]')[['tooltip']]();
        }[['call']](undefined, jQuery));
        jQuery(function ($) {
            $('[data-toggle="checkbox"]')[['radiocheck']]();
            $('[data-toggle="radio"]')[['radiocheck']]();
        }[['call']](undefined, jQuery));
        jQuery(function ($) {
            $('[data-toggle="popover"]')[['popover']]();
        }[['call']](undefined, jQuery));
        jQuery(function ($) {
            $('.pagination')[['on']]('click', 'a', function () {
                $(this)[['parent']]()[['siblings']]('li')[['removeClass']]('active')[['end']]()[['addClass']]('active');
            });
        }[['call']](undefined, jQuery));
        jQuery(function ($) {
            $('.btn-group')[['on']]('click', 'a', function () {
                $(this)[['siblings']]()[['removeClass']]('active')[['end']]()[['addClass']]('active');
            });
        }[['call']](undefined, jQuery));
    }
]));