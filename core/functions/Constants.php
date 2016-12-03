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

/* Theme Version */
defined('TT_PRO') || define('TT_PRO', !!preg_match('/([0-9-\.]+)PRO/i', trim(wp_get_theme()->get('Version'))));

/* Asset Names */
include_once THEME_FUNC . '/asset.Constant.php';

/* String */
defined('CACHE_PREFIX') || define('CACHE_PREFIX', 'tt_cache');

/* Allowed UC Tabs */
$uc_allow_tabs = json_encode(array('latest', 'comments', 'stars', 'followers', 'following', 'chat')); //TODO: add more - e.g /activities/timeline
defined('ALLOWED_UC_TABS') || define('ALLOWED_UC_TABS', $uc_allow_tabs);

/* Allowed Action */
$m_allow_actions = json_encode(array(
    'signin' => 'Signin',
    'signup' => 'Signup',
    'activate' => 'Activate',
    'signout' => 'Signout',
    'refresh' => 'Refresh',
    'findpass' => 'Findpass',
    'resetpass' => 'Resetpass'
));
defined('ALLOWED_M_ACTIONS') || define('ALLOWED_M_ACTIONS', $m_allow_actions);

/* Allowed Me Routes */
$me_allow_routes = json_encode(array(
    'settings' => 'settings', //profile设置等, @xxx/profile只提供profile资料查阅, 不可编辑
    'balance'  => 'balance',
    'stars'    => 'stars',
    'order'    => 'order',
    'newpost' => 'newpost',
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

/* Allowed Oauth Types */
$oauth_allow_types = json_encode(array('qq', 'weibo', 'weixin'));  // TODO: more e.g github..
defined('ALLOWED_OAUTH_TYPES') || define('ALLOWED_OAUTH_TYPES', $oauth_allow_types);

/* Allowed Oauth Act */
$oauth_allow_acts = json_encode(array('connect', 'disconnect', 'refresh'));
defined('ALLOWED_OAUTH_ACTIONS') || define('ALLOWED_OAUTH_ACTIONS', $oauth_allow_acts);

/* Allowed Site Utils */
$site_allow_utils = json_encode(array(
    'upgrade-browser' => 'UpgradeBrowser',
    'privacy-policies-and-terms' => 'Privacy',
    'captcha'   =>  'Captcha',
    'qr' => 'QrCode',
    'checkout' => 'CheckOut',
    'payresult' => 'PayResult',
    'qrpay' => 'QrPay',
    'paygateway' => 'PayGateway',
    'alipayreturn' => 'Alipay.Return',
    'alipaynotify' => 'Alipay.Notify',
    'apsvnotify' => 'APSV.Notify'
));
defined('ALLOWED_SITE_UTILS') || define('ALLOWED_SITE_UTILS', $site_allow_utils);

/* Some Endpoints */
$site_endpoints = json_encode(array(
    'upgrade_browser'           =>  'site/upgrade-browser',
    'privacy'                   =>  'site/privacy-policies-and-terms',   // TODO: terms
    'captcha'                   =>  'site/captcha',
    'qr'                        =>  'site/qr',
    'checkout'                  =>  'site/checkout',
    'payresult'                 =>  'site/payresult',
    'qrpay'                     =>  'site/qrpay',
    'paygateway'                =>  'site/paygateway',
    'alipayreturn'              =>  'site/alipayreturn',
    'alipaynotify'              =>  'site/alipaynotify',
    'apsvnotify'                =>  'site/apsvnotify',
    'api_root'                  =>  'api',
    'signin'                    =>  'm/signin',
    'signup'                    =>  'm/signup',
    'activate'                  =>  'm/activate',
    'signout'                   =>  'm/signout',
    'findpass'                  =>  'm/findpass',
    'resetpass'                 =>  'm/resetpass',
    'my_settings'               =>  'me/settings',
    'balance'                   =>  'me/balance',
    'stars'                     =>  'me/stars',
    'new_post'                  =>  'me/newpost',
    'in_msg'                    =>  'me/messages/inbox',
    'out_msg'                   =>  'me/messages/sendbox',
    'all_notify'                =>  'me/notifications/all',
    'comment_notify'            =>  'me/notifications/comment',
    'star_notify'               =>  'me/notifications/star',
    'update_notify'             =>  'me/notifications/update',
    'my_all_orders'             =>  'me/orders/all',
    'my_gold_orders'            =>  'me/orders/gold',
    'my_cash_orders'            =>  'me/orders/cash',
    'oauth_qq'                  =>  'oauth/qq',
    'oauth_weibo'               =>  'oauth/weibo',
    'oauth_weixin'              =>  'oauth/weixin',
    'oauth_qq_last'             =>  'oauth/qq/last',
    'oauth_weibo_last'          =>  'oauth/weibo/last',
    'oauth_weixin_last'         =>  'oauth/weixin/last',
    'oauth_qq_disconnect'       =>  'oauth/qq?act=disconnect',
    'oauth_weibo_disconnect'    =>  'oauth/weibo?act=disconnect',
    'oauth_weixin_disconnect'   =>  'oauth/weixin?act=disconnect',
    'oauth_qq_refresh'          =>  'oauth/qq?act=refresh',
    'oauth_weibo_refresh'       =>  'oauth/weibo?act=refresh',
    'oauth_weixin_refresh'      =>  'oauth/weixin?act=refresh'

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

/* Lazy pending image */
defined('LAZY_PENDING_IMAGE') || define('LAZY_PENDING_IMAGE', THEME_ASSET . '/img/image-pending.gif');
defined('LAZY_PENDING_AVATAR') || define('LAZY_PENDING_AVATAR', THEME_ASSET . '/img/avatar/avatar.png');