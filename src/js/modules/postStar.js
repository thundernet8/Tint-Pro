/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/13 21:06
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';

import {Routes} from './globalConfig';
import Utils from './utils';

var _body = $('body');

/* Star文章 */
/* --------------------- */

// Star按钮选择器
var _postStarBtnSel = '.post-meta-likes'; // 存在两处
var _postStarCountSel = '.js-article-like-count';
var _postStaredUserWrapSel = '.post-like-avatars';

// Staring
var _staring = false;

var _markStared = function (starCount, userInfo) {
    $(_postStarBtnSel).addClass('active');
    
    $(_postStarCountSel).text(starCount.toString());
    
    var starUserImg = '<li class="post-like-user"><img src="' + userInfo.avatar + '" alt="' + userInfo.name + '" title="' + userInfo.name + '" data-user-id="' + userInfo.uid + '"></li>';
    $(_postStaredUserWrapSel).prepend(starUserImg);
};

var _postStar = function (btn) {
    if(_staring || btn.hasClass('active') || !Utils.checkLogin()) return false;
    
    var url = Routes.postStars + '/' + btn.data('post-id');
    var data = {
        postStarNonce: btn.data('nonce')
    };
    
    var beforeSend = function () {
        // return false; // Note: return false 会导致AJAX请求取消, return 空或true则继续请求
        if(_staring) return false;
        _staring = true;
    };
    var finishRequest = function () {
        if(!_staring) return;
        _staring = false;
    };
    var success = function (data, textStatus, xhr) {
        if(data.success && data.success == 1) {
            _markStared(data.stars, data);
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
        data: Utils.filterDataForRest(data),
        dataType: 'json',
        beforeSend: beforeSend,
        success: success,
        error: error
    });
};


/* 导出模块 */
/* ---------------------- */

var postStarKit = {
    init: function () {
        _body.on('click', _postStarBtnSel, function () {
            var $this = $(this);
    
            _postStar($this);
        });
    }
};

export default postStarKit;