<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/15 21:39
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class MeSettingsVM
 */
class MeSettingsVM extends BaseVM {

    /**
     * @var int 用户ID
     */
    private $_userId;

    protected function __construct() {
        $this->_cacheUpdateFrequency = 'daily';
        $this->_cacheInterval = 3600*24; // 缓存保留一天
    }

    /**
     * 获取实例
     *
     * @since   2.0.0
     * @param   int    $user_id   用户ID
     * @return  static
     */
    public static function getInstance($user_id = 0) {
        $instance = new static();
        $instance->_cacheKey = 'tt_cache_' . $instance->_cacheUpdateFrequency . '_vm_' . __CLASS__ . '_user' . $user_id;
        $instance->_userId = $user_id;
        $instance->configInstance();
        return $instance;
    }

    protected function getRealData() {
        $data = get_userdata($this->_userId);
        if(!$data) return null;

        $user_info = array();
        $user_info['ID'] = $this->_userId;
        $user_info['display_name'] = $data->display_name;
        $user_info['nickname'] = $data->nickname; //get_user_meta($author->ID, 'nickname', true);
        $user_info['email'] = $data->user_email;
        $user_info['member_since'] = mysql2date('Y/m/d', $data->user_registered);
        $user_info['member_days'] = max(1, round(( strtotime(date('Y-m-d')) - strtotime( $data->user_registered ) ) /3600/24));
        $user_info['site'] = $data->user_url;
        $user_info['description'] = $data->description;

        $avatar = new Avatar($data->ID, 'medium');
        $user_info['avatar'] = $avatar->getAvatar();
        $user_info['avatar_type'] = $avatar->avatarType;

        $custom_avatar_path = AVATARS_PATH . DIRECTORY_SEPARATOR . $this->_userId . '.jpg';
        if(file_exists($custom_avatar_path)){
            $user_info['custom_avatar'] = $avatar->getAvatar('custom');
        }else{
            $user_info['custom_avatar'] = Avatar::getDefaultAvatar('medium');
        }

        $user_info['letter_avatar'] = $avatar->getAvatar(Avatar::LETTER_AVATAR);

        if(tt_has_connect('qq', $this->_userId)) {
            $user_info['qq_avatar'] = $avatar->getAvatar(Avatar::QQ_AVATAR);
        }

        if(tt_has_connect('weibo', $this->_userId)){
            $user_info['weibo_avatar'] = $avatar->getAvatar(Avatar::WEIBO_AVATAR);
        }

        if(tt_has_connect('weixin', $this->_userId)){
            $user_info['weixin_avatar'] = $avatar->getAvatar(Avatar::WEIXIN_AVATAR);
        }

//        $user_info['latest_login'] = mysql2date('Y/m/d g:i:s A', $data->tt_latest_login);
//        $user_info['latest_login_before'] = mysql2date('Y/m/d g:i:s A', $data->tt_latest_login_before);
//        $user_info['last_login_ip'] = $data->tt_latest_ip_before;
//        $user_info['this_login_ip'] = $data->tt_latest_login_ip;


        $user_info['qq'] = $data->tt_qq; //$data->tt_qq ? 'http://wpa.qq.com/msgrd?v=3&uin=' . $data->tt_qq . '&site=qq&menu=yes' : '';
        $user_info['weibo'] = $data->tt_weibo; //$data->tt_weibo ? 'http://weibo.com/' . $data->tt_weibo : '';
        $user_info['weixin'] = $data->tt_weixin;
        $user_info['twitter'] = $data->tt_twitter; //$data->tt_twitter ? 'https://twitter.com/' . $data->tt_twitter : '';
        $user_info['facebook'] = $data->tt_facebook; //$data->tt_facebook ? 'https://www.facebook.com/' . $data->tt_facebook : '';
        $user_info['googleplus'] = $data->tt_googleplus; //$data->tt_googleplus ? 'https://plus.google.com/u/0/' . $data->tt_googleplus : '';
        $user_info['alipay_email'] = $data->tt_alipay_email;
        $user_info['alipay_pay'] = $data->tt_alipay_pay_qr;
        $user_info['wechat_pay'] = $data->tt_wechat_pay_qr;

        //$user_info['cover'] = tt_get_user_cover($data->ID, 'full');

        //$user_info['referral'] = tt_get_referral_link($data->ID);
        //$user_info['banned'] = $data->tt_banned;
        //$user_info['banned_time'] = mysql2date('Y/m/d g:i:s A', $data->tt_banned_time);
        //$user_info['banned_reason'] = $data->tt_banned_reason;

        return (object)$user_info;
    }
}