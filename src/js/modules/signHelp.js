/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/06 18:28
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';

import Utils from './utils';

var _signInLinkSel = '.login-link';

var SignHelp = {
    init: function () {
        $('body').on('click', _signInLinkSel, function (e) {
            if($(window).width() >= 640) {
                e.preventDefault();
                Utils.checkLogin();
            }
        });
    }
};

 export default SignHelp;