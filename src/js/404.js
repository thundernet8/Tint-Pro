/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/12 21:30
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

import {handleLineLoading} from './modules/loading';
import {} from './modules/bootstrap-flat';
import {Utils} from './modules/utils'

// DOM Ready
jQuery(document).ready(function ($) {
    // 隐藏加载条
    handleLineLoading();
    
    // 跳转计时
    var _redirectBtn = $('#linkBackHome');
    var _numSpan = _redirectBtn.children('span.num');
    var _countNum = function (span) {
        var sec = parseInt(span.text());
        if(sec-1 <= 0) {
            clearInterval(_interval);
            _redirectBtn.html('跳转中...');
            window.location.href = Utils.getSiteUrl();
        }else{
            span.text(sec-1);
        }
    };
    
    var _interval = setInterval(_countNum.bind(this, _numSpan), 1000);
    
});