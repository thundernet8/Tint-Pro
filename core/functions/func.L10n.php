<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/17 21:06
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/**
 * 载入语言包
 *
 * @since 2.0.0
 */
function tt_load_languages(){
    load_theme_textdomain( 'tt', THEME_DIR . '/core/languages');
}
add_action( 'after_setup_theme', 'tt_load_languages');

/**
 * 选择本地化语言
 *
 * @since 2.0.0
 */
function tt_theme_l10n(){
    return tt_get_option( 'tt_l10n', 'zh_CN');
}
add_filter('locale','tt_theme_l10n');