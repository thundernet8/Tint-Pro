/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/10 16:39
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

var jQuery = require('jquery');
var swal = require('./sweet-alert');

/**
 * Msgbox Wrap
 */
var app = window.App || (window.App = {});
var msgbox = app.Msgbox || (app.Msgbox = {});

// Basic
msgbox.basic = function (options) {
  options.customClass = 'swal-basic';
  options.type = '';
  options.confirmButtonColor = '#1abc9c';
  options.confirmButtonClass = 'btn-primary';
  swal(options);
};

// Alert/Warning wrap
msgbox.alert = msgbox.warning = function (options, callback) {
  options.customClass = 'swal-alert';
  options.type = 'warning';
  options.confirmButtonColor = '#3498db';
  options.confirmButtonClass = 'btn-info';
  swal(options, callback);
};

// Error wrap
msgbox.error = function (options, callback) {
  options.customClass = 'swal-error';
  options.type = 'error';
  options.confirmButtonColor = '#e74c3c';
  options.confirmButtonClass = 'btn-danger';
  swal(options, callback);
};

// Success wrap
msgbox.success = function (options, callback) {
  options.customClass = 'swal-success';
  options.type = 'success';
  options.confirmButtonColor = '#2ecc71';
  options.confirmButtonClass = 'btn-success';
  swal(options, callback);
};

// Info wrap
msgbox.info = function (options, callback) {
  options.customClass = 'swal-info';
  options.type = 'info';
  options.confirmButtonColor = '#3498db';
  options.confirmButtonClass = 'btn-info';
  swal(options, callback);
};

// Input wrap
msgbox.input = function (options, callback) {
  options.customClass = 'swal-input';
  options.type = 'input';
  options.confirmButtonColor = '#34495e';
  options.confirmButtonClass = 'btn-inverse';
  options.animation = options.animation ? options.animation : 'slide-from-top';
  swal(options, callback);
};

app.Msgbox = msgbox;
window.App = app;

// Event
(function ($, Msgbox) {

  // Trigger
  $(document).on('click.tt.msgbox', '[data-toggle="msgbox"]', function (e) {
    var $this = $(this);
    var title = $this.attr('title');
    var text = $this.data('content');
    var type = $this.data('msgtype') ? $this.data('msgtype') : 'info';
    var animation = $this.data('animation') ? $this.data('animation') : 'pop';
    Msgbox[type]({
      title: title,
      text: text,
      type: type,
      animation: animation,
      confirmButtonText: 'OK',
      showCancelButton: true
    });
  });
})(jQuery, msgbox);
