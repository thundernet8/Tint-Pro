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


/**
 * 导出模块
 */
var ScrollHandler = {
    initScrollTo: _initScrollTo
};

export default ScrollHandler;