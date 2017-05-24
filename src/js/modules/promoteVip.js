/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.4
 * @package Tint
 * @author Zhiyan
 * @date 2017/02/09 14:56
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

var _promoteBtnSel = '#promotevip-submit';

var _vipTypeRadiosSel = 'input[name="vip_product_id"]';

var _submitting = false;

var _getSelectedVipType = function () {
    var radios = $(_vipTypeRadiosSel);
    if(!radios.length) {
        return 1;
    }
    var productId = -3;
    radios.each(function() {
        var $this = $(this);
        if($this.prop('checked')){
            productId = $this.val();
        }
    });
    return Math.abs(productId);
};

var _handlePromoteVip = function (btn) {
    if(_submitting || !Utils.checkLogin()) return false;
    
    var data = {
        user: btn.data('uid'),
        type: _getSelectedVipType()
    };
    
    var url = Routes.members;
    
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
    _body.on('click', _promoteBtnSel, function () {
        _handlePromoteVip($(this));
    });
};

var PromoteVip = {
    init: _init
};

export default PromoteVip;