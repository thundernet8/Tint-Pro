/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/11 21:38
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */

'use strict';


import Utils from './utils';

// API路由列表
var routes = {
    signIn: Utils.getAPIUrl('/session'),
    session: Utils.getAPIUrl('/session'),
    signUp: Utils.getAPIUrl('/users'),
    users: Utils.getAPIUrl('/users'),
    comments: Utils.getAPIUrl('/comments'),
    commentStars: Utils.getAPIUrl('/comment/stars'), //TODO more
    postStars: Utils.getAPIUrl('/post/stars'),
    myFollower: Utils.getAPIUrl('/users/me/followers'),
    myFollowing: Utils.getAPIUrl('/users/me/following'),
    follower: Utils.getAPIUrl('/users/{{uid}}/followers'),
    following: Utils.getAPIUrl('/users/{{uid}}/following')
};

// 特殊类名
var classes = {
   appLoading: 'is-loadingApp'
};

export {routes as Routes, classes as Classes};