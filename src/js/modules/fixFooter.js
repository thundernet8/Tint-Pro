/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/06 23:41
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';

export default function () {
    var footer = $('body>footer');
    var diffH = $(window).height() - footer.offset().top - footer.height();
    if( diffH > 0 ) {
        footer.css('position', 'relative').css('top', diffH);
    }
}
 