/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/30 18:52
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

import Utils from './utils';
import {Routes} from './globalConfig';
import {popMsgbox} from './msgbox';

var _btnSel = '.follow-btn';
var _followAct = 'follow';
var _unfollowAct = 'unfollow';
var _spinnerClass = 'tico tico-spinner2 spinning';
var _unfollowedIconClass = 'tico tico-user-plus';
var _unfollowedText = '关注';
var _followedIconClass = 'tico tico-user-check';
var _followedText = '已关注';
var _followEachIconClass = 'tico tico-exchange';
var _followEachText = '互相关注';
var _originIconClass = '';
var _body = $('body');


var _followActing = false; // 正在执行follow/unfollow操作

var _markFollowed = function (btn, followEach = false) {
    btn.removeClass('unfollowed').addClass('followed').data('act', _unfollowAct).attr('title', ''); // data操作不会反应在dom元素属性字符上
    var icon = btn.children('i');
    if(followEach) {
        icon.attr('class', _followEachIconClass);
        btn.children('span').text(_followEachText);
    }else{
        icon.attr('class', _followedIconClass);
        btn.children('span').text(_followedText);
    }
};

var _markUnfollowed = function (btn) {
    btn.removeClass('followed').addClass('unfollowed').data('act', _followAct).attr('title', '');
    var icon = btn.children('i');
    icon.attr('class', _unfollowedIconClass);
    btn.children('span').text(_unfollowedText);
};

var _restoreIcon = function (btn) {
    btn.children('i').attr('class', _originIconClass);
};

var _doFollow = function (btn) {
    if(_followActing || !(btn.data('uid')) || !Utils.checkLogin()) return false;
    
    var followedUid = parseInt(btn.data('uid'));
    var action = btn.data('act') == _unfollowAct ? _unfollowAct : _followAct;
    var url = Routes.follower.replace('{{uid}}', followedUid);
    var data = {
        action : action
    };
    
    var beforeSend = function () {
        // return false; // Note: return false 会导致AJAX请求取消, return 空或true则继续请求
        if(_followActing) return false;
        _followActing = true;
        var icon = btn.children('i');
        _originIconClass = icon.attr('class');
        icon.attr('class', _spinnerClass);
    };
    var finishRequest = function () {
        if(!_followActing) return;
        _followActing = false;
    };
    var success = function (data, textStatus, xhr) {
        if(data.success && data.success == 1) {
            if(action == _unfollowAct) {
                _markUnfollowed(btn);
            }else{
                _markFollowed(btn, data.hasOwnProperty('followEach') && data['followEach']);
            }
            popMsgbox.success({
                title: data.message,
                timer: 2000,
                showConfirmButton: true
            });
        }else{
            popMsgbox.error({
                title: data.message,
                timer: 2000,
                showConfirmButton: true
            });
            _restoreIcon(btn);
        }
        finishRequest();
    };
    var error = function (xhr, textStatus, err) {
        popMsgbox.error({
            title: xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText,
            timer: 2000,
            showConfirmButton: true
        });
        _restoreIcon(btn);
        finishRequest();
    };
    $.post({
        url: url,
        data: Utils.filterDataForRest(data),
        dataType: 'json',
        beforeSend: beforeSend,
        success: success,
        error: error
    });
};


/* 导出模块 */
/* ---------------------- */

var FollowKit = {
    init: function () {
        _body.on('click', _btnSel, function () {
            var $this = $(this);
    
            _doFollow($this);
        });
    }
};

export default FollowKit;