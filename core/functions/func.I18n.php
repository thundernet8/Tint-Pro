<?php
/**
 * Copyright 2016, WebApproach.net
 * All right reserved.
 *
 * @author Zhiyan
 * @date 2016/08/17 21:06
 * @license GPL v3 LICENSE
 */
?>

<?php

/**
 * 载入语言包
 *
 * @since 2.0.0
 */
function tt_load_languages(){
    load_theme_textdomain( 'tt', THEME_DIR . '/dashboard/i18n');
}
add_action( 'after_setup_theme', 'tt_load_languages');

/**
 * 选择本地化语言
 *
 * @since 2.0.0
 */
function tt_theme_i18n(){
    return tt_get_option( 'tt_i18n', 'zh_CN');
}
add_filter('locale','tt_theme_i18n');