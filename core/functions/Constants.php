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
 * @link https://webapproach.net/tint.html
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

defined('THEME_DASH') || define('THEME_DASH', get_template_directory() . '/dash');

defined('AVATARS_PATH') || define('AVATARS_PATH', WP_CONTENT_DIR . '/uploads/avatars');

defined('AVATARS_URL') || define('AVATARS_URL', home_url('wp-content/uploads/avatars'));

/* Theme Version */
defined('TT_PRO') || define('TT_PRO', !!preg_match('/([0-9-\.]+)PRO/i', trim(wp_get_theme()->get('Version'))));

/* Tint Site Url */
defined('TT_SITE') || define('TT_SITE', 'https://webapproach.net');

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
    //'balance'  => 'balance',
    'drafts'    => 'drafts',
    'credits' => 'credits',
    'order'    => 'order',
    'newpost' => 'newpost',
    'editpost' => 'editpost',
    'membership' => 'membership',
    'notifications' => array(
        'all',
        'comment',
        'star',
        'update',
        'credit' //TODO remove?
    ),
    'messages' => array(
        'inbox',
        'sendbox'
    ),
    'orders' => array(
        'all',
        'credit',
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
    'apsvnotify' => 'APSV.Notify',
    'download' => 'Download',
    'upload' => 'Image.Upload'
));
defined('ALLOWED_SITE_UTILS') || define('ALLOWED_SITE_UTILS', $site_allow_utils);

/* Allowed Management Routes */
$manage_allow_routes = json_encode(array(
    'status' => 'status',
    'posts' => 'posts',
    'comments' => 'comments',
    'users' => 'users',
    //'user' => 'user',
    'orders' => array(
        'all',
        'credit',
        'cash'
    ),
    //'order' => 'order',
    'coupons' => 'coupons',
    'members' => 'members',
    'products' => 'products'
));
defined('ALLOWED_MANAGE_ROUTES') || define('ALLOWED_MANAGE_ROUTES', $manage_allow_routes);

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
    'upload'                    =>  'site/upload',
    'api_root'                  =>  'api',
    'signin'                    =>  'm/signin',
    'signup'                    =>  'm/signup',
    'activate'                  =>  'm/activate',
    'signout'                   =>  'm/signout',
    'findpass'                  =>  'm/findpass',
    'resetpass'                 =>  'm/resetpass',
    'my_settings'               =>  'me/settings',
    //'my_balance'                =>  'me/balance',
    //'my_stars'                  =>  'me/stars',
    'my_drafts'                 =>  'me/drafts',
    'my_credits'                =>  'me/credits',
    'new_post'                  =>  'me/newpost',
    'my_membership'             =>  'me/membership',
    'in_msg'                    =>  'me/messages/inbox',
    'out_msg'                   =>  'me/messages/sendbox',
    'all_notify'                =>  'me/notifications/all',
    'comment_notify'            =>  'me/notifications/comment',
    'star_notify'               =>  'me/notifications/star',
    'update_notify'             =>  'me/notifications/update',
    'credit_notify'             =>  'me/notifications/credit',
    'my_all_orders'             =>  'me/orders/all',
    'my_credit_orders'          =>  'me/orders/credit',
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
    'oauth_weixin_refresh'      =>  'oauth/weixin?act=refresh',
    'manage_home'               =>  'management', //302 to status
    'manage_status'             =>  'management/status', // 全站数据统计 用户文章评论订单等数据量 运营时间等
    'manage_users'              =>  'management/users',
    'manage_posts'              =>  'management/posts',
    'manage_comments'           =>  'management/comments',
    'manage_orders'             =>  'management/orders/all',
    'manage_cash_orders'        =>  'management/orders/cash',
    'manage_credit_orders'      =>  'management/orders/credit',
    'manage_coupons'            =>  'management/coupons',
    'manage_members'            =>  'management/members',
    'manage_products'           =>  'management/products'

    // TODO: Add more
));
defined('SITE_ROUTES') || define('SITE_ROUTES', $site_endpoints);

/* Some Actions for API */
$site_api_actions = json_encode(array(
    'daily_sign' => 1, // 1代表私密action必须登录执行, 其它为公开action
    'credits_charge' => 1
    // TODO: Add more
));
defined('ALLOWED_ACTIONS') || define('ALLOWED_ACTIONS', $site_api_actions);

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