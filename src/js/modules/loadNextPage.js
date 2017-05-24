/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/22 14:19
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';

import {Classes} from './globalConfig';
//import {Utils} from './utils';

var _body = $('body');

var _postListCls = 'archive-posts';

var _loadNextComponentID = 'loadNext';
var _loadingIcon = '<i class="tico tico-spinner2 spinning"></i>';
var _unLoadingIcon = '<i class="tico tico-angle-down"></i>';

var _isLoadingNext = false;

// var _getNextPageUrl = function (nextPage) {
//     var currentUrl = Utils.getSiteUrl();
//     var reg = /^(.*\/page\/)[0-9]+$/;
//     var nextPageUrl;
//     if(reg.test(currentUrl)) {
//         nextPageUrl = currentUrl.match(reg)[1] + nextPage;
//     }
// };

var _handlePageContent = function (html, url) {
    var doc = $(html);
    var postList = $('.' + _postListCls);
    if(doc && postList) {
        postList.html(doc.find('.' + _postListCls).html());
        history.pushState('200', doc[9].innerText, url);
        document.title = doc[9].innerText;
    }
};

var _ajaxLoadNext = function (btn) {
    if(_isLoadingNext) return false;
    
    var nextPageUrl = btn.data('next-page-url');
    
    if(!nextPageUrl) return false;
    
    var beforeSend = function () {
        _body.addClass(Classes.appLoading);
        _isLoadingNext = true;
        btn.html(_loadingIcon);
    };
    
    var finishRequest = function () {
        _body.removeClass(Classes.appLoading);
        _isLoadingNext = false;
        btn.html(_unLoadingIcon);
    };
    
    var success = function (data, textStatus, xhr) {
        if(data && xhr.status == '200') {
            _handlePageContent(data, nextPageUrl);
        }
        finishRequest();
    };
    var error = function (xhr, textStatus, err) {
        // TODO
        finishRequest();
    };
    
    $.get({
        url: nextPageUrl,
        dataType: 'html',
        beforeSend: beforeSend,
        success: success,
        error: error
    });
};


/* 导出模块 */
/* ---------------------- */

var loadNext = {
    init: function () {
        _body.on('click', '[data-component='+_loadNextComponentID+']', function () {
            var $this = $(this);
            _ajaxLoadNext($this);
        });
    }
};

export default loadNext;