/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/11 20:59
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';


import {Routes} from './globalConfig';
import Utils from './utils';
import {popMsgbox} from './msgbox'

var _body = $('body');

var _spinner = 'tico tico-spinner9 spinning';

var _actBtnSel = '.post-act';

var _submitting = false;

var _handlePostsManagement = function (btn) {
    if(_submitting || !Utils.checkLogin()) return false;
    
    var action = btn.data('act');
    if(action.length < 1) {
        return false;
    }
    
    var data = {
        onlyStatus: true,
        action: action
    };
    
    var postId = parseInt(btn.data('post-id'));
    
    var url = postId < 1 ? Routes.posts : Routes.posts + '/' + postId;
    
    var beforeSend = function () {
        if(_submitting) return;
        Utils.showFullLoader(_spinner, '正在操作中...');
        _submitting = true;
    };
    
    var finishRequest = function () {
        if(!_submitting) return;
        Utils.hideFullLoader();
        _submitting = false;
    };
    
    var success = function (data, textStatus, xhr) {
        finishRequest();
        if(data.success && data.success == 1) {
            popMsgbox.success({
                title: data.message,
                timer: 2000,
                showConfirmButton: true
            }, function () {
                location.replace(location.href);
            });
        }else{
            popMsgbox.error({
                title: data.message,
                timer: 2000,
                showConfirmButton: true
            });
        }
    };
    var error = function (xhr, textStatus, err) {
        finishRequest();
        popMsgbox.error({
            title: xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText,
            timer: 2000,
            showConfirmButton: true
        });
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


//
var _init = function () {
    _body.on('click', _actBtnSel, function () {
        _handlePostsManagement($(this));
    });
};

var ManagePosts = {
    init: _init
};

export default ManagePosts;