/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/08 00:13
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';


import {Routes} from './globalConfig';
import Utils from './utils';

var _body = $('body');

/* Unstar文章 */
/* --------------------- */

// Unstar按钮选择器
var _unstarBtnSel = '.unstar-link>a';

// Unstaring
var _unstaring = false;

var _removeUnstared = function (postId) {
    var articleBoxSel = '#post-' + postId.toString();
    var article = $(articleBoxSel);
    if(article) {
        article.slideUp.remove();
    }
};

var _unstar = function (btn) {
    if(_unstaring || !Utils.checkLogin()) return false;
    
    var postId = btn.data('post-id');
    var url = Routes.postStars + '/' + postId;
    var data = {
        unstarNonce: ''
    };
    
    var beforeSend = function () {
        // return false; // Note: return false 会导致AJAX请求取消, return 空或true则继续请求
        if(_unstaring) return false;
        _unstaring = true;
    };
    var finishRequest = function () {
        if(!_unstaring) return;
        _unstaring = false;
    };
    var success = function (data, textStatus, xhr) {
        if(data.success && data.success == 1) {
            _removeUnstared(postId);
        }else{
            // TODO
        }
        finishRequest();
    };
    var error = function (xhr, textStatus, err) {
        // TODO
        finishRequest();
    };
    $.post({
        url: url,
        type: 'DELETE',
        data: Utils.filterDataForRest(data),
        dataType: 'json',
        beforeSend: beforeSend,
        success: success,
        error: error
    });
};


/* 导出模块 */
/* ---------------------- */

var UnstarKit = {
    init: function () {
        _body.on('click', _unstarBtnSel, function () {
            var $this = $(this);
    
            _unstar($this);
        });
    }
};

export default UnstarKit;