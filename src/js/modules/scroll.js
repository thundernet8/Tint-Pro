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
var _document = $(document);

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
var _shareBar = null;
var _postWrap = null;
var _singleBody = null;

var _calcTop = function () {
    if(!_shareBar) _shareBar = $(_shareBarSel);
    if(!_singleBody) _singleBody = $(_singleBodySel);
    if(!_postWrap) _postWrap = $(_postWrapSel);
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
    _document.on('scroll', function () {
        var top = _calcTop();
        if(!_shareBar) _shareBar = $(_shareBarSel);
        _shareBar.css('top', top + 'px');
    })
};

/* 浮动边栏 */
var _originWidgetSel = '#sidebar>.widget_float-sidebar';
var _originWidget = null;
var _originWidgetTopY = 0;
var _originWidgetHeight = 0;
var _mirrorWidgetSel = '#sidebar>.float-widget-mirror';
var _mirrorWidget = null;
var _mirrorWidgetTopY = 0;
var _mainWrapSel = '.main-wrap';
var _mainWrap = null;
var _mainWrapTopY = 0;
var _mainWrapHeight = 0;
var _windowHeight = 0;

var _handleFloatWidget = function () {
    //sidebar不和主内容并列的情况不处理
    if($(window).width() < 1000) return;
    
    if(!_originWidget) _originWidget = $(_originWidgetSel);
    if(_originWidget.length == 0) return; // 没有需要浮动的小工具
    
    if(!_mirrorWidget) _mirrorWidget = $(_mirrorWidgetSel);
    if(!_mainWrap) _mainWrap = $(_mainWrapSel);
    if(!_originWidgetTopY) _originWidgetTopY = _originWidget.offset().top;
    if(!_originWidgetHeight) _originWidgetHeight = _originWidget.height();
    if(!_mirrorWidgetTopY) _mirrorWidgetTopY = _mirrorWidget.offset().top;
    if(!_mainWrapTopY) _mainWrapTopY = _mainWrap.offset().top;
    if(!_mainWrapHeight) _mainWrapHeight = _mainWrap.height();
    if(!_windowHeight) _windowHeight = $(window).height();
    
    var documentScrollTop = _document.scrollTop();
    
    // 滚动
    if(documentScrollTop + _windowHeight + 20 > _mirrorWidgetTopY + _originWidgetHeight + 60){ // add _originWidgetHeight后保证先滚动了足够高度容纳了镜像在可视区域再显示出来
        if(_mirrorWidget.html()==''){
            _mirrorWidget.prepend(_originWidget.html());
        }
        _mirrorWidget.fadeIn('slow');
        var top = Math.max(0, documentScrollTop - _mirrorWidgetTopY + 100);
        _mirrorWidget.css('top', top);
    }else{
        _mirrorWidget.html('').fadeOut('slow');
    }
};

var _initFloatWidget = function () {
    _document.on('scroll', function () {
        _handleFloatWidget();
    });
};


/**
 * 导出模块
 */
var ScrollHandler = {
    initScrollTo: _initScrollTo,
    initShareBar: _initShareBar,
    initFloatWidget: _initFloatWidget
};

export default ScrollHandler;