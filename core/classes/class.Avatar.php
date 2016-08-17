<?php

/**
 * Copyright 2016, WebApproach.net
 * All right reserved.
 *
 * @author Zhiyan
 * @date 16/6/24 14:37
 * @license GPL v3 LICENSE
 */

?>
<?php

/**
 * 用户头像
 */

class TAvatar{
    /**
     * 构造器,根据用户id或邮箱获得头像
     *
     * @since   2.0.0
     *
     * @access  public
     * @param   int | string    $uid_or_email    用户ID或用户邮箱
     */
    public function __construct($uid_or_email){

    }

    /**
     * 获取本地默认头像
     *
     * @since   2.0.0
     *
     * @static
     * @access  public
     * @param   string      $size   头像尺寸(small|medium|large)
     * @return  string
     */
    public static function get_default_avatar($size = 'medium'){
        $size = in_array($size, array('small', 'medium', 'large')) ? $size : 'medium';
        return THEME_ASSET . '/img/avatar_' . $size . '.png';
    }
}
