/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/15 16:39
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

var _submitBtnSel = '.delete-member';

var _submitting = false;


var _handleDeleteMember = function (btn) {
    if(_submitting || !Utils.checkLogin()) return false;
    
    var data = {};
    
    var url = Routes.members + '/' + btn.data('member-id');
    
    var beforeSend = function () {
        if(_submitting) return;
        btn.prop('disabled', true);
        Utils.showFullLoader(_spinner, '正在请求中...');
        _submitting = true;
    };
    
    var finishRequest = function () {
        if(!_submitting) return;
        Utils.hideFullLoader();
        btn.prop('disabled', false);
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
                $('#mid-' + btn.data('member-id')).remove();
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
        url: url + '?' + $.param(Utils.filterDataForRest(data)),
        type: 'DELETE',
        dataType: 'json',
        beforeSend: beforeSend,
        success: success,
        error: error
    });
};


//
var _init = function () {
    _body.on('click', _submitBtnSel, function () {
        _handleDeleteMember($(this));
    });
};

var DeleteMember = {
    init: _init
};

export default DeleteMember;