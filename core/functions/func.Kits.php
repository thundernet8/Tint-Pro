<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/06/23 18:43
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/**
 * 不可归类工具
 */


/**
 * 根据name获取主题设置(of_get_option别名函数)
 *
 * @since   2.0.0
 *
 * @access  global
 * @param   string  $name     设置ID
 * @param   mixed   $default    默认值
 * @return  mixed   具体设置值
 */
function tt_get_option( $name, $default='' ){
    return of_get_option( $name, $default );
}

// TODO: Utils::function_name -> tt_function_name