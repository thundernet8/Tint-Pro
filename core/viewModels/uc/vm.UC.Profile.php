<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/11 21:11
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class UCProfileVM
 */
class UCProfileVM extends BaseVM {

    /**
     * @var int 作者ID
     */
    private $_authorId;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $author_id   作者ID
     * @return  static
     */
    public static function getInstance($author_id = 0) {
        $instance = new static();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_author' . $author_id;
        $instance->_authorId = $author_id;
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        //$author = get_user_by('ID', $this->_authorId);
        $data = get_userdata($this->_authorId);
        if(!$data) return null;

        $author_info = array();
        $author_info['ID'] = $this->_authorId;
        $author_info['display_name'] = $data->display_name;
        $author_info['nickname'] = $data->nickname; //get_user_meta($author->ID, 'nickname', true);
        $author_info['email'] = $data->user_email;
        $author_info['member_since'] = mysql2date('Y/m/d', $data->user_registered);
        $author_info['member_days'] = max(1, round(( strtotime(date('Y-m-d')) - strtotime( $data->user_registered ) ) /3600/24));
        $author_info['site'] = $data->user_url;
        $author_info['description'] = $data->description;

        $author_info['avatar'] = tt_get_avatar($data->ID, 'medium');

        $author_info['latest_login'] = mysql2date('Y/m/d g:i:s A', $data->tt_latest_login);
        $author_info['latest_login_before'] = mysql2date('Y/m/d g:i:s A', $data->tt_latest_login_before);
        $author_info['last_login_ip'] = $data->tt_latest_ip_before;
        $author_info['this_login_ip'] = $data->tt_latest_login_ip;


        $author_info['qq'] = $data->tt_qq ? 'http://wpa.qq.com/msgrd?v=3&uin=' . $data->tt_qq . '&site=qq&menu=yes' : ''; //get_user_meta($author->ID, 'tt_qq', true);
        $author_info['weibo'] = $data->tt_weibo ? 'http://weibo.com/' . $data->tt_weibo : ''; //get_user_meta($author->ID, 'tt_weibo', true);
        $author_info['weixin'] = $data->tt_weixin; //get_user_meta($author->ID, 'tt_weixin', true);
        $author_info['twitter'] = $data->tt_twitter ? 'https://twitter.com/' . $data->tt_twitter : ''; //get_user_meta($author->ID, 'tt_twitter', true);
        $author_info['facebook'] = $data->tt_facebook ? 'https://www.facebook.com/' . $data->tt_facebook : ''; //get_user_meta($author->ID, 'tt_facebook', true);
        $author_info['googleplus'] = $data->tt_googleplus ? 'https://plus.google.com/u/0/' . $data->tt_googleplus : ''; //get_user_meta($author->ID, 'tt_googleplus', true);
        //$author_info['alipay_email'] = $data->tt_alipay_email; //get_user_meta($author->ID, 'tt_alipay_email', true);
        $author_info['alipay_pay'] = $data->tt_alipay_pay_qr; //get_user_meta($author->ID, 'tt_alipay_pay_qr', true);
        $author_info['wechat_pay'] = $data->tt_wechat_pay_qr; //get_user_meta($author->ID, 'tt_wechat_pay_qr', true);

        //$author_info['cover'] = tt_get_user_cover($data->ID, 'full');

        $author_info['referral'] = tt_get_referral_link($data->ID);
        $author_info['banned'] = $data->tt_banned;
        //$author_info['banned_time'] = mysql2date('Y/m/d g:i:s A', $data->tt_banned_time);
        //$author_info['banned_reason'] = $data->tt_banned_reason;

        return (object)$author_info;
    }
}