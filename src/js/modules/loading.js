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
 * @link https://webapproach.net/tint.html
 */

'use strict';

// jQuery(function ($) {
//   var body = $('body');
//   if(body.hasClass('is-loadingApp')) {
//     setTimeout(function () {
//       body.removeClass('is-loadingApp');
//     }, 2000);
//   }
// }.call(this, jQuery));


// 顶部加载条
var handleLineLoading = function () {
  var body = $('body');
  if(body.hasClass('is-loadingApp')) {
    setTimeout(function () {
      body.removeClass('is-loadingApp');
    }, 2000);
  }
};

var handleSpinLoading = function () {
  console.log('10000');
};

export {handleLineLoading, handleSpinLoading};