<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/06/24 14：37
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

if (!defined('LETTER_AVATAR_URI')){
    define('LETTER_AVATAR_URI', get_template_directory_uri() . '/assets/img/avatar/letters/');
}

/**
 * 用户头像
 */

final class Avatar{

    const GRAVATAR = 'gravatar';

    const QQ_AVATAR = 'qq';

    const WEIBO_AVATAR = 'weibo';

    const WEIXIN_AVATAR = 'weixin';

    const CUSTOM_AVATAR = 'custom';

    const LETTER_AVATAR = 'letter';

    /**
     * 头像尺寸
     *
     * @since   2.0.0
     *
     * @access  private
     * @var     string
     */
    private $_size = 'medium';


    /**
     * 尺寸对照
     *
     * @since   2.0.0
     *
     * @static
     * @access  private
     * @var     array
     */
    private static $_sizeMap = array(
        'small'     =>  32,
        'medium'    =>  64,
        'large'     =>  96
    );

    /**
     * Gravatar API url
     *
     * @since   2.0.0
     *
     * @static
     * @access  private
     * @var     string
     */
    private static $_gravatarAPI = 'https://cn.gravatar.com/avatar/';


    /**
     * Gravatar API url
     *
     * @since   2.0.0
     *
     * @static
     * @access  private
     * @var     string
     */
    private static $_letterAvatarAPI = LETTER_AVATAR_URI;  //php 5.6以上可以THEME_ASSET . '/img/avatar/letters/', 弱智的低版本不支持类中常量与字符串拼接


    /**
     * QQ avatar API
     *
     * @since   2.0.0
     *
     * @static
     * @access  private
     * @var     string
     */
    private static $_qqAvatarAPI = 'https://q.qlogo.cn/qqapp/';


    /**
     * 微博 avatar API
     *
     * @since   2.0.0
     *
     * @static
     * @access  private
     * @var     string
     */
    private static $_weiboAvatarAPI = 'http://tp1.sinaimg.cn/';


    /**
     * 允许的头像类型
     *
     * @since   2.0.0
     *
     * @static
     * @access  private
     * @var     array
     */
    public static $_avatarTypes = array(
        Avatar::GRAVATAR,
        Avatar::QQ_AVATAR,
        Avatar::WEIBO_AVATAR,
        Avatar::WEIXIN_AVATAR,
        Avatar::CUSTOM_AVATAR,
        Avatar::LETTER_AVATAR
    );


    /**
     * 当前的头像类型
     *
     * @since 2.0.0
     * @var
     */
    public $avatarType;


    /**
     * 用户实例
     * Note: 由于禁止游客评论，即每个评论以及头像都对应一个数据库存在的用户，则不论通过用户id还是邮箱实例化Avatar时，必须绑定对应用户实例
     *
     * @since   2.0.0
     *
     * @access  private
     * @var     object  //WP_User
     */
    private $_user;


    /**
     * 缓存键
     *
     * @since   2.0.0
     *
     * @access  public
     * @var     string
     */
    public $cache_key;


    /**
     * 构造器,根据用户id或邮箱获得头像
     *
     * @since   2.0.0
     *
     * @access  public
     * @param   int | string | object    $id_or_email    用户ID或用户邮箱或用户实例对象
     * @param   string | int    尺寸
     */
    public function __construct($id_or_email, $size='medium'){
        if($id_or_email instanceof WP_User){
            $this->_user = $id_or_email;
        } elseif (is_email(strval($id_or_email))){
            $this->_user = get_user_by('email', $id_or_email);
        } elseif(intval($id_or_email) > 0) {
            $this->_user = get_user_by('id', $id_or_email);
        }else{
            $this->_user = (object)array(
                'ID' => 0,
                'display_name' => strval($id_or_email),
                'user_email' => ''
            );
        }
        $this->_size = self::strSize($size);
        //为每个用户头像赋予一个专用缓存key
        $key = CACHE_PREFIX . '_daily_avatar_' . $this->_user->ID . '_' . md5(strval($this->_user->ID) . strval($this->_size) . Utils::getCurrentDateTimeStr('day'));
        $this->cache_key = $key;
    }


    /**
     * 获取头像，主要方法
     *
     * @since   2.0.0
     *
     * @access  public
     * @param   $type
     * @return  string
     */
    public function getAvatar($type = ''){
        $this->avatarType = $this->getUserAvatarType();
        $type = $type && in_array($type, Avatar::$_avatarTypes) ? $type : $this->avatarType;
        switch ($type){
            case 'gravatar':
                return $this->getGravatar();
                break;
            case 'qq':
                return self::$_qqAvatarAPI . tt_get_option('tt_qq_openid') . '/' . get_user_meta( $this->_user->ID, 'tt_qq_openid', true ) . '/100';
                break;
            case 'weibo':
                // return self::$_weiboAvatarAPI . get_user_meta( $this->_user->ID, 'tt_weibo_openid', true ) . '/180/0/1';
                $weibo_avatar = get_user_meta($this->_user->ID, 'tt_weibo_avatar', true);
                return set_url_scheme($weibo_avatar);
                break;
            case 'weixin':
                return get_user_meta( $this->_user->ID, 'tt_weixin_avatar', true) ? get_user_meta( $this->_user->ID, 'tt_weixin_avatar', true) : $this->getLetterAvatar();
                break;
            case 'custom':
                return HOME_URI . '/wp-content/uploads/avatars/' . $this->_user->ID . '.jpg';
            default:
                return $this->getLetterAvatar();
        }

    }


    /**
     * 获取Gravatar
     *
     * @since   2.0.0
     *
     * @access  public
     * @return  string
     */
    public function getGravatar(){
        $default = self::getDefaultAvatar($this->_size);
        return self::$_gravatarAPI . md5( strtolower( trim( $this->_user->user_email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . self::$_sizeMap[$this->_size];
    }


    /**
     * 获取字母头像
     *
     * @since   2.0.0
     *
     * @access  public
     * @return  string
     */
    public function getLetterAvatar(){
        $instance = new NameFirstChar($this->_user->display_name, true, "Sharp");
        $firstLetter = $instance->toUpperCase();
        return self::$_letterAvatarAPI . $firstLetter . '/' . $this->_size . '.png';
    }


    /**
     * 获取本地默认头像
     *
     * @since   2.0.0
     *
     * @static
     * @access  public
     * @param   string | int      $size   头像尺寸(small|medium|large) | (100)
     * @return  string
     */
    public static function getDefaultAvatar($size = 'medium'){
        $size = self::strSize($size);
        return THEME_ASSET . '/img/avatar/avatar_' . $size . '.png';
    }


    /**
     * 获取用户头像类型
     *
     * @since   2.0.0
     *
     * @access  private
     * @return  string
     */
    private function getUserAvatarType(){
        if($this->_user->ID == 0 && $this->_user->user_email == '') return 'letter';
        $type = get_user_meta($this->_user->ID, 'tt_avatar_type', true);
        $type = in_array($type, self::$_avatarTypes) ? $type : 'gravatar';
        if($type=='gravatar'){
            return tt_get_option('tt_enable_gravatar') ? $type : 'letter';
        }
        return $type;
    }


    /**
     * 辅助方法 - 语义化尺寸
     *
     * @since   2.0.0
     *
     * @static
     * @access  private
     * @param   string | int    $size
     * @return  string
     */
    private static function strSize($size='medium'){
        if(is_int($size)){
            if($size>64){
                $size = 'large';
            } elseif ($size>48){ // TODO 32?
                $size = 'medium';
            }else{
                $size = 'small';
            }
        }
        $size = in_array($size, array('small', 'medium', 'large')) ? $size : 'medium';

        return $size;
    }
}
