<?php

/**
 * Copyright 2016, Zhiyanblog.com
 * All right reserved.
 *
 * @author Zhiyan
 * @date 16/6/23 18:43
 * @license GPL v3 LICENSE
 */
 
?>
<?php

/**
 * 不可归类工具
 */


/**
 * 根据ID获取主题设置(of_get_option别名函数)
 *
 * @since   2.0.0
 *
 * @access  global
 * @param   string  $id     设置ID
 * @param   mixed   $default    默认值
 * @return  mixed   具体设置值
 */
function tt_get_option($id, $default){
    return of_get_option($id, $default);
}