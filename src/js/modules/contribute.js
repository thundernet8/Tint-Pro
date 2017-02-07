/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/24 18:10
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

var _submitBtnSel = '#submit-post';

var _titleSel = 'input[name="post_title"]';
var _excerptSel = '#excerpt-input';
var _contentSel = 'textarea[name="post_content"]';
var _catSel = '#cat-selector';
var _tagsSel = '#tags-input';
var _ccTitleSel = '#origin-title';
var _ccLinkSel = '#origin-link';
var _freeDlSel = '#free-downloads';
var _saleDlSel = '#sale-downloads';
var _actionSel = 'select[name="post_status"]';
var _postIdSel = 'input[name="post_id"]';

var _submitting = false;

var _warning = function (text) {
    popMsgbox.warning({
        title: text,
        timer: 2000,
        showConfirmButton: true
    });
};

var _handleContribute = function (btn) {
    if(_submitting || !Utils.checkLogin()) return false;

    tinyMCE.triggerSave();

    var data = {};

    var titleInput = $(_titleSel);
    if(!titleInput.length || titleInput.val().length < 10) {
        _warning('标题不能为空或过短');
        return false;
    }
    data.title = titleInput.val();

    data.excerpt = $(_excerptSel).val();

    var contentInput = $(_contentSel);
    if(!contentInput.length || contentInput.val().length < 100) {
        _warning('文章内容不能少于100字符');
        return false;
    }
    data.content = contentInput.val();

    data.cat = $(_catSel).val();
    data.tags = $(_tagsSel).val();
    data.ccTitle = $(_ccTitleSel).val();
    data.ccLink = $(_ccLinkSel).val();
    data.freeDl = $(_freeDlSel).val();
    data.saleDl = $(_saleDlSel).val();
    data.action = $(_actionSel).val();

    var postId = parseInt($(_postIdSel).val());

    var url = postId < 1 ? Routes.posts : Routes.posts + '/' + postId;

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
            }, function () {
                location.href = data.data.url;
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
    _body.on('click', _submitBtnSel, function () {
        _handleContribute($(this));
    });
};

var Contribute = {
    init: _init
};

export default Contribute;