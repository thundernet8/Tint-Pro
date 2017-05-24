<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/28 22:53
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>升级你的浏览器 - <?php bloginfo('name'); ?></title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <style>
        /* reset样式 */
        html{color:#000;background:#fff;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
        body{margin:0;padding:0;font:12px/1.5 tahoma,arial,"Hiragino Sans GB","Microsoft Yahei","\5b8b\4f53";min-width:1000px;}
        *{margin:0;padding:0}
        ul{list-style:none}
        h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:500}
        a,a:link,a:hover,a:active{color:#000;}
        a:hover{text-decoration:none;border-bottom:1px solid #000;}
        ins,a{text-decoration:none}
        .clearfix:after{visibility:hidden;display:block;font-size:0;content:" ";clear:both;height:0}
        .clearfix{zoom:1}
        /* 结束reset样式 */
        .special-header {
            width: 990px;
            height: 60px;
            margin: 0 auto;
            line-height: 60px;
        }
        ul.menu-list {
            float: right;
        }
        ul.menu-list li {
            display: inline-block;
            zoom:1; *display:inline;
            margin-left: 10px;
        }
        span.username {
            padding-left: 10px;
        }
        .special-main {
            width: 100%;
            height: 630px;
            text-align: center;
/*            filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src="*/<?php //echo THEME_ASSET . '/img/special_main_bg.jpg'; ?>/*", sizingMethod='scale');*/
/*        -ms-filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src="*/<?php //echo THEME_ASSET . '/img/special_main_bg.jpg'; ?>/*", sizingMethod='scale');*/
        min-width: 990px;
        }
        .special-content {
            padding-top: 75px;
        }
        .special-main h1 {
            font-size: 46px;
            margin-bottom: 10px;
        }
        .special-main p {
            font-size: 25px;
        }
        ul.special-list li {
            display: inline-block;
            zoom:1; *display:inline;
            width: 281px;
            font-size: 18px;
            line-height: 32px;
            margin-top: 92px;
        }
        ul.special-list li.first {
            border-right: 1px solid #ebe3dd;
        }
        a.download-link {
            display: inline-block;
            zoom:1; *display:inline;
            padding-top: 60px;
            position: relative;
        }
        a.download-link:hover {
            border-bottom: none;
            color:#08c;
        }
        .ie {
            position: absolute;
            background: url("<?php echo THEME_ASSET . '/img/icon/ie.png'; ?>") center top no-repeat;
        }
        .chrome {
            position: absolute;
            background: url("<?php echo THEME_ASSET . '/img/icon/chrome.png'; ?>") center top no-repeat;
        }
        .link-close-window, .link-close-window:link, .link-close-window:active{
            position: relative;
            top: 50px;
            font-size: 24px;
            color: #08c;
        }
        .link-close-window:hover {
            text-decoration: none;
            border-bottom: 1px solid #08c;
        }
        div.special-footer {
            width: 990px;
            height: 215px;
            margin: 0 auto;
            padding-top: 31px;
            text-align: center;
            font-size: 12px;
        }
        ul.footer-list {
            margin-bottom: 10px;
        }
        ul.footer-list li {
            display: inline-block;
            zoom:1; *display:inline;
            padding: 0 7px 0 2px;
        }
        ul.footer-list li.first {
            padding-left: 0;
        }
    </style>
</head>
<body class="false">
<div class="special-header clearfix">
</div>
<div class="special-main">
    <div class="special-content">
        <h1>浏览器版本太低</h1> <p>为了更佳的展示和阅读效果，建议升级浏览器</p>
        <ul class="special-list">
            <li class="first">
                <a class="download-link ie" href="http://www.microsoft.com/china/windows/IE/upgrade/index.aspx" title="IE浏览器最新版">IE浏览器最新版</a> </li>
            <li> <a class="download-link chrome" href="http://www.google.cn/chrome/browser/desktop/index.html" title="chrome浏览器最新版">chrome浏览器最新版</a> </li>
        </ul>
        <a class="link-close-window" id="linkCloseWindow" href="javascript:;" title="关闭页面" >关闭页面</a>
        <script>
            (function () {
                document.getElementById("linkCloseWindow").onclick = function () {
                    var opened=window.open('about:blank','_self');
                    opened.opener=null;
                    opened.close();
                };
            })();
        </script>
    </div>
</div>
<div class="special-footer">
</div>
</body>
</html>
