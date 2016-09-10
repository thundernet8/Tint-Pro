<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/24 20:37
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/**
 * 加载header模板
 *
 * @since 2.0.0
 *
 * @param string $name 特殊header的名字
 */
function tt_get_header( $name = null ) {
    do_action( 'get_header', $name );

    $templates = array();
    $name = (string) $name;
    if ( '' !== $name ) {
        $templates[] = 'core/modules/mod.Header.' . ucfirst($name) . '.php';
    }

    $templates[] = 'core/modules/mod.Header.php';

    locate_template( $templates, true );
}


/**
 * 加载footer模板
 *
 * @since 2.0.0
 *
 * @param string $name 特殊footer的名字
 */
function tt_get_footer( $name = null ) {
    do_action( 'get_footer', $name );

    $templates = array();
    $name = (string) $name;
    if ( '' !== $name ) {
        $templates[] = 'core/modules/mod.Footer.' . ucfirst($name) . '.php';
    }

    $templates[] = 'core/modules/mod.Footer.php';

    locate_template( $templates, true );
}


/**
 * 加载自定义路径下的Sidebar
 *
 * @since   2.0.0
 *
 * @param   string  $name  特定Sidebar名
 * @return  void
 */
function tt_get_sidebar( $name = null ) {
    do_action( 'get_sidebar', $name );

    $templates = array();
    $name = (string) $name;
    if ( '' !== $name )
        $templates[] = 'core/modules/mod.Sidebar' . ucfirst($name) . '.php';

    $templates[] = 'core/modules/mod.Sidebar.php';

    locate_template( $templates, true );
}
