/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/10 16:15
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

var jQuery = require('jquery');

/* ============================================================
 * flatui-radiocheck v0.1.0
 * ============================================================ */

(function (global, $) {
  'use strict';

  var Radiocheck = function (element, options) {
    this.init('radiocheck', element, options);
  };

  Radiocheck.DEFAULTS = {
    checkboxClass: 'custom-checkbox',
    radioClass: 'custom-radio',
    checkboxTemplate: '<span class="icons"><span class="icon-unchecked"></span><span class="icon-checked"></span></span>',
    radioTemplate: '<span class="icons"><span class="icon-unchecked"></span><span class="icon-checked"></span></span>'
  };

  Radiocheck.prototype.init = function (type, element, options) {
    this.$element = $(element);
    this.options = $.extend({}, Radiocheck.DEFAULTS, this.$element.data(), options);
    if (this.$element.attr('type') == 'checkbox') {
      this.$element.addClass(this.options.checkboxClass);
      this.$element.after(this.options.checkboxTemplate);
    } else if (this.$element.attr('type') == 'radio') {
      this.$element.addClass(this.options.radioClass);
      this.$element.after(this.options.radioTemplate);
    }
  };

  Radiocheck.prototype.check = function () {
    this.$element.prop('checked', true);
    this.$element.trigger('change.radiocheck').trigger('checked.radiocheck');
  },

    Radiocheck.prototype.uncheck = function () {
      this.$element.prop('checked', false);
      this.$element.trigger('change.radiocheck').trigger('unchecked.radiocheck');
    },

    Radiocheck.prototype.toggle = function () {
      this.$element.prop('checked', function (i, value) {
        return !value;
      });
      this.$element.trigger('change.radiocheck').trigger('toggled.radiocheck');
    },

    Radiocheck.prototype.indeterminate = function () {
      this.$element.prop('indeterminate', true);
      this.$element.trigger('change.radiocheck').trigger('indeterminated.radiocheck');
    },

    Radiocheck.prototype.determinate = function () {
      this.$element.prop('indeterminate', false);
      this.$element.trigger('change.radiocheck').trigger('determinated.radiocheck');
    },

    Radiocheck.prototype.disable = function () {
      this.$element.prop('disabled', true);
      this.$element.trigger('change.radiocheck').trigger('disabled.radiocheck');
    },

    Radiocheck.prototype.enable = function () {
      this.$element.prop('disabled', false);
      this.$element.trigger('change.radiocheck').trigger('enabled.radiocheck');
    },

    Radiocheck.prototype.destroy = function () {
      this.$element.removeData().removeClass(this.options.checkboxClass + ' ' + this.options.radioClass).next('.icons').remove();
      this.$element.trigger('destroyed.radiocheck');
    };

  // RADIOCHECK PLUGIN DEFINITION
  // ============================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this);
      var data    = $this.data('radiocheck');
      var options = typeof option == 'object' && option;

      if (!data && option == 'destroy') { return; }
      if (!data) {
        $this.data('radiocheck', (data = new Radiocheck(this, options)));
      }
      if (typeof option == 'string') {
        data[option]();
      }

      // Adding 'nohover' class for mobile devices

      var mobile = /mobile|tablet|phone|ip(ad|od)|android|silk|webos/i.test(global.navigator.userAgent);

      if (mobile === true) {
        $this.parent().hover(function () {
          $this.addClass('nohover');
        }, function () {
          $this.removeClass('nohover');
        });
      }
    });
  }

  var old = $.fn.radiocheck;

  $.fn.radiocheck             = Plugin;
  $.fn.radiocheck.Constructor = Radiocheck;

  // RADIOCHECK NO CONFLICT
  // ======================

  $.fn.radiocheck.noConflict = function () {
    $.fn.radiocheck = old;
    return this;
  };

})(this, jQuery);

/* ========================================================================
 * Bootstrap: tooltip.js v3.2.0
 * http://getbootstrap.com/javascript/#tooltip
 * Inspired by the original jQuery.tipsy by Jason Frame
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

(function ($) {
  'use strict';

  // TOOLTIP PUBLIC CLASS DEFINITION
  // ===============================

  var Tooltip = function (element, options) {
    this.type       =
      this.options    =
        this.enabled    =
          this.timeout    =
            this.hoverState =
              this.$element   = null

    this.init('tooltip', element, options)
  }

  Tooltip.VERSION  = '3.2.0'

  Tooltip.DEFAULTS = {
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
  }

  Tooltip.prototype.init = function (type, element, options) {
    this.enabled   = true
    this.type      = type
    this.$element  = $(element)
    this.options   = this.getOptions(options)
    this.$viewport = this.options.viewport && $(this.options.viewport.selector || this.options.viewport)

    var triggers = this.options.trigger.split(' ')

    for (var i = triggers.length; i--;) {
      var trigger = triggers[i]

      if (trigger == 'click') {
        this.$element.on('click.' + this.type, this.options.selector, $.proxy(this.toggle, this))
      } else if (trigger != 'manual') {
        var eventIn  = trigger == 'hover' ? 'mouseenter' : 'focusin'
        var eventOut = trigger == 'hover' ? 'mouseleave' : 'focusout'

        this.$element.on(eventIn  + '.' + this.type, this.options.selector, $.proxy(this.enter, this))
        this.$element.on(eventOut + '.' + this.type, this.options.selector, $.proxy(this.leave, this))
      }
    }

    this.options.selector ?
      (this._options = $.extend({}, this.options, { trigger: 'manual', selector: '' })) :
      this.fixTitle()
  }

  Tooltip.prototype.getDefaults = function () {
    return Tooltip.DEFAULTS
  }

  Tooltip.prototype.getOptions = function (options) {
    options = $.extend({}, this.getDefaults(), this.$element.data(), options)

    if (options.delay && typeof options.delay == 'number') {
      options.delay = {
        show: options.delay,
        hide: options.delay
      }
    }

    return options
  }

  Tooltip.prototype.getDelegateOptions = function () {
    var options  = {}
    var defaults = this.getDefaults()

    this._options && $.each(this._options, function (key, value) {
      if (defaults[key] != value) options[key] = value
    })

    return options
  }

  Tooltip.prototype.enter = function (obj) {
    var self = obj instanceof this.constructor ?
      obj : $(obj.currentTarget).data('bs.' + this.type)

    if (!self) {
      self = new this.constructor(obj.currentTarget, this.getDelegateOptions())
      $(obj.currentTarget).data('bs.' + this.type, self)
    }

    clearTimeout(self.timeout)

    self.hoverState = 'in'

    if (!self.options.delay || !self.options.delay.show) return self.show()

    self.timeout = setTimeout(function () {
      if (self.hoverState == 'in') self.show()
    }, self.options.delay.show)
  }

  Tooltip.prototype.leave = function (obj) {
    var self = obj instanceof this.constructor ?
      obj : $(obj.currentTarget).data('bs.' + this.type)

    if (!self) {
      self = new this.constructor(obj.currentTarget, this.getDelegateOptions())
      $(obj.currentTarget).data('bs.' + this.type, self)
    }

    clearTimeout(self.timeout)

    self.hoverState = 'out'

    if (!self.options.delay || !self.options.delay.hide) return self.hide()

    self.timeout = setTimeout(function () {
      if (self.hoverState == 'out') self.hide()
    }, self.options.delay.hide)
  }

  Tooltip.prototype.show = function () {
    var e = $.Event('show.bs.' + this.type)

    if (this.hasContent() && this.enabled) {
      this.$element.trigger(e)

      var inDom = $.contains(document.documentElement, this.$element[0])
      if (e.isDefaultPrevented() || !inDom) return
      var that = this

      var $tip = this.tip()

      var tipId = this.getUID(this.type)

      this.setContent()
      $tip.attr('id', tipId)
      this.$element.attr('aria-describedby', tipId)

      if (this.options.animation) $tip.addClass('fade')

      var placement = typeof this.options.placement == 'function' ?
        this.options.placement.call(this, $tip[0], this.$element[0]) :
        this.options.placement

      var autoToken = /\s?auto?\s?/i
      var autoPlace = autoToken.test(placement)
      if (autoPlace) placement = placement.replace(autoToken, '') || 'top'

      $tip
        .detach()
        .css({ top: 0, left: 0, display: 'block' })
        .addClass(placement)
        .data('bs.' + this.type, this)

      this.options.container ? $tip.appendTo(this.options.container) : $tip.insertAfter(this.$element)

      var pos          = this.getPosition()
      var actualWidth  = $tip[0].offsetWidth
      var actualHeight = $tip[0].offsetHeight

      if (autoPlace) {
        var orgPlacement = placement
        var $parent      = this.$element.parent()
        var parentDim    = this.getPosition($parent)

        placement = placement == 'bottom' && pos.top   + pos.height       + actualHeight - parentDim.scroll > parentDim.height ? 'top'    :
          placement == 'top'    && pos.top   - parentDim.scroll - actualHeight < 0                                   ? 'bottom' :
            placement == 'right'  && pos.right + actualWidth      > parentDim.width                                    ? 'left'   :
              placement == 'left'   && pos.left  - actualWidth      < parentDim.left                                     ? 'right'  :
                placement

        $tip
          .removeClass(orgPlacement)
          .addClass(placement)
      }

      var calculatedOffset = this.getCalculatedOffset(placement, pos, actualWidth, actualHeight)

      this.applyPlacement(calculatedOffset, placement)

      var complete = function () {
        that.$element.trigger('shown.bs.' + that.type)
        that.hoverState = null
      }

      $.support.transition && this.$tip.hasClass('fade') ?
        $tip
          .one('bsTransitionEnd', complete)
          .emulateTransitionEnd(150) :
        complete()
    }
  }

  Tooltip.prototype.applyPlacement = function (offset, placement) {
    var $tip   = this.tip()
    var width  = $tip[0].offsetWidth
    var height = $tip[0].offsetHeight

    // manually read margins because getBoundingClientRect includes difference
    var marginTop = parseInt($tip.css('margin-top'), 10)
    var marginLeft = parseInt($tip.css('margin-left'), 10)

    // we must check for NaN for ie 8/9
    if (isNaN(marginTop))  marginTop  = 0
    if (isNaN(marginLeft)) marginLeft = 0

    offset.top  = offset.top  + marginTop
    offset.left = offset.left + marginLeft

    // $.fn.offset doesn't round pixel values
    // so we use setOffset directly with our own function B-0
    $.offset.setOffset($tip[0], $.extend({
      using: function (props) {
        $tip.css({
          top: Math.round(props.top),
          left: Math.round(props.left)
        })
      }
    }, offset), 0)

    $tip.addClass('in')

    // check to see if placing tip in new offset caused the tip to resize itself
    var actualWidth  = $tip[0].offsetWidth
    var actualHeight = $tip[0].offsetHeight

    if (placement == 'top' && actualHeight != height) {
      offset.top = offset.top + height - actualHeight
    }

    var delta = this.getViewportAdjustedDelta(placement, offset, actualWidth, actualHeight)

    if (delta.left) offset.left += delta.left
    else offset.top += delta.top

    var arrowDelta          = delta.left ? delta.left * 2 - width + actualWidth : delta.top * 2 - height + actualHeight
    var arrowPosition       = delta.left ? 'left'        : 'top'
    var arrowOffsetPosition = delta.left ? 'offsetWidth' : 'offsetHeight'

    $tip.offset(offset)
    this.replaceArrow(arrowDelta, $tip[0][arrowOffsetPosition], arrowPosition)
  }

  Tooltip.prototype.replaceArrow = function (delta, dimension, position) {
    this.arrow().css(position, delta ? (50 * (1 - delta / dimension) + '%') : '')
  }

  Tooltip.prototype.setContent = function () {
    var $tip  = this.tip()
    var title = this.getTitle()

    $tip.find('.tooltip-inner')[this.options.html ? 'html' : 'text'](title)
    $tip.removeClass('fade in top bottom left right')
  }

  Tooltip.prototype.hide = function () {
    var that = this
    var $tip = this.tip()
    var e    = $.Event('hide.bs.' + this.type)

    this.$element.removeAttr('aria-describedby')

    function complete() {
      if (that.hoverState != 'in') $tip.detach()
      that.$element.trigger('hidden.bs.' + that.type)
    }

    this.$element.trigger(e)

    if (e.isDefaultPrevented()) return

    $tip.removeClass('in')

    $.support.transition && this.$tip.hasClass('fade') ?
      $tip
        .one('bsTransitionEnd', complete)
        .emulateTransitionEnd(150) :
      complete()

    this.hoverState = null

    return this
  }

  Tooltip.prototype.fixTitle = function () {
    var $e = this.$element
    if ($e.attr('title') || typeof ($e.attr('data-original-title')) != 'string') {
      $e.attr('data-original-title', $e.attr('title') || '').attr('title', '')
    }
  }

  Tooltip.prototype.hasContent = function () {
    return this.getTitle()
  }

  Tooltip.prototype.getPosition = function ($element) {
    $element   = $element || this.$element
    var el     = $element[0]
    var isBody = el.tagName == 'BODY'
    return $.extend({}, (typeof el.getBoundingClientRect == 'function') ? el.getBoundingClientRect() : null, {
      scroll: isBody ? document.documentElement.scrollTop || document.body.scrollTop : $element.scrollTop(),
      width:  isBody ? $(window).width()  : $element.outerWidth(),
      height: isBody ? $(window).height() : $element.outerHeight()
    }, isBody ? { top: 0, left: 0 } : $element.offset())
  }

  Tooltip.prototype.getCalculatedOffset = function (placement, pos, actualWidth, actualHeight) {
    return placement == 'bottom' ? { top: pos.top + pos.height,   left: pos.left + pos.width / 2 - actualWidth / 2  } :
      placement == 'top'    ? { top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2  } :
        placement == 'left'   ? { top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth } :
          /* placement == 'right' */ { top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width   }

  }

  Tooltip.prototype.getViewportAdjustedDelta = function (placement, pos, actualWidth, actualHeight) {
    var delta = { top: 0, left: 0 }
    if (!this.$viewport) return delta

    var viewportPadding = this.options.viewport && this.options.viewport.padding || 0
    var viewportDimensions = this.getPosition(this.$viewport)

    if (/right|left/.test(placement)) {
      var topEdgeOffset    = pos.top - viewportPadding - viewportDimensions.scroll
      var bottomEdgeOffset = pos.top + viewportPadding - viewportDimensions.scroll + actualHeight
      if (topEdgeOffset < viewportDimensions.top) { // top overflow
        delta.top = viewportDimensions.top - topEdgeOffset
      } else if (bottomEdgeOffset > viewportDimensions.top + viewportDimensions.height) { // bottom overflow
        delta.top = viewportDimensions.top + viewportDimensions.height - bottomEdgeOffset
      }
    } else {
      var leftEdgeOffset  = pos.left - viewportPadding
      var rightEdgeOffset = pos.left + viewportPadding + actualWidth
      if (leftEdgeOffset < viewportDimensions.left) { // left overflow
        delta.left = viewportDimensions.left - leftEdgeOffset
      } else if (rightEdgeOffset > viewportDimensions.width) { // right overflow
        delta.left = viewportDimensions.left + viewportDimensions.width - rightEdgeOffset
      }
    }

    return delta
  }

  Tooltip.prototype.getTitle = function () {
    var title
    var $e = this.$element
    var o  = this.options

    title = $e.attr('data-original-title')
      || (typeof o.title == 'function' ? o.title.call($e[0]) :  o.title)

    return title
  }

  Tooltip.prototype.getUID = function (prefix) {
    do prefix += ~~(Math.random() * 1000000)
    while (document.getElementById(prefix))
    return prefix
  }

  Tooltip.prototype.tip = function () {
    return (this.$tip = this.$tip || $(this.options.template))
  }

  Tooltip.prototype.arrow = function () {
    return (this.$arrow = this.$arrow || this.tip().find('.tooltip-arrow'))
  }

  Tooltip.prototype.validate = function () {
    if (!this.$element[0].parentNode) {
      this.hide()
      this.$element = null
      this.options  = null
    }
  }

  Tooltip.prototype.enable = function () {
    this.enabled = true
  }

  Tooltip.prototype.disable = function () {
    this.enabled = false
  }

  Tooltip.prototype.toggleEnabled = function () {
    this.enabled = !this.enabled
  }

  Tooltip.prototype.toggle = function (e) {
    var self = this
    if (e) {
      self = $(e.currentTarget).data('bs.' + this.type)
      if (!self) {
        self = new this.constructor(e.currentTarget, this.getDelegateOptions())
        $(e.currentTarget).data('bs.' + this.type, self)
      }
    }

    self.tip().hasClass('in') ? self.leave(self) : self.enter(self)
  }

  Tooltip.prototype.destroy = function () {
    clearTimeout(this.timeout)
    this.hide().$element.off('.' + this.type).removeData('bs.' + this.type)
  }


  // TOOLTIP PLUGIN DEFINITION
  // =========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.tooltip')
      var options = typeof option == 'object' && option

      if (!data && option == 'destroy') return
      if (!data) $this.data('bs.tooltip', (data = new Tooltip(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.tooltip

  $.fn.tooltip             = Plugin
  $.fn.tooltip.Constructor = Tooltip


  // TOOLTIP NO CONFLICT
  // ===================

  $.fn.tooltip.noConflict = function () {
    $.fn.tooltip = old
    return this
  }

})(jQuery);

/* ========================================================================
 * bootstrap-switch - v3.0.2
 * http://www.bootstrap-switch.org
 * ========================================================================
 * Copyright 2012-2013 Mattia Larentis
 *
 * ========================================================================
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================================
 */

// (function () {
//   var __slice = [].slice;
//
//   (function($, window) {
//     "use strict";
//     var BootstrapSwitch;
//     BootstrapSwitch = (function() {
//       function BootstrapSwitch(element, options) {
//         if (options == null) {
//           options = {};
//         }
//         this.$element = $(element);
//         this.options = $.extend({}, $.fn.bootstrapSwitch.defaults, {
//           state: this.$element.is(":checked"),
//           size: this.$element.data("size"),
//           animate: this.$element.data("animate"),
//           disabled: this.$element.is(":disabled"),
//           readonly: this.$element.is("[readonly]"),
//           indeterminate: this.$element.data("indeterminate"),
//           onColor: this.$element.data("on-color"),
//           offColor: this.$element.data("off-color"),
//           onText: this.$element.data("on-text"),
//           offText: this.$element.data("off-text"),
//           labelText: this.$element.data("label-text"),
//           baseClass: this.$element.data("base-class"),
//           wrapperClass: this.$element.data("wrapper-class"),
//           radioAllOff: this.$element.data("radio-all-off")
//         }, options);
//         this.$wrapper = $("<div>", {
//           "class": (function(_this) {
//             return function() {
//               var classes;
//               classes = ["" + _this.options.baseClass].concat(_this._getClasses(_this.options.wrapperClass));
//               classes.push(_this.options.state ? "" + _this.options.baseClass + "-on" : "" + _this.options.baseClass + "-off");
//               if (_this.options.size != null) {
//                 classes.push("" + _this.options.baseClass + "-" + _this.options.size);
//               }
//               if (_this.options.animate) {
//                 classes.push("" + _this.options.baseClass + "-animate");
//               }
//               if (_this.options.disabled) {
//                 classes.push("" + _this.options.baseClass + "-disabled");
//               }
//               if (_this.options.readonly) {
//                 classes.push("" + _this.options.baseClass + "-readonly");
//               }
//               if (_this.options.indeterminate) {
//                 classes.push("" + _this.options.baseClass + "-indeterminate");
//               }
//               if (_this.$element.attr("id")) {
//                 classes.push("" + _this.options.baseClass + "-id-" + (_this.$element.attr("id")));
//               }
//               return classes.join(" ");
//             };
//           })(this)()
//         });
//         this.$container = $("<div>", {
//           "class": "" + this.options.baseClass + "-container"
//         });
//         this.$on = $("<span>", {
//           html: this.options.onText,
//           "class": "" + this.options.baseClass + "-handle-on " + this.options.baseClass + "-" + this.options.onColor
//         });
//         this.$off = $("<span>", {
//           html: this.options.offText,
//           "class": "" + this.options.baseClass + "-handle-off " + this.options.baseClass + "-" + this.options.offColor
//         });
//         this.$label = $("<label>", {
//           html: this.options.labelText,
//           "class": "" + this.options.baseClass + "-label"
//         });
//         if (this.options.indeterminate) {
//           this.$element.prop("indeterminate", true);
//         }
//         this.$element.on("init.bootstrapSwitch", (function(_this) {
//           return function() {
//             return _this.options.onInit.apply(element, arguments);
//           };
//         })(this));
//         this.$element.on("switchChange.bootstrapSwitch", (function(_this) {
//           return function() {
//             return _this.options.onSwitchChange.apply(element, arguments);
//           };
//         })(this));
//         this.$container = this.$element.wrap(this.$container).parent();
//         this.$wrapper = this.$container.wrap(this.$wrapper).parent();
//         this.$element.before(this.$on).before(this.$label).before(this.$off).trigger("init.bootstrapSwitch");
//         this._elementHandlers();
//         this._handleHandlers();
//         this._labelHandlers();
//         this._formHandler();
//       }
//
//       BootstrapSwitch.prototype._constructor = BootstrapSwitch;
//
//       BootstrapSwitch.prototype.state = function(value, skip) {
//         if (typeof value === "undefined") {
//           return this.options.state;
//         }
//         if (this.options.disabled || this.options.readonly || this.options.indeterminate) {
//           return this.$element;
//         }
//         if (this.options.state && !this.options.radioAllOff && this.$element.is(':radio')) {
//           return this.$element;
//         }
//         value = !!value;
//         this.$element.prop("checked", value).trigger("change.bootstrapSwitch", skip);
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.toggleState = function(skip) {
//         if (this.options.disabled || this.options.readonly || this.options.indeterminate) {
//           return this.$element;
//         }
//         return this.$element.prop("checked", !this.options.state).trigger("change.bootstrapSwitch", skip);
//       };
//
//       BootstrapSwitch.prototype.size = function(value) {
//         if (typeof value === "undefined") {
//           return this.options.size;
//         }
//         if (this.options.size != null) {
//           this.$wrapper.removeClass("" + this.options.baseClass + "-" + this.options.size);
//         }
//         if (value) {
//           this.$wrapper.addClass("" + this.options.baseClass + "-" + value);
//         }
//         this.options.size = value;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.animate = function(value) {
//         if (typeof value === "undefined") {
//           return this.options.animate;
//         }
//         value = !!value;
//         this.$wrapper[value ? "addClass" : "removeClass"]("" + this.options.baseClass + "-animate");
//         this.options.animate = value;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.disabled = function(value) {
//         if (typeof value === "undefined") {
//           return this.options.disabled;
//         }
//         value = !!value;
//         this.$wrapper[value ? "addClass" : "removeClass"]("" + this.options.baseClass + "-disabled");
//         this.$element.prop("disabled", value);
//         this.options.disabled = value;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.toggleDisabled = function() {
//         this.$element.prop("disabled", !this.options.disabled);
//         this.$wrapper.toggleClass("" + this.options.baseClass + "-disabled");
//         this.options.disabled = !this.options.disabled;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.readonly = function(value) {
//         if (typeof value === "undefined") {
//           return this.options.readonly;
//         }
//         value = !!value;
//         this.$wrapper[value ? "addClass" : "removeClass"]("" + this.options.baseClass + "-readonly");
//         this.$element.prop("readonly", value);
//         this.options.readonly = value;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.toggleReadonly = function() {
//         this.$element.prop("readonly", !this.options.readonly);
//         this.$wrapper.toggleClass("" + this.options.baseClass + "-readonly");
//         this.options.readonly = !this.options.readonly;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.indeterminate = function(value) {
//         if (typeof value === "undefined") {
//           return this.options.indeterminate;
//         }
//         value = !!value;
//         this.$wrapper[value ? "addClass" : "removeClass"]("" + this.options.baseClass + "-indeterminate");
//         this.$element.prop("indeterminate", value);
//         this.options.indeterminate = value;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.toggleIndeterminate = function() {
//         this.$element.prop("indeterminate", !this.options.indeterminate);
//         this.$wrapper.toggleClass("" + this.options.baseClass + "-indeterminate");
//         this.options.indeterminate = !this.options.indeterminate;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.onColor = function(value) {
//         var color;
//         color = this.options.onColor;
//         if (typeof value === "undefined") {
//           return color;
//         }
//         if (color != null) {
//           this.$on.removeClass("" + this.options.baseClass + "-" + color);
//         }
//         this.$on.addClass("" + this.options.baseClass + "-" + value);
//         this.options.onColor = value;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.offColor = function(value) {
//         var color;
//         color = this.options.offColor;
//         if (typeof value === "undefined") {
//           return color;
//         }
//         if (color != null) {
//           this.$off.removeClass("" + this.options.baseClass + "-" + color);
//         }
//         this.$off.addClass("" + this.options.baseClass + "-" + value);
//         this.options.offColor = value;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.onText = function(value) {
//         if (typeof value === "undefined") {
//           return this.options.onText;
//         }
//         this.$on.html(value);
//         this.options.onText = value;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.offText = function(value) {
//         if (typeof value === "undefined") {
//           return this.options.offText;
//         }
//         this.$off.html(value);
//         this.options.offText = value;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.labelText = function(value) {
//         if (typeof value === "undefined") {
//           return this.options.labelText;
//         }
//         this.$label.html(value);
//         this.options.labelText = value;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.baseClass = function(value) {
//         return this.options.baseClass;
//       };
//
//       BootstrapSwitch.prototype.wrapperClass = function(value) {
//         if (typeof value === "undefined") {
//           return this.options.wrapperClass;
//         }
//         if (!value) {
//           value = $.fn.bootstrapSwitch.defaults.wrapperClass;
//         }
//         this.$wrapper.removeClass(this._getClasses(this.options.wrapperClass).join(" "));
//         this.$wrapper.addClass(this._getClasses(value).join(" "));
//         this.options.wrapperClass = value;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.radioAllOff = function(value) {
//         if (typeof value === "undefined") {
//           return this.options.radioAllOff;
//         }
//         this.options.radioAllOff = value;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.onInit = function(value) {
//         if (typeof value === "undefined") {
//           return this.options.onInit;
//         }
//         if (!value) {
//           value = $.fn.bootstrapSwitch.defaults.onInit;
//         }
//         this.options.onInit = value;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.onSwitchChange = function(value) {
//         if (typeof value === "undefined") {
//           return this.options.onSwitchChange;
//         }
//         if (!value) {
//           value = $.fn.bootstrapSwitch.defaults.onSwitchChange;
//         }
//         this.options.onSwitchChange = value;
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype.destroy = function() {
//         var $form;
//         $form = this.$element.closest("form");
//         if ($form.length) {
//           $form.off("reset.bootstrapSwitch").removeData("bootstrap-switch");
//         }
//         this.$container.children().not(this.$element).remove();
//         this.$element.unwrap().unwrap().off(".bootstrapSwitch").removeData("bootstrap-switch");
//         return this.$element;
//       };
//
//       BootstrapSwitch.prototype._elementHandlers = function() {
//         return this.$element.on({
//           "change.bootstrapSwitch": (function(_this) {
//             return function(e, skip) {
//               var checked;
//               e.preventDefault();
//               e.stopImmediatePropagation();
//               checked = _this.$element.is(":checked");
//               if (checked === _this.options.state) {
//                 return;
//               }
//               _this.options.state = checked;
//               _this.$wrapper.removeClass(checked ? "" + _this.options.baseClass + "-off" : "" + _this.options.baseClass + "-on").addClass(checked ? "" + _this.options.baseClass + "-on" : "" + _this.options.baseClass + "-off");
//               if (!skip) {
//                 if (_this.$element.is(":radio")) {
//                   $("[name='" + (_this.$element.attr('name')) + "']").not(_this.$element).prop("checked", false).trigger("change.bootstrapSwitch", true);
//                 }
//                 return _this.$element.trigger("switchChange.bootstrapSwitch", [checked]);
//               }
//             };
//           })(this),
//           "focus.bootstrapSwitch": (function(_this) {
//             return function(e) {
//               e.preventDefault();
//               return _this.$wrapper.addClass("" + _this.options.baseClass + "-focused");
//             };
//           })(this),
//           "blur.bootstrapSwitch": (function(_this) {
//             return function(e) {
//               e.preventDefault();
//               return _this.$wrapper.removeClass("" + _this.options.baseClass + "-focused");
//             };
//           })(this),
//           "keydown.bootstrapSwitch": (function(_this) {
//             return function(e) {
//               if (!e.which || _this.options.disabled || _this.options.readonly || _this.options.indeterminate) {
//                 return;
//               }
//               switch (e.which) {
//                 case 37:
//                   e.preventDefault();
//                   e.stopImmediatePropagation();
//                   return _this.state(false);
//                 case 39:
//                   e.preventDefault();
//                   e.stopImmediatePropagation();
//                   return _this.state(true);
//               }
//             };
//           })(this)
//         });
//       };
//
//       BootstrapSwitch.prototype._handleHandlers = function() {
//         this.$on.on("click.bootstrapSwitch", (function(_this) {
//           return function(e) {
//             _this.state(false);
//             return _this.$element.trigger("focus.bootstrapSwitch");
//           };
//         })(this));
//         return this.$off.on("click.bootstrapSwitch", (function(_this) {
//           return function(e) {
//             _this.state(true);
//             return _this.$element.trigger("focus.bootstrapSwitch");
//           };
//         })(this));
//       };
//
//       BootstrapSwitch.prototype._labelHandlers = function() {
//         return this.$label.on({
//           "mousemove.bootstrapSwitch touchmove.bootstrapSwitch": (function(_this) {
//             return function(e) {
//               var left, pageX, percent, right;
//               if (!_this.isLabelDragging) {
//                 return;
//               }
//               e.preventDefault();
//               _this.isLabelDragged = true;
//               pageX = e.pageX || e.originalEvent.touches[0].pageX;
//               percent = ((pageX - _this.$wrapper.offset().left) / _this.$wrapper.width()) * 100;
//               left = 25;
//               right = 75;
//               if (_this.options.animate) {
//                 _this.$wrapper.removeClass("" + _this.options.baseClass + "-animate");
//               }
//               if (percent < left) {
//                 percent = left;
//               } else if (percent > right) {
//                 percent = right;
//               }
//               _this.$container.css("margin-left", "" + (percent - right) + "%");
//               return _this.$element.trigger("focus.bootstrapSwitch");
//             };
//           })(this),
//           "mousedown.bootstrapSwitch touchstart.bootstrapSwitch": (function(_this) {
//             return function(e) {
//               if (_this.isLabelDragging || _this.options.disabled || _this.options.readonly || _this.options.indeterminate) {
//                 return;
//               }
//               e.preventDefault();
//               _this.isLabelDragging = true;
//               return _this.$element.trigger("focus.bootstrapSwitch");
//             };
//           })(this),
//           "mouseup.bootstrapSwitch touchend.bootstrapSwitch": (function(_this) {
//             return function(e) {
//               if (!_this.isLabelDragging) {
//                 return;
//               }
//               e.preventDefault();
//               if (_this.isLabelDragged) {
//                 _this.isLabelDragged = false;
//                 _this.state(parseInt(_this.$container.css("margin-left"), 10) > -(_this.$container.width() / 6));
//                 if (_this.options.animate) {
//                   _this.$wrapper.addClass("" + _this.options.baseClass + "-animate");
//                 }
//                 _this.$container.css("margin-left", "");
//               } else {
//                 _this.state(!_this.options.state);
//               }
//               return _this.isLabelDragging = false;
//             };
//           })(this),
//           "mouseleave.bootstrapSwitch": (function(_this) {
//             return function(e) {
//               return _this.$label.trigger("mouseup.bootstrapSwitch");
//             };
//           })(this)
//         });
//       };
//
//       BootstrapSwitch.prototype._formHandler = function() {
//         var $form;
//         $form = this.$element.closest("form");
//         if ($form.data("bootstrap-switch")) {
//           return;
//         }
//         return $form.on("reset.bootstrapSwitch", function() {
//           return window.setTimeout(function() {
//             return $form.find("input").filter(function() {
//               return $(this).data("bootstrap-switch");
//             }).each(function() {
//               return $(this).bootstrapSwitch("state", this.checked);
//             });
//           }, 1);
//         }).data("bootstrap-switch", true);
//       };
//
//       BootstrapSwitch.prototype._getClasses = function(classes) {
//         var c, cls, _i, _len;
//         if (!$.isArray(classes)) {
//           return ["" + this.options.baseClass + "-" + classes];
//         }
//         cls = [];
//         for (_i = 0, _len = classes.length; _i < _len; _i++) {
//           c = classes[_i];
//           cls.push("" + this.options.baseClass + "-" + c);
//         }
//         return cls;
//       };
//
//       return BootstrapSwitch;
//
//     })();
//     $.fn.bootstrapSwitch = function() {
//       var args, option, ret;
//       option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
//       ret = this;
//       this.each(function() {
//         var $this, data;
//         $this = $(this);
//         data = $this.data("bootstrap-switch");
//         if (!data) {
//           $this.data("bootstrap-switch", data = new BootstrapSwitch(this, option));
//         }
//         if (typeof option === "string") {
//           return ret = data[option].apply(data, args);
//         }
//       });
//       return ret;
//     };
//     $.fn.bootstrapSwitch.Constructor = BootstrapSwitch;
//     return $.fn.bootstrapSwitch.defaults = {
//       state: true,
//       size: null,
//       animate: true,
//       disabled: false,
//       readonly: false,
//       indeterminate: false,
//       onColor: "primary",
//       offColor: "default",
//       onText: "ON",
//       offText: "OFF",
//       labelText: "&nbsp;",
//       baseClass: "bootstrap-switch",
//       wrapperClass: "wrapper",
//       radioAllOff: false,
//       onInit: function() {},
//       onSwitchChange: function() {}
//     };
//   })(window.jQuery, window);
//
// }).call(this);

/* ========================================================================
 * Bootstrap: button.js v3.2.0
 * http://getbootstrap.com/javascript/#buttons
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

(function ($) {
  'use strict';

  // BUTTON PUBLIC CLASS DEFINITION
  // ==============================

  var Button = function (element, options) {
    this.$element  = $(element)
    this.options   = $.extend({}, Button.DEFAULTS, options)
    this.isLoading = false
  }

  Button.VERSION  = '3.2.0'

  Button.DEFAULTS = {
    loadingText: 'loading...'
  }

  Button.prototype.setState = function (state) {
    var d    = 'disabled'
    var $el  = this.$element
    var val  = $el.is('input') ? 'val' : 'html'
    var data = $el.data()

    state = state + 'Text'

    if (data.resetText == null) $el.data('resetText', $el[val]())

    $el[val](data[state] == null ? this.options[state] : data[state])

    // push to event loop to allow forms to submit
    setTimeout($.proxy(function () {
      if (state == 'loadingText') {
        this.isLoading = true
        $el.addClass(d).attr(d, d)
      } else if (this.isLoading) {
        this.isLoading = false
        $el.removeClass(d).removeAttr(d)
      }
    }, this), 0)
  }

  Button.prototype.toggle = function () {
    var changed = true
    var $parent = this.$element.closest('[data-toggle="buttons"]')

    if ($parent.length) {
      var $input = this.$element.find('input')
      if ($input.prop('type') == 'radio') {
        if ($input.prop('checked') && this.$element.hasClass('active')) changed = false
        else $parent.find('.active').removeClass('active')
      }
      if (changed) $input.prop('checked', !this.$element.hasClass('active')).trigger('change')
    }

    if (changed) this.$element.toggleClass('active')
  }


  // BUTTON PLUGIN DEFINITION
  // ========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.button')
      var options = typeof option == 'object' && option

      if (!data) $this.data('bs.button', (data = new Button(this, options)))

      if (option == 'toggle') data.toggle()
      else if (option) data.setState(option)
    })
  }

  var old = $.fn.button

  $.fn.button             = Plugin
  $.fn.button.Constructor = Button


  // BUTTON NO CONFLICT
  // ==================

  $.fn.button.noConflict = function () {
    $.fn.button = old
    return this
  }


  // BUTTON DATA-API
  // ===============

  $(document).on('click.bs.button.data-api', '[data-toggle^="button"]', function (e) {
    var $btn = $(e.target)
    if (!$btn.hasClass('btn')) $btn = $btn.closest('.btn')
    Plugin.call($btn, 'toggle')
    e.preventDefault()
  })

})(jQuery);

/* ========================================================================
 * Bootstrap: dropdown.js v3.2.0
 * http://getbootstrap.com/javascript/#dropdowns
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

(function ($) {
  'use strict';

  // DROPDOWN CLASS DEFINITION
  // =========================

  var backdrop = '.dropdown-backdrop'
  var toggle   = '[data-toggle="dropdown"]'
  var Dropdown = function (element) {
    $(element).on('click.bs.dropdown', this.toggle)
  }

  Dropdown.VERSION = '3.2.0'

  Dropdown.prototype.toggle = function (e) {
    var $this = $(this)

    if ($this.is('.disabled, :disabled')) return

    var $parent  = getParent($this)
    var isActive = $parent.hasClass('open')

    clearMenus()

    if (!isActive) {
      if ('ontouchstart' in document.documentElement && !$parent.closest('.navbar-nav').length) {
        // if mobile we use a backdrop because click events don't delegate
        $('<div class="dropdown-backdrop"/>').insertAfter($(this)).on('click', clearMenus)
      }

      var relatedTarget = { relatedTarget: this }
      $parent.trigger(e = $.Event('show.bs.dropdown', relatedTarget))

      if (e.isDefaultPrevented()) return

      $this.trigger('focus')

      $parent
        .toggleClass('open')
        .trigger('shown.bs.dropdown', relatedTarget)
    }

    return false
  }

  Dropdown.prototype.keydown = function (e) {
    if (!/(38|40|27)/.test(e.keyCode)) return

    var $this = $(this)

    e.preventDefault()
    e.stopPropagation()

    if ($this.is('.disabled, :disabled')) return

    var $parent  = getParent($this)
    var isActive = $parent.hasClass('open')

    if (!isActive || (isActive && e.keyCode == 27)) {
      if (e.which == 27) $parent.find(toggle).trigger('focus')
      return $this.trigger('click')
    }

    var desc = ' li:not(.divider):visible a'
    var $items = $parent.find('[role="menu"]' + desc + ', [role="listbox"]' + desc)

    if (!$items.length) return

    var index = $items.index($items.filter(':focus'))

    if (e.keyCode == 38 && index > 0)                 index--                        // up
    if (e.keyCode == 40 && index < $items.length - 1) index++                        // down
    if (!~index)                                      index = 0

    $items.eq(index).trigger('focus')
  }

  function clearMenus(e) {
    if (e && e.which === 3) return
    $(backdrop).remove()
    $(toggle).each(function () {
      var $parent = getParent($(this))
      var relatedTarget = { relatedTarget: this }
      if (!$parent.hasClass('open')) return
      $parent.trigger(e = $.Event('hide.bs.dropdown', relatedTarget))
      if (e.isDefaultPrevented()) return
      $parent.removeClass('open').trigger('hidden.bs.dropdown', relatedTarget)
    })
  }

  function getParent($this) {
    var selector = $this.attr('data-target')

    if (!selector) {
      selector = $this.attr('href')
      selector = selector && /#[A-Za-z]/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
    }

    var $parent = selector && $(selector)

    return $parent && $parent.length ? $parent : $this.parent()
  }


  // DROPDOWN PLUGIN DEFINITION
  // ==========================

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var data  = $this.data('bs.dropdown')

      if (!data) $this.data('bs.dropdown', (data = new Dropdown(this)))
      if (typeof option == 'string') data[option].call($this)
    })
  }

  var old = $.fn.dropdown

  $.fn.dropdown             = Plugin
  $.fn.dropdown.Constructor = Dropdown


  // DROPDOWN NO CONFLICT
  // ====================

  $.fn.dropdown.noConflict = function () {
    $.fn.dropdown = old
    return this
  }


  // APPLY TO STANDARD DROPDOWN ELEMENTS
  // ===================================

  $(document)
    .on('click.bs.dropdown.data-api', clearMenus)
    .on('click.bs.dropdown.data-api', '.dropdown form', function (e) { e.stopPropagation() })
    .on('click.bs.dropdown.data-api', toggle, Dropdown.prototype.toggle)
    .on('keydown.bs.dropdown.data-api', toggle + ', [role="menu"], [role="listbox"]', Dropdown.prototype.keydown)

})(jQuery);

/* ========================================================================
 * Bootstrap: popover.js v3.2.0
 * http://getbootstrap.com/javascript/#popovers
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

(function ($) {
  'use strict';

  // POPOVER PUBLIC CLASS DEFINITION
  // ===============================

  var Popover = function (element, options) {
    this.init('popover', element, options)
  }

  if (!$.fn.tooltip) throw new Error('Popover requires tooltip.js')

  Popover.VERSION  = '3.2.0'

  Popover.DEFAULTS = $.extend({}, $.fn.tooltip.Constructor.DEFAULTS, {
    placement: 'right',
    trigger: 'click',
    content: '',
    template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
  })


  // NOTE: POPOVER EXTENDS tooltip.js
  // ================================

  Popover.prototype = $.extend({}, $.fn.tooltip.Constructor.prototype)

  Popover.prototype.constructor = Popover

  Popover.prototype.getDefaults = function () {
    return Popover.DEFAULTS
  }

  Popover.prototype.setContent = function () {
    var $tip    = this.tip()
    var title   = this.getTitle()
    var content = this.getContent()

    $tip.find('.popover-title')[this.options.html ? 'html' : 'text'](title)
    $tip.find('.popover-content').empty()[ // we use append for html objects to maintain js events
      this.options.html ? (typeof content == 'string' ? 'html' : 'append') : 'text'
      ](content)

    $tip.removeClass('fade top bottom left right in')

    // IE8 doesn't accept hiding via the `:empty` pseudo selector, we have to do
    // this manually by checking the contents.
    if (!$tip.find('.popover-title').html()) $tip.find('.popover-title').hide()
  }

  Popover.prototype.hasContent = function () {
    return this.getTitle() || this.getContent()
  }

  Popover.prototype.getContent = function () {
    var $e = this.$element
    var o  = this.options

    return $e.attr('data-content')
      || (typeof o.content == 'function' ?
        o.content.call($e[0]) :
        o.content)
  }

  Popover.prototype.arrow = function () {
    return (this.$arrow = this.$arrow || this.tip().find('.arrow'))
  }

  Popover.prototype.tip = function () {
    if (!this.$tip) this.$tip = $(this.options.template)
    return this.$tip
  }


  // POPOVER PLUGIN DEFINITION
  // =========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.popover')
      var options = typeof option == 'object' && option

      if (!data && option == 'destroy') return
      if (!data) $this.data('bs.popover', (data = new Popover(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.popover

  $.fn.popover             = Plugin
  $.fn.popover.Constructor = Popover


  // POPOVER NO CONFLICT
  // ===================

  $.fn.popover.noConflict = function () {
    $.fn.popover = old
    return this
  }

})(jQuery);

/* ========================================================================
 * Bootstrap: modal.js v3.3.7
 * http://getbootstrap.com/javascript/#modals
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

+function ($) {
    'use strict';
    
    // MODAL CLASS DEFINITION
    // ======================
    
    var Modal = function (element, options) {
        this.options             = options
        this.$body               = $(document.body)
        this.$element            = $(element)
        this.$dialog             = this.$element.find('.modal-dialog')
        this.$backdrop           = null
        this.isShown             = null
        this.originalBodyPad     = null
        this.scrollbarWidth      = 0
        this.ignoreBackdropClick = false
        
        if (this.options.remote) {
            this.$element
                .find('.modal-content')
                .load(this.options.remote, $.proxy(function () {
                    this.$element.trigger('loaded.bs.modal')
                }, this))
        }
    }
    
    Modal.VERSION  = '3.3.7'
    
    Modal.TRANSITION_DURATION = 300
    Modal.BACKDROP_TRANSITION_DURATION = 150
    
    Modal.DEFAULTS = {
        backdrop: true,
        keyboard: true,
        show: true
    }
    
    Modal.prototype.toggle = function (_relatedTarget) {
        return this.isShown ? this.hide() : this.show(_relatedTarget)
    }
    
    Modal.prototype.show = function (_relatedTarget) {
        var that = this
        var e    = $.Event('show.bs.modal', { relatedTarget: _relatedTarget })
        
        this.$element.trigger(e)
        
        if (this.isShown || e.isDefaultPrevented()) return
        
        this.isShown = true
        
        this.checkScrollbar()
        this.setScrollbar()
        this.$body.addClass('modal-open')
        
        this.escape()
        this.resize()
        
        this.$element.on('click.dismiss.bs.modal', '[data-dismiss="modal"]', $.proxy(this.hide, this))
        
        this.$dialog.on('mousedown.dismiss.bs.modal', function () {
            that.$element.one('mouseup.dismiss.bs.modal', function (e) {
                if ($(e.target).is(that.$element)) that.ignoreBackdropClick = true
            })
        })
        
        this.backdrop(function () {
            var transition = $.support.transition && that.$element.hasClass('fade')
            
            if (!that.$element.parent().length) {
                that.$element.appendTo(that.$body) // don't move modals dom position
            }
            
            that.$element
                .show()
                .scrollTop(0)
            
            that.adjustDialog()
            
            if (transition) {
                that.$element[0].offsetWidth // force reflow
            }
            
            that.$element.addClass('in')
            
            that.enforceFocus()
            
            var e = $.Event('shown.bs.modal', { relatedTarget: _relatedTarget })
            
            transition ?
                that.$dialog // wait for modal to slide in
                    .one('bsTransitionEnd', function () {
                        that.$element.trigger('focus').trigger(e)
                    })
                    .emulateTransitionEnd(Modal.TRANSITION_DURATION) :
                that.$element.trigger('focus').trigger(e)
        })
    }
    
    Modal.prototype.hide = function (e) {
        if (e) e.preventDefault()
        
        e = $.Event('hide.bs.modal')
        
        this.$element.trigger(e)
        
        if (!this.isShown || e.isDefaultPrevented()) return
        
        this.isShown = false
        
        this.escape()
        this.resize()
        
        $(document).off('focusin.bs.modal')
        
        this.$element
            .removeClass('in')
            .off('click.dismiss.bs.modal')
            .off('mouseup.dismiss.bs.modal')
        
        this.$dialog.off('mousedown.dismiss.bs.modal')
        
        $.support.transition && this.$element.hasClass('fade') ?
            this.$element
                .one('bsTransitionEnd', $.proxy(this.hideModal, this))
                .emulateTransitionEnd(Modal.TRANSITION_DURATION) :
            this.hideModal()
    }
    
    Modal.prototype.enforceFocus = function () {
        $(document)
            .off('focusin.bs.modal') // guard against infinite focus loop
            .on('focusin.bs.modal', $.proxy(function (e) {
                if (document !== e.target &&
                    this.$element[0] !== e.target &&
                    !this.$element.has(e.target).length) {
                    this.$element.trigger('focus')
                }
            }, this))
    }
    
    Modal.prototype.escape = function () {
        if (this.isShown && this.options.keyboard) {
            this.$element.on('keydown.dismiss.bs.modal', $.proxy(function (e) {
                e.which == 27 && this.hide()
            }, this))
        } else if (!this.isShown) {
            this.$element.off('keydown.dismiss.bs.modal')
        }
    }
    
    Modal.prototype.resize = function () {
        if (this.isShown) {
            $(window).on('resize.bs.modal', $.proxy(this.handleUpdate, this))
        } else {
            $(window).off('resize.bs.modal')
        }
    }
    
    Modal.prototype.hideModal = function () {
        var that = this
        this.$element.hide()
        this.backdrop(function () {
            that.$body.removeClass('modal-open')
            that.resetAdjustments()
            that.resetScrollbar()
            that.$element.trigger('hidden.bs.modal')
        })
    }
    
    Modal.prototype.removeBackdrop = function () {
        this.$backdrop && this.$backdrop.remove()
        this.$backdrop = null
    }
    
    Modal.prototype.backdrop = function (callback) {
        var that = this
        var animate = this.$element.hasClass('fade') ? 'fade' : ''
        
        if (this.isShown && this.options.backdrop) {
            var doAnimate = $.support.transition && animate
            
            this.$backdrop = $(document.createElement('div'))
                .addClass('modal-backdrop ' + animate)
                .appendTo(this.$body)
            
            this.$element.on('click.dismiss.bs.modal', $.proxy(function (e) {
                if (this.ignoreBackdropClick) {
                    this.ignoreBackdropClick = false
                    return
                }
                if (e.target !== e.currentTarget) return
                this.options.backdrop == 'static'
                    ? this.$element[0].focus()
                    : this.hide()
            }, this))
            
            if (doAnimate) this.$backdrop[0].offsetWidth // force reflow
            
            this.$backdrop.addClass('in')
            
            if (!callback) return
            
            doAnimate ?
                this.$backdrop
                    .one('bsTransitionEnd', callback)
                    .emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION) :
                callback()
            
        } else if (!this.isShown && this.$backdrop) {
            this.$backdrop.removeClass('in')
            
            var callbackRemove = function () {
                that.removeBackdrop()
                callback && callback()
            }
            $.support.transition && this.$element.hasClass('fade') ?
                this.$backdrop
                    .one('bsTransitionEnd', callbackRemove)
                    .emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION) :
                callbackRemove()
            
        } else if (callback) {
            callback()
        }
    }
    
    // these following methods are used to handle overflowing modals
    
    Modal.prototype.handleUpdate = function () {
        this.adjustDialog()
    }
    
    Modal.prototype.adjustDialog = function () {
        var modalIsOverflowing = this.$element[0].scrollHeight > document.documentElement.clientHeight
        
        this.$element.css({
            paddingLeft:  !this.bodyIsOverflowing && modalIsOverflowing ? this.scrollbarWidth : '',
            paddingRight: this.bodyIsOverflowing && !modalIsOverflowing ? this.scrollbarWidth : ''
        })
    }
    
    Modal.prototype.resetAdjustments = function () {
        this.$element.css({
            paddingLeft: '',
            paddingRight: ''
        })
    }
    
    Modal.prototype.checkScrollbar = function () {
        var fullWindowWidth = window.innerWidth
        if (!fullWindowWidth) { // workaround for missing window.innerWidth in IE8
            var documentElementRect = document.documentElement.getBoundingClientRect()
            fullWindowWidth = documentElementRect.right - Math.abs(documentElementRect.left)
        }
        this.bodyIsOverflowing = document.body.clientWidth < fullWindowWidth
        this.scrollbarWidth = this.measureScrollbar()
    }
    
    Modal.prototype.setScrollbar = function () {
        var bodyPad = parseInt((this.$body.css('padding-right') || 0), 10)
        this.originalBodyPad = document.body.style.paddingRight || ''
        if (this.bodyIsOverflowing) this.$body.css('padding-right', bodyPad + this.scrollbarWidth)
    }
    
    Modal.prototype.resetScrollbar = function () {
        this.$body.css('padding-right', this.originalBodyPad)
    }
    
    Modal.prototype.measureScrollbar = function () { // thx walsh
        var scrollDiv = document.createElement('div')
        scrollDiv.className = 'modal-scrollbar-measure'
        this.$body.append(scrollDiv)
        var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth
        this.$body[0].removeChild(scrollDiv)
        return scrollbarWidth
    }
    
    
    // MODAL PLUGIN DEFINITION
    // =======================
    
    function Plugin(option, _relatedTarget) {
        return this.each(function () {
            var $this   = $(this)
            var data    = $this.data('bs.modal')
            var options = $.extend({}, Modal.DEFAULTS, $this.data(), typeof option == 'object' && option)
            
            if (!data) $this.data('bs.modal', (data = new Modal(this, options)))
            if (typeof option == 'string') data[option](_relatedTarget)
            else if (options.show) data.show(_relatedTarget)
        })
    }
    
    var old = $.fn.modal
    
    $.fn.modal             = Plugin
    $.fn.modal.Constructor = Modal
    
    
    // MODAL NO CONFLICT
    // =================
    
    $.fn.modal.noConflict = function () {
        $.fn.modal = old
        return this
    }
    
    
    // MODAL DATA-API
    // ==============
    
    $(document).on('click.bs.modal.data-api', '[data-toggle="modal"]', function (e) {
        var $this   = $(this)
        var href    = $this.attr('href')
        var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))) // strip for ie7
        var option  = $target.data('bs.modal') ? 'toggle' : $.extend({ remote: !/#/.test(href) && href }, $target.data(), $this.data())
        
        if ($this.is('a')) e.preventDefault()
        
        $target.one('show.bs.modal', function (showEvent) {
            if (showEvent.isDefaultPrevented()) return // only register focus restorer if modal will actually get shown
            $target.one('hidden.bs.modal', function () {
                $this.is(':visible') && $this.trigger('focus')
            })
        })
        Plugin.call($target, option, this)
    })
    
}(jQuery);

/* ========================================================================
 * Bootstrap: tab.js v3.2.0
 * http://getbootstrap.com/javascript/#tabs
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

// (function ($) {
//   'use strict';
//
//   // TAB CLASS DEFINITION
//   // ====================
//
//   var Tab = function (element) {
//     this.element = $(element)
//   }
//
//   Tab.VERSION = '3.2.0'
//
//   Tab.prototype.show = function () {
//     var $this    = this.element
//     var $ul      = $this.closest('ul:not(.dropdown-menu)')
//     var selector = $this.data('target')
//
//     if (!selector) {
//       selector = $this.attr('href')
//       selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
//     }
//
//     if ($this.parent('li').hasClass('active')) return
//
//     var previous = $ul.find('.active:last a')[0]
//     var e        = $.Event('show.bs.tab', {
//       relatedTarget: previous
//     })
//
//     $this.trigger(e)
//
//     if (e.isDefaultPrevented()) return
//
//     var $target = $(selector)
//
//     this.activate($this.closest('li'), $ul)
//     this.activate($target, $target.parent(), function () {
//       $this.trigger({
//         type: 'shown.bs.tab',
//         relatedTarget: previous
//       })
//     })
//   }
//
//   Tab.prototype.activate = function (element, container, callback) {
//     var $active    = container.find('> .active')
//     var transition = callback
//       && $.support.transition
//       && $active.hasClass('fade')
//
//     function next() {
//       $active
//         .removeClass('active')
//         .find('> .dropdown-menu > .active')
//         .removeClass('active')
//
//       element.addClass('active')
//
//       if (transition) {
//         element[0].offsetWidth // reflow for transition
//         element.addClass('in')
//       } else {
//         element.removeClass('fade')
//       }
//
//       if (element.parent('.dropdown-menu')) {
//         element.closest('li.dropdown').addClass('active')
//       }
//
//       callback && callback()
//     }
//
//     transition ?
//       $active
//         .one('bsTransitionEnd', next)
//         .emulateTransitionEnd(150) :
//       next()
//
//     $active.removeClass('in')
//   }
//
//
//   // TAB PLUGIN DEFINITION
//   // =====================
//
//   function Plugin(option) {
//     return this.each(function () {
//       var $this = $(this)
//       var data  = $this.data('bs.tab')
//
//       if (!data) $this.data('bs.tab', (data = new Tab(this)))
//       if (typeof option == 'string') data[option]()
//     })
//   }
//
//   var old = $.fn.tab
//
//   $.fn.tab             = Plugin
//   $.fn.tab.Constructor = Tab
//
//
//   // TAB NO CONFLICT
//   // ===============
//
//   $.fn.tab.noConflict = function () {
//     $.fn.tab = old
//     return this
//   }
//
//
//   // TAB DATA-API
//   // ============
//
//   $(document).on('click.bs.tab.data-api', '[data-toggle="tab"], [data-toggle="pill"]', function (e) {
//     e.preventDefault()
//     Plugin.call($(this), 'show')
//   })
//
// })(jQuery);

/*
 *  input focus
 */

(function (global, $) {
  // Focus state for append/prepend inputs
  $('.input-group').on('focus', '.form-control', function () {
    $(this).closest('.input-group, .form-group').addClass('focus');
  }).on('blur', '.form-control', function () {
    $(this).closest('.input-group, .form-group').removeClass('focus');
  });
})(this, jQuery);

/*
 * Tooltips init
 */
// Tooltips
jQuery(function ($) {
  $('[data-toggle="tooltip"]').tooltip();
}.call(this, jQuery));

/*
 * Checkbox init
 */
jQuery(function ($) {
  $('[data-toggle="checkbox"]').radiocheck();
  $('[data-toggle="radio"]').radiocheck();
}.call(this, jQuery));

/*
 * Switches init
 */
// Switches
// jQuery(function ($) {
//   $('[data-toggle="switch"]').bootstrapSwitch();
// }.call(this, jQuery));

/*
 * Popover init
 */
jQuery(function ($) {
  $('[data-toggle="popover"]').popover();
}.call(this, jQuery));

/*
 * Pagination init
 */
jQuery(function ($) {
  $('.pagination').on('click', 'a', function () {
    $(this).parent().siblings('li').removeClass('active').end().addClass('active');
  });
}.call(this, jQuery));

/*
 * Button group init
 */
jQuery(function ($) {
  $('.btn-group').on('click', 'a', function () {
    $(this).siblings().removeClass('active').end().addClass('active');
  });
}.call(this, jQuery));
