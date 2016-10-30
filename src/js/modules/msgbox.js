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

var swal = require('./../vender/sweet-alert');

/**
 * 弹出式消息框
 */
var app = window.App || (window.App = {});
var popMsgbox = app.PopMsgbox || (app.PopMsgbox = {});
var popMsgbox = {};

// Basic
popMsgbox.basic = function (options) {
  options.customClass = 'swal-basic';
  options.type = '';
  options.confirmButtonColor = '#1abc9c';
  options.confirmButtonClass = 'btn-primary';
  swal(options);
};

// Alert/Warning wrap
popMsgbox.alert = popMsgbox.warning = function (options, callback) {
  options.customClass = 'swal-alert';
  options.type = 'warning';
  options.confirmButtonColor = '#3498db';
  options.confirmButtonClass = 'btn-info';
  swal(options, callback);
};

// Error wrap
popMsgbox.error = function (options, callback) {
  options.customClass = 'swal-error';
  options.type = 'error';
  options.confirmButtonColor = '#e74c3c';
  options.confirmButtonClass = 'btn-danger';
  swal(options, callback);
};

// Success wrap
popMsgbox.success = function (options, callback) {
  options.customClass = 'swal-success';
  options.type = 'success';
  options.confirmButtonColor = '#2ecc71';
  options.confirmButtonClass = 'btn-success';
  swal(options, callback);
};

// Info wrap
popMsgbox.info = function (options, callback) {
  options.customClass = 'swal-info';
  options.type = 'info';
  options.confirmButtonColor = '#3498db';
  options.confirmButtonClass = 'btn-info';
  swal(options, callback);
};

// Input wrap
popMsgbox.input = function (options, callback) {
  options.customClass = 'swal-input';
  options.type = 'input';
  options.confirmButtonColor = '#34495e';
  options.confirmButtonClass = 'btn-inverse';
  options.animation = options.animation ? options.animation : 'slide-from-top';
  swal(options, callback);
};

// 绑定事件
popMsgbox.init = function () {
  jQuery(document).on('click.tt.popMsgbox.show', '[data-toggle="msgbox"]', function (e) {
    var $this = $(this);
    var title = $this.attr('title');
    var text = $this.data('content');
    var type = $this.data('msgtype') ? $this.data('msgtype') : 'info';
    var animation = $this.data('animation') ? $this.data('animation') : 'pop';
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

app.PopMsgbox = popMsgbox;
window.App = app;


/**
 * 页内消息提示(如注册框上提醒消息)
 */
var msgbox = {};
msgbox.show = function(str, type, beforeSel){
  var $msg = $('.msg'),
      tpl = '<button type="button" class="btn-close">×</button><ul><li></li></ul>';

  var $txt = $(tpl);

  if($msg.length === 0){
    $msg = $('<div class="msg"></div>');
    beforeSel.before($msg);
  }else{
    $msg.find('li').remove();
  }
  $txt.find('li').text(str);
  $msg.append($txt).addClass(type).show();
};

// 初始化绑定事件
msgbox.init = function () {
  $('body').on('click.tt.msgbox.close', '.msg > .btn-close', function(){
    var $this = $(this),
        $msgbox = $this.parent();
    $msgbox.slideUp(function(){
      $msgbox.remove();
    });
  });
};

export {popMsgbox, msgbox};