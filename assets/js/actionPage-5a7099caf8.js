/**
 * Generated on Sun Nov 13 2016 23:42:10 GMT+0800 (中国标准时间) by Zhiyan
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
            var _loading = __webpack_require__(8);
            var _msgbox = __webpack_require__(6);
            var _signin = __webpack_require__(10);
            var _signup = __webpack_require__(11);
            var _seasonalBg = __webpack_require__(12);
            jQuery(document)[['ready']](function ($) {
                (0, _loading[['handleLineLoading']])();
                _msgbox[['popMsgbox']][['init']]();
                _msgbox[['msgbox']][['init']]();
                var body = $('body');
                if (body[['hasClass']]('signin')) {
                    (0, _seasonalBg[['handleSeasonalBg']])($('#bg-layer'));
                    _signin[['pageSignIn']][['init']]();
                }
                if (body[['hasClass']]('signup')) {
                    _signup[['pageSignUp']][['init']]();
                }
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
            accountStatus: _utils2[['default']][['getAPIUrl']]('/users/status')
        };
        var urls = {
            site: _utils2[['default']][['getSiteUrl']](),
            signIn: _utils2[['default']][['getSiteUrl']]() + '/m/signin'
        };
        var classes = { appLoading: 'is-loadingApp' };
        exports[['Routes']] = routes;
        exports[['Urls']] = urls;
        exports[['Classes']] = classes;
    },
    function (module, exports, __webpack_require__) {
        (function (TT) {
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
                checkLogin: _checkLogin
            };
            exports[['default']] = Utils;
        }[['call']](exports, __webpack_require__(4)));
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
        var __WEBPACK_AMD_DEFINE_RESULT__;
        var require;
        var require;
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
    ,
    function (module, exports, __webpack_require__) {
        (function ($) {
            'use strict';
            Object[['defineProperty']](exports, '__esModule', { value: true });
            exports[['pageSignIn']] = undefined;
            var _globalConfig = __webpack_require__(2);
            var _msgbox = __webpack_require__(6);
            var _utils = __webpack_require__(3);
            var _utils2 = _interopRequireDefault(_utils);
            function _interopRequireDefault(obj) {
                return obj && obj[['__esModule']] ? obj : { default: obj };
            }
            var _form = $('.form-signin');
            var _userLoginInput = $('#user_login-input');
            var _passwordInput = $('#password-input');
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
                    _showError(_userLoginInput, '\u90ae\u7bb1\u6216\u8005\u4ee5\u5b57\u6bcd\u5f00\u5934\u7684\u82f1\u6587/\u6570\u5b57/\u4e0b\u5212\u7ebf\u7ec4\u5408\u7684\u7528\u6237\u540d');
                    return false;
                } else if (_userLoginInput[['val']]()[['length']] < 5) {
                    _showError(_userLoginInput, '\u8d26\u6237\u957f\u5ea6\u81f3\u5c11\u4e3a 5');
                    return false;
                }
                return true;
            };
            var _validatePassword = function _validatePassword() {
                if (_passwordInput[['val']]() === '') {
                    _showError(_passwordInput, '\u8bf7\u8f93\u5165\u5bc6\u7801');
                    return false;
                } else if (_passwordInput[['val']]()[['length']] < 6) {
                    _showError(_passwordInput, '\u5bc6\u7801\u957f\u5ea6\u81f3\u5c11\u4e3a 6');
                    return false;
                }
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
                input[['parent']]()[['addClass']]('error')[['append']]('<div class="error-tip">' + msg + '</div>');
            };
            var _removeError = function _removeError(input) {
                input[['parent']]()[['removeClass']]('error')[['children']]('.error-tip')[['remove']]();
            };
            var _post = function _post() {
                var url = _globalConfig[['Routes']][['signIn']];
                var beforeSend = function beforeSend() {
                    _form[['addClass']]('submitting');
                    _userLoginInput[['prop']]('disabled', true);
                    _passwordInput[['prop']]('disabled', true);
                    _submitting = true;
                };
                var finishRequest = function finishRequest() {
                    _form[['removeClass']]('submitting');
                    _userLoginInput[['prop']]('disabled', false);
                    _passwordInput[['prop']]('disabled', false);
                    _submitting = false;
                };
                var success = function success(data, textStatus, xhr) {
                    if (data[['success']] && data[['success']] == 1) {
                        var redirect = _utils2[['default']][['getUrlPara']]('redirect') ? _utils2[['default']][['getAbsUrl']](decodeURIComponent(_utils2[['default']][['getUrlPara']]('redirect'))) : _utils2[['default']][['getSiteUrl']]();
                        _msgbox[['popMsgbox']][['success']]({
                            title: '\u767b\u5f55\u6210\u529f',
                            text: '\u5c06\u5728 2s \u5185\u8df3\u8f6c\u81f3 ' + redirect,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(function () {
                            window[['location']][['href']] = redirect;
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
                    data: _utils2[['default']][['filterDataForRest']](_form[['serialize']]()),
                    dataType: 'json',
                    beforeSend: beforeSend,
                    success: success,
                    error: error
                });
            };
            var pageSignIn = {
                init: function init() {
                    $('body')[['on']]('blur', '.local-signin>.input-container>input', function () {
                        _validate($(this));
                    })[['on']]('keyup', '.local-signin>.input-container>input', function (e) {
                        var $this = $(this);
                        _validate($this) ? _removeError($this) : function () {
                        }();
                        if (e[['keyCode']] === 13 && !_submitting && $this[['attr']]('name') === 'password' && _validate()) {
                            _post();
                        }
                    });
                }
            };
            exports[['pageSignIn']] = pageSignIn;
        }[['call']](exports, __webpack_require__(1)));
    },
    function (module, exports, __webpack_require__) {
        (function ($) {
            'use strict';
            Object[['defineProperty']](exports, '__esModule', { value: true });
            exports[['pageSignUp']] = undefined;
            var _globalConfig = __webpack_require__(2);
            var _msgbox = __webpack_require__(6);
            var _utils = __webpack_require__(3);
            var _utils2 = _interopRequireDefault(_utils);
            function _interopRequireDefault(obj) {
                return obj && obj[['__esModule']] ? obj : { default: obj };
            }
            var _form = $('.form-signup');
            var _msgSibling = $('#default-tip');
            var _userLoginInput = $('#user_login-input');
            var _emailInput = $('#email-input');
            var _passwordInput = $('#password-input');
            var _captchaInput = $('#captcha-input');
            var _captchaImg = $('img#captcha');
            var _submitBtn = $('button#signup-btn');
            var _submitBtnText = _submitBtn[['text']]();
            var _submitting = false;
            var _validate = function _validate(input) {
                var showMsg = arguments[['length']] <= 1 || arguments[1] === undefined ? true : arguments[1];
                if (!input) {
                    return _validateUserLogin(showMsg) && _validateEmail(showMsg) && _validatePassword(showMsg) && _validateCaptcha(showMsg);
                } else {
                    var inputName = input[['attr']]('name');
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
            var _validateUserLogin = function _validateUserLogin(showMsg) {
                if (_userLoginInput[['val']]() === '') {
                    if (showMsg) {
                        _msgbox[['msgbox']][['show']]('\u8bf7\u8f93\u5165\u7528\u6237\u540d', 'danger', _msgSibling);
                    }
                    _userLoginInput[['parent']]()[['addClass']]('has-error');
                    return false;
                } else if (!_utils2[['default']][['isValidUserName']](_userLoginInput[['val']]()) && !_utils2[['default']][['isEmail']](_userLoginInput[['val']]())) {
                    if (showMsg) {
                        _msgbox[['msgbox']][['show']]('\u7528\u6237\u540d\u5fc5\u987b\u4ee5\u5b57\u6bcd\u5f00\u5934, \u82f1\u6587/\u6570\u5b57/\u4e0b\u5212\u7ebf\u7ec4\u5408', 'danger', _msgSibling);
                    }
                    _userLoginInput[['parent']]()[['addClass']]('has-error');
                    return false;
                } else if (_userLoginInput[['val']]()[['length']] < 5) {
                    if (showMsg) {
                        _msgbox[['msgbox']][['show']]('\u8d26\u6237\u957f\u5ea6\u81f3\u5c11\u4e3a 5', 'danger', _msgSibling);
                    }
                    _userLoginInput[['parent']]()[['addClass']]('has-error');
                    return false;
                }
                _userLoginInput[['parent']]()[['removeClass']]('has-error');
                return true;
            };
            var _validateEmail = function _validateEmail(showMsg) {
                if (_emailInput[['val']]() === '') {
                    if (showMsg) {
                        _msgbox[['msgbox']][['show']]('\u8bf7\u586b\u5199\u90ae\u7bb1', 'danger', _msgSibling);
                    }
                    _emailInput[['parent']]()[['addClass']]('has-error');
                    return false;
                } else if (!_utils2[['default']][['isEmail']](_emailInput[['val']]())) {
                    if (showMsg) {
                        _msgbox[['msgbox']][['show']]('\u90ae\u7bb1\u683c\u5f0f\u4e0d\u6b63\u786e', 'danger', _msgSibling);
                    }
                    _emailInput[['parent']]()[['addClass']]('has-error');
                    return false;
                }
                _emailInput[['parent']]()[['removeClass']]('has-error');
                return true;
            };
            var _validatePassword = function _validatePassword(showMsg) {
                if (_passwordInput[['val']]() === '') {
                    if (showMsg) {
                        _msgbox[['msgbox']][['show']]('\u8bf7\u8f93\u5165\u5bc6\u7801', 'danger', _msgSibling);
                    }
                    _passwordInput[['parent']]()[['addClass']]('has-error');
                    return false;
                } else if (_passwordInput[['val']]()[['length']] < 6) {
                    if (showMsg) {
                        _msgbox[['msgbox']][['show']]('\u5bc6\u7801\u957f\u5ea6\u81f3\u5c11\u4e3a 6', 'danger', _msgSibling);
                    }
                    _passwordInput[['parent']]()[['addClass']]('has-error');
                    return false;
                }
                _passwordInput[['parent']]()[['removeClass']]('has-error');
                return true;
            };
            var _validateCaptcha = function _validateCaptcha(showMsg) {
                if (_captchaInput[['val']]() === '') {
                    if (showMsg) {
                        _msgbox[['msgbox']][['show']]('\u9a8c\u8bc1\u7801\u4e0d\u80fd\u4e3a\u7a7a', 'danger', _msgSibling);
                    }
                    _captchaInput[['parent']]()[['addClass']]('has-error');
                    return false;
                } else if (_captchaInput[['val']]()[['length']] != 4) {
                    if (showMsg) {
                        _msgbox[['msgbox']][['show']]('\u9a8c\u8bc1\u7801\u957f\u5ea6\u5fc5\u987b\u4e3a 4 \u4f4d', 'danger', _msgSibling);
                    }
                    _captchaInput[['parent']]()[['addClass']]('has-error');
                    return false;
                }
                _captchaInput[['parent']]()[['removeClass']]('has-error');
                return true;
            };
            var _removeMsg = function _removeMsg() {
                $('.form-signup>.msg')[['remove']]();
            };
            var _handleInputStatus = function _handleInputStatus(disable) {
                _userLoginInput[['prop']]('disabled', disable);
                _emailInput[['prop']]('disabled', disable);
                _passwordInput[['prop']]('disabled', disable);
                _captchaInput[['prop']]('disabled', disable);
            };
            var _handleCaptchaRefresh = function _handleCaptchaRefresh(captcha) {
                var captchaSel = captcha ? captcha : _captchaImg;
                var originCaptchaUrl = captchaSel[['attr']]('src');
                var date = new Date();
                var tQueryStr = date[['getMilliseconds']]() / 1000 + '00000_' + date[['getTime']]();
                var newCaptchaUrl = originCaptchaUrl[['replace']](/\?t=([0-9_\.]+)/, '?t=' + tQueryStr);
                captchaSel[['attr']]('src', newCaptchaUrl);
            };
            var _handleSubmitBtnStatus = function _handleSubmitBtnStatus(disable) {
                var status = !!disable;
                _submitBtn[['prop']]('disabled', status);
            };
            var _handleSubmitBtnHtml = function _handleSubmitBtnHtml(submitting) {
                if (submitting) {
                    _submitBtn[['html']]('<span class="indicator spinner tico tico-spinner3"></span>');
                } else {
                    _submitBtn[['html']]('')[['text']](_submitBtnText);
                }
            };
            var _handleSuccess = function _handleSuccess() {
                var title = '\u6ce8\u518c\u5b8c\u6210';
                var message = '\u8fd8\u5dee\u4e00\u6b65\u60a8\u5c31\u80fd\u6b63\u5f0f\u62e5\u6709\u4e00\u4e2a\u672c\u7ad9\u8d26\u6237\uff0c\u8bf7\u7acb\u5373\u8bbf\u95ee\u4f60\u6ce8\u518c\u65f6\u63d0\u4f9b\u7684\u90ae\u7bb1\uff0c\u70b9\u51fb\u6fc0\u6d3b\u94fe\u63a5\u5b8c\u6210\u6700\u7ec8\u8d26\u6237\u6ce8\u518c.<br>\u5982\u679c\u60a8\u6ca1\u6709\u6536\u5230\u90ae\u4ef6\uff0c\u8bf7\u67e5\u770b\u5783\u573e\u7bb1\u6216\u90ae\u7bb1\u62e6\u622a\u8bb0\u5f55\uff0c\u5982\u679c\u4ecd\u672a\u83b7\u5f97\u6fc0\u6d3b\u94fe\u63a5\uff0c\u8bf7\u8054\u7cfb\u7f51\u7ad9\u7ba1\u7406\u5458.';
                _form[['html']]('<h2 class="title signup-title mb30">' + title + '</h2>' + '<p id="default-tip">' + message + '</p>');
            };
            var _post = function _post() {
                var url = _globalConfig[['Routes']][['signUp']];
                var beforeSend = function beforeSend() {
                    _handleInputStatus(true);
                    _handleSubmitBtnStatus(true);
                    _submitting = true;
                    _handleSubmitBtnHtml(true);
                };
                var finishRequest = function finishRequest() {
                    _handleInputStatus(false);
                    _handleSubmitBtnStatus(false);
                    _submitting = false;
                    _handleSubmitBtnHtml(false);
                };
                var success = function success(data, textStatus, xhr) {
                    if (data[['success']] && data[['success']] == 1) {
                        var redirect = _utils2[['default']][['getUrlPara']]('redirect') ? _utils2[['default']][['getAbsUrl']](decodeURIComponent(_utils2[['default']][['getUrlPara']]('redirect'))) : _utils2[['default']][['getSiteUrl']]();
                        _msgbox[['popMsgbox']][['success']]({
                            title: '\u8bf7\u6c42\u6ce8\u518c\u6210\u529f',
                            text: '\u8bf7\u81f3\u60a8\u7684\u90ae\u7bb1\u67e5\u8be2\u5e76\u8bbf\u95ee\u8d26\u6237\u6fc0\u6d3b\u94fe\u63a5\u4ee5\u6700\u7ec8\u5b8c\u6210\u8d26\u6237\u7684\u6ce8\u518c.',
                            showConfirmButton: true
                        });
                        _handleSuccess();
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
                        text: xhr[['responseJSON']][['message']]
                    });
                    finishRequest();
                };
                $[['post']]({
                    url: url,
                    data: _form[['serialize']](),
                    dataType: 'json',
                    beforeSend: beforeSend,
                    success: success,
                    error: error
                });
            };
            var pageSignUp = {
                init: function init() {
                    var body = $('body');
                    body[['on']]('blur', '.local-signup>.input-container input', function () {
                        _validate($(this));
                    })[['on']]('keyup', '.local-signup>.input-container input', function () {
                        var validateResult = _validate(null, false);
                        _handleSubmitBtnStatus(!validateResult);
                        if (validateResult) {
                            _removeMsg();
                        }
                    });
                    body[['on']]('click', 'img.captcha', function () {
                        _handleCaptchaRefresh($(this));
                    });
                    body[['on']]('click', '.local-signup>#signup-btn', function () {
                        if (_validate()) {
                            _post();
                        }
                        return false;
                    });
                }
            };
            exports[['pageSignUp']] = pageSignUp;
        }[['call']](exports, __webpack_require__(1)));
    },
    function (module, exports, __webpack_require__) {
        (function (TT, $) {
            'use strict';
            Object[['defineProperty']](exports, '__esModule', { value: true });
            exports[['handleSeasonalBg']] = undefined;
            var _utils = __webpack_require__(3);
            var _utils2 = _interopRequireDefault(_utils);
            function _interopRequireDefault(obj) {
                return obj && obj[['__esModule']] ? obj : { default: obj };
            }
            var _getSeason = function _getSeason(month) {
                month = parseInt(month);
                switch (month) {
                case 3:
                case 4:
                case 5:
                    return 'Spring';
                    break;
                case 6:
                case 7:
                case 8:
                    return 'Summer';
                    break;
                case 9:
                case 10:
                case 11:
                    return 'Autumn';
                    break;
                case 12:
                case 1:
                case 2:
                    return 'Winter';
                    break;
                }
            };
            var _getPeriod = function _getPeriod(hour) {
                hour = parseInt(hour);
                if (hour >= 5 && hour < 11) {
                    return 'Morning';
                }
                if (hour >= 11 && hour < 16) {
                    return 'Noon';
                }
                if (hour >= 16 && hour < 19) {
                    return 'Evening';
                }
                return 'Night';
            };
            var _getSeasonalBg = function _getSeasonalBg() {
                var bgRootUrl = TT && TT[['themeRoot']] ? TT[['themeRoot']] + '/assets/img/spotlight/' : _utils2[['default']][['getSiteUrl']]() + '/wp-content/themes/Tint/assets/img/spotlight/';
                var _date = new Date();
                return bgRootUrl + _getSeason(_date[['getMonth']]() + 1)[['toLowerCase']]() + '/' + _getPeriod(_date[['getHours']]())[['toLowerCase']]() + '.jpg';
            };
            var _handleSeasonalBg = function _handleSeasonalBg(sel) {
                var changeBg = function changeBg() {
                    var bgLayer = sel ? sel : $('body');
                    bgLayer[['css']]('background-image', 'url(' + _getSeasonalBg() + ')');
                };
                changeBg(sel);
                setInterval(changeBg[['bind']](this, sel), 1000 * 60);
            };
            exports[['handleSeasonalBg']] = _handleSeasonalBg;
        }[['call']](exports, __webpack_require__(4), __webpack_require__(1)));
    }
]));