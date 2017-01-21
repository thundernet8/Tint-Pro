<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/17 13:46
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * 判断用户是否已经绑定了开放平台账户
 *
 * @since 2.0.0
 * @param string $type
 * @param int $user_id
 * @return bool
 */
function tt_has_connect($type = 'qq', $user_id = 0){
    if(!in_array($type, array('qq', 'weibo', 'weixin'))) {
        return  false;
    }
    $user_id = $user_id ? : get_current_user_id();
    switch ($type){
        case 'qq':
            $instance = new OpenQQ($user_id);
            return $instance->isOpenConnected();
        case 'weibo':
            $instance = new OpenWeibo($user_id);
            return $instance->isOpenConnected();
        case 'weixin':
            $instance = new OpenWeiXin($user_id);
            return $instance->isOpenConnected();
    }

    return false;
}
