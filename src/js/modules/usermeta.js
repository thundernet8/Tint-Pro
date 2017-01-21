/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/21 06:18
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';


import {Routes} from './globalConfig';
import Utils from './utils';

var _addMeta = function (key, value) {
    var url = Routes.userMeta + '/' + key;
    var data = {
        value: value,
        multi: true
    };
    $.post({
        url: url,
        data: Utils.filterDataForRest(data),
        dataType: 'json'
    });
};


var _updateMeta = function (key, value) {
    var url = Routes.userMeta + '/' + key;
    var data = {
        value: value
    };
    $.post({
        url: url,
        data: Utils.filterDataForRest(data),
        dataType: 'json'
    });
};


var _deleteMeta = function (key, value) {
    var url = Routes.userMeta + '/' + key;
    var data = {
        value: value
    };
    $.post({
        url: url,
        data: Utils.filterDataForRest(data),
        dataType: 'json'
    });
};


var Usermeta = {
    addMeta: _addMeta, //不唯一的meta用
    updateMeta: _updateMeta,
    deleteMeta: _deleteMeta
};

export default Usermeta;