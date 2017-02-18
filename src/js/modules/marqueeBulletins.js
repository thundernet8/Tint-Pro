/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/02/18 18:30
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */

'use strict';

var _id = 'bulletins-scroll-zone';

var _startMarquee = function(lineHeight, speed, delay, id){
    var t;
    var p = false;
    var o = document.getElementById(id);
    o.innerHTML+=o.innerHTML;
    o.onmouseover=function(){p=true;};
    o.onmouseout=function(){p=false;};
    o.scrollTop = 0;
    function start(){
        t=setInterval(scrolling, speed);
        if(!p){ o.scrollTop += 1;}
    }
    function scrolling(){
        if(o.scrollTop%lineHeight!=0){
            o.scrollTop += 1;
            if(o.scrollTop>=o.scrollHeight/2)
                o.scrollTop = 0;
        }else{
            clearInterval(t);
            setTimeout(start, delay);
        }
    }
    setTimeout(start, delay);
};

var _initMarqueeBulletins = function () {
    if($('#' + _id).length>0){
        _startMarquee(20, 30, 5000, _id);
    }
};

var MarqueeBulletins = {
    init: _initMarqueeBulletins
};

export default MarqueeBulletins;