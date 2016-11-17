/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/17 22:21
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

var _body = $('body');

 // Toggle 商店页面左边菜单
var _shopMenuToggleAnchorSel = '.hamburger';

var _initShopLeftMenuToggle = function () {
    _body.on('click', _shopMenuToggleAnchorSel, function () {
        _body.toggleClass('without-menu');
    });
};

var Toggle = {
    initShopLeftMenuToggle: _initShopLeftMenuToggle
};

export default Toggle;