/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/11 14:08
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

var jQuery = require('jquery');

jQuery(function ($) {
  var body = $('body');
  if(body.hasClass('is-loadingApp')) {
    setTimeout(function () {
      body.removeClass('is-loadingApp');
    }, 2000);
  }
}.call(this, jQuery));
