/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/21 21:39
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

var _joinBtnSel = '#joinvip-submit';

var _vipTypeRadiosSel = 'input[name="vip_product_id"]';

var _submitting = false;

var _getSelectedVipType = function () {
    var radios = $(_vipTypeRadiosSel);
    if(!radios.length) {
        return -3;
    }
    var productId = -3;
    radios.each(function() {
        var $this = $(this);
        if($this.prop('checked')){
            productId = $this.val();
        }
    });
    return productId;
};

var _handleJoinVip = function (btn) {
    if(_submitting || !Utils.checkLogin()) return false;
    
    var data = {
        joinVip: true,
        vipProductId: _getSelectedVipType()
    };
    
    var url = Routes.orders;
    
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
    _body.on('click', _joinBtnSel, function () {
        _handleJoinVip($(this));
    });
};

var JoinVip = {
    init: _init
};

export default JoinVip;