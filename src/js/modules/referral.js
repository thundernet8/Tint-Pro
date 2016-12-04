/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/04 21:00
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

import {} from './cookie';
import Utils from './utils';

// 设置推广信息到cookie(url ref参数)

var _body = $('body');

var _initRef = function () {
    if(!($.cookie('tt_ref'))){
        $.cookie('tt_ref', Utils.getQueryString('ref'), {expires: 1, path: '/'});
    }
};

var Referral = {
    init: _initRef
};

export default Referral;