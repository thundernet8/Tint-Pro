/**
 * Generated on Mon Oct 10 2016 23:44:41 GMT+0800 (中国标准时间) by Zhiyan
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
 
webpackJsonp([
    0,
    10
], [
    function (module, exports, __webpack_require__) {
        (function (jQuery) {
            'use strict';
            var _loading = __webpack_require__(2);
            __webpack_require__(3);
            var _utils = __webpack_require__(4);
            jQuery(document)[['ready']](function ($) {
                (0, _loading[['handleLineLoading']])();
                var _redirectBtn = $('#linkBackHome');
                var _numSpan = _redirectBtn[['children']]('span.num');
                var _countNum = function _countNum(span) {
                    var sec = parseInt(span[['text']]());
                    if (sec - 1 <= 0) {
                        clearInterval(_interval);
                        _redirectBtn[['html']]('\u8df3\u8f6c\u4e2d...');
                        window[['location']][['href']] = _utils[['Utils']][['getSiteUrl']]();
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
        (function ($) {
            'use strict';
            Object[['defineProperty']](exports, '__esModule', { value: true });
            exports[['handleSpinLoading']] = exports[['handleLineLoading']] = undefined;
            var _jquery = __webpack_require__(1);
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
    },
    function (module, exports, __webpack_require__) {
        (function (TT) {
            'use strict';
            Object[['defineProperty']](exports, '__esModule', { value: true });
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
            var Utils = {
                getUrlPara: _getUrlPara,
                getSiteUrl: _getSiteUrl,
                getAbsUrl: _getAbsUrl,
                getAPIUrl: _getAPIUrl,
                isPhoneNum: _isPhoneNum,
                isEmail: _isEmail,
                isUrl: _isUrl,
                isValidUserName: _isValidUserName
            };
            exports[['Utils']] = Utils;
        }[['call']](exports, __webpack_require__(5)));
    },
    function (module, exports) {
        module[['exports']] = TT;
    }
]);