/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/22 23:28
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';


import {Routes, Urls} from './globalConfig';
import Utils from './utils';
import {popMsgbox} from './msgbox'

var _body = $('body');

var _chargeNumInputSel = 'input[name="credits-charge-num"]';
var _chargeBtnSel = '#charge-credits';
var _btnOriginText = '';
var _spinner = '<i class="tico tico-spinner9 spinning"></i>';


var _submitting = false;

var _handleCreditsCharge = function (btn) {
    if(_submitting || !Utils.checkLogin()) return false;
    
    var chargeNumInput = $(_chargeNumInputSel);
    var chargeNum = chargeNumInput.length ? parseInt(chargeNumInput.val()) : 10;
    
    var data = {
        amount: chargeNum
    };
    
    var url = Routes.otherActions + '/credits_charge';
    
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
            location.href = data.data.url;
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
    _body.on('click', _chargeBtnSel, function () {
        _handleCreditsCharge($(this));
    });
};

var CreditsCharge = {
    init: _init
};

export default CreditsCharge;