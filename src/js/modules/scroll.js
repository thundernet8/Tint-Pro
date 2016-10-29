/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/23 15:39
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

var _body = $('body');

/* 滚动到顶部或底部 */
var _scrollTopBottomAnchorCls = 'scroll-to';
var _scrollTopAnchorCls = 'scroll-top';
var _scrollBottomAnchorCls = 'scroll-bottom';

var _handleScrollTo = function (btn) {
    if(btn.hasClass(_scrollBottomAnchorCls)) {
        _body.animate({
            scrollTop: $(document).height()
        }, 'slow');
    }else if(btn.hasClass(_scrollTopAnchorCls)) {
        _body.animate({
            scrollTop: 0
        }, 'slow');
    }
    return false;
};

var _initScrollTo = function () {
    _body.on('click', '.'+_scrollTopBottomAnchorCls, function () {
        _handleScrollTo($(this));
    })
};


/* 分享条 */
var _postWrapSel = '#main>.post';
var _postWrapBottomY = 0;
var _singleBodySel = '.single-body';
var _singleBodyTopY = 0;
var _shareBarSel = '.single-body>.share-bar';
var _shareBarHeight = 0;
var _document = null;
var _shareBar = null;
var _postWrap = null;
var _singleBody = null;

var _calcTop = function () {
    if(!_shareBar) _shareBar = $(_shareBarSel);
    if(!_singleBody) _singleBody = $(_singleBodySel);
    if(!_postWrap) _postWrap = $(_postWrapSel);
    if(!_document) _document = $(document);
    if(!_shareBarHeight) _shareBarHeight = _shareBar.height();
    if(!_postWrapBottomY) _postWrapBottomY = _postWrap.offset().top + _postWrap.height() + 40;
    if(!_singleBodyTopY) _singleBodyTopY = _singleBody.offset().top;
    
    var documentScrollTop = _document.scrollTop();
    var top = 0;
    // 顶部限制
    top = Math.max(20, 80 + documentScrollTop - _singleBodyTopY);
    
    // 底部限制
    if(_singleBodyTopY + top + _shareBarHeight > _postWrapBottomY) {
        top = _postWrapBottomY -_shareBarHeight - _singleBodyTopY;
    }
    
    return top;
};

var _initShareBar = function () {
    if(!_document) _document = $(document);
    _document.on('scroll', function () {
        var top = _calcTop();
        if(!_shareBar) _shareBar = $(_shareBarSel);
        _shareBar.css('top', top + 'px');
    })
};

/**
 * 导出模块
 */
var ScrollHandler = {
    initScrollTo: _initScrollTo,
    initShareBar: _initShareBar
};

export default ScrollHandler;