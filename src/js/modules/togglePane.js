/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/19 20:48
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

 
var _body = $('body');

var _initTogglePane = function () {
    _body.on('click', '.toggle-click-btn', function () {
        var $this = $(this);
        $this.next('.toggle-content').slideToggle('slow');
        $this.parent().toggleClass('show');
    });
};

var TogglePane = {
    init: _initTogglePane
};

export default TogglePane;