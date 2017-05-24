/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/02/09 15:16
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';


import {Routes} from './globalConfig';
import Utils from './utils';
import {popMsgbox} from './msgbox'

var _body = $('body');

var _btnOriginText = '';
var _spinner = '<i class="tico tico-spinner9 spinning"></i>';

var _addBtnSel = '#add-credits';

var _creditsNumInputSel = 'input[name="credits-num"]';

var _submitting = false;

var _error = function (title) {
    popMsgbox.error({
        title: title,
        timer: 2000,
        showConfirmButton: true
    });
    return false;
};

var _handleAddCredits = function (btn) {
    if(_submitting || !Utils.checkLogin()) return false;
    
    var creditsNumInput = $(_creditsNumInputSel);
    if(creditsNumInput.length < 1) {
        return false;
    }
    
    var num = creditsNumInput.val();
    if(num < 1) {
        return _error('积分数量必须大于0');
    }
    
    var data = {
        uid: btn.data('uid'),
        num: parseInt(num)
    };
    
    var url = Routes.otherActions + '/add_credits';
    
    var beforeSend = function () {
        if(_submitting) return;
        btn.prop('disabled', true);
        _btnOriginText = btn.html();
        btn.html(_spinner);
        _submitting = true;
    };
    
    var finishRequest = function () {
        if(!_submitting) return;
        btn.html(_btnOriginText);
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
            }, location.replace(location.href));
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
    _body.on('click', _addBtnSel, function () {
        _handleAddCredits($(this));
    });
};

var AddCredits = {
    init: _init
};

export default AddCredits;