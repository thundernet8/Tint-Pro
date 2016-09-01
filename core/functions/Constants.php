<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/28 14:27
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>

<?php

/**
 * 定义全局常量
 * @since   2.0.0
 */

/* Path */
defined('HOME_URI') || define('HOME_URI', get_home_url());

defined('THEME_DIR') || define('THEME_DIR', get_template_directory());

defined('THEME_URI') || define('THEME_URI', get_template_directory_uri());

defined('THEME_ASSET') || define('THEME_ASSET', get_template_directory_uri() . '/assets');

defined('THEME_API') || define('THEME_API', get_template_directory() . '/core/api');

defined('THEME_CLASS') || define('THEME_CLASS', get_template_directory() . '/core/classes');

defined('THEME_FUNC') || define('THEME_FUNC', get_template_directory() . '/core/functions');

defined('THEME_LIB') || define('THEME_LIB', get_template_directory() . '/core/library');

defined('THEME_MOD') || define('THEME_MOD', get_template_directory() . '/core/modules');

defined('THEME_TPL') || define('THEME_TPL', get_template_directory() . '/core/templates');

/* String */
defined('CACHE_PREFIX') || define('CACHE_PREFIX', 'tt_cache');

/* Allowed UC Tabs */
$uc_allow_tabs = json_encode(array('latest', 'comments', 'recommendations')); //TODO: add more - e.g followers/followings/activities/timelines
defined('ALLOWED_UC_TABS') || define('ALLOWED_UC_TABS', $uc_allow_tabs);

/* Allowed Action */
$m_allow_actions = json_encode(array('signin', 'signup', 'signout', 'refresh'));
defined('ALLOWED_M_ACTIONS') || define('ALLOWED_M_ACTIONS', $m_allow_actions);

/* Allowed Me Routes */
$me_allow_routes = json_encode(array(
    'settings' => 'settings',
    'balance'  => 'balance',
    'stars'    => 'stars',
    'order'    => 'order',
    'notifications' => array(
        'all',
        'comment',
        'star',
        'update'
    ),
    'messages' => array(
        'inbox',
        'sendbox'
    ),
    'orders' => array(
        'all',
        'gold',
        'cash'
    )
));
defined('ALLOWED_ME_ROUTES') || define('ALLOWED_ME_ROUTES', $me_allow_routes);

/* Allowed Oauth Routes */
$oauth_allow_routes = json_encode(array('qq', 'weibo', 'weixin'));  // TODO: more github..
defined('ALLOWED_OAUTH_SITES') || define('ALLOWED_OAUTH_SITES', $oauth_allow_routes);

/* Allowed Site Utils */
$site_allow_utils = json_encode(array('upgradebrowser'));
defined('ALLOWED_SITE_UTILS') || define('ALLOWED_SITE_UTILS', $site_allow_utils);

/* Some Endpoints */
$site_endpoints = json_encode(array(
    'upgrade_browser'   =>  'site/upgradebrowser',
    'api_root'          =>  'api',
    'signin'            =>  'm/signin',
    'signup'            =>  'm/signup',
    'signout'           =>  'm/signout',
    'my_settings'       =>  'me/settings',
    'balance'           =>  'me/balance',
    'stars'             =>  'me/stars',
    'in_msg'            =>  'me/messages/inbox',
    'out_msg'           =>  'me/messages/sendbox',
    'all_notify'        =>  'me/notifications/all',
    'comment_notify'    =>  'me/notifications/comment',
    'star_notify'       =>  'me/notifications/star',
    'update_notify'     =>  'me/notifications/update',
    'my_all_orders'     =>  'me/orders/all',
    'my_gold_orders'    =>  'me/orders/gold',
    'my_cash_orders'    =>  'me/orders/cash',
    'oauth_qq'          =>  'oauth/qq',
    'oauth_weibo'       =>  'oauth/weibo',
    'oauth_weixin'      =>  'oauth/weixin'

    // TODO: Add more
));
defined('SITE_ROUTES') || define('SITE_ROUTES', $site_endpoints);

/* jQuery Source */
$jquery_srouces = json_encode(array(
    'local_1' => THEME_ASSET . '/vender/js/jquery/1.12.4/jquery.min.js',
    'local_2' => THEME_ASSET . '/vender/js/jquery/3.1.0/jquery.min.js',
    'cdn_http' => 'http://cdn.staticfile.org/jquery/2.2.1/jquery.min.js',
    'cdn_https' => 'https://staticfile.qnssl.com/jquery/2.2.1/jquery.min.js'
));
defined('JQUERY_SOURCES') || define('JQUERY_SOURCES', $jquery_srouces);