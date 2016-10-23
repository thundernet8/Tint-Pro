/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/16 19:27
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';

import Utils from './utils';

// 季节判断
var _getSeason = function (month) {
  month = parseInt(month);
  switch (month) {
    case 3:
    case 4:
    case 5:
      return 'Spring';
      break;
    case 6:
    case 7:
    case 8:
      return 'Summer';
      break;
    case 9:
    case 10:
    case 11:
      return 'Autumn';
      break;
    case 12:
    case 1:
    case 2:
      return 'Winter';
      break;
  }
};

// 时间判断
var _getPeriod = function (hour) {
  hour = parseInt(hour);
  if(hour >= 5 && hour < 11) {
    return 'Morning';
  }
  if(hour >= 11 && hour < 16) {
    return 'Noon';
  }
  if(hour >= 16 && hour < 19) {
    return 'Evening';
  }
  return 'Night';
};

var _getSeasonalBg = function () {
  var bgRootUrl = (TT && TT.themeRoot) ? TT.themeRoot + '/assets/img/spotlight/' : Utils.getSiteUrl() + '/wp-content/themes/Tint/assets/img/spotlight/';
  var _date = new Date();
  return bgRootUrl + _getSeason(_date.getMonth() + 1).toLowerCase() + '/' + _getPeriod(_date.getHours()).toLowerCase() + '.jpg';
};

var _handleSeasonalBg = function (sel) {
  var changeBg = function () {
    var bgLayer = sel ? sel : $('body');
    bgLayer.css('background-image', 'url(' + _getSeasonalBg() + ')');
  };
  changeBg(sel);
  setInterval(changeBg.bind(this, sel), 1000*60);
};

export {_handleSeasonalBg as handleSeasonalBg};