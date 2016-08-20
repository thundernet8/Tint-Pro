<?php
/**
 * Copyright 2016, WebApproach.net
 * All right reserved.
 *
 * @author Zhiyan
 * @date 2016/08/20 23:25
 * @license GPL v3 LICENSE
 */
?>

<?php

require_once '../classes/class.Avatar.php';

/**
 * 获取头像
 *
 * @since   2.0.0
 * @param   int | string | object   $id_or_email    用户ID或Email或用户实例对象
 * @param   int | string    $size                   头像尺寸
 * @return  string
 */
function tt_get_avatar($id_or_email, $size='medium'){
    //TODO: hit cache first
    //like return Cached((new Avatar($id_or_email, $size))) ? Cached((new Avatar($id_or_email, $size))) : (new Avatar($id_or_email, $size))->getAvatar();
    return (new Avatar($id_or_email, $size))->getAvatar();
}
