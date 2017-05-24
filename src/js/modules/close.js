/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/02/18 17:49
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';

var _setCookie = function (cookieName) {
    var timestamp = parseInt((new Date).getTime()/1000);
    $.cookie(cookieName, timestamp, {expires: 1});
};

var _toggleClose = function () {
    var firstArg = arguments.length > 0 ? arguments[0] : null;
    $('[data-toggle="close"]').on('click', function(){
        var $this = $(this);
        var targetSel;
        if(targetSel = $this.data('target')) {
            var target = $(targetSel);
            target.length && target.slideUp() && _setCookie(firstArg);
        }
    })
};

var ToggleClose = {
    init: _toggleClose
};

export default ToggleClose;