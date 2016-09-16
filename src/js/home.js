/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/31 23:38
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

import {handleLineLoading} from './modules/loading';
import {popMsgbox} from './modules/msgbox';
import {} from './modules/bootstrap-flat';
// require('./modules/smooth-scroll');

// DOM Ready
jQuery(document).ready(function ($) {
  // 隐藏加载条
  handleLineLoading();

  // 初始化popMsgbox
  popMsgbox.init();




});
