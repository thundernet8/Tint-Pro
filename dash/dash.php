<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/5/27 17:33
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php

/* 后台编辑器预览样式 */
add_editor_style(THEME_ASSET.'/dash/css/editor-preview.css');

/* 后台编辑器强化 */
function tt_add_more_buttons($buttons){
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'styleselect';
	$buttons[] = 'fontselect';
	$buttons[] = 'hr';
	$buttons[] = 'sub';
	$buttons[] = 'sup';
	$buttons[] = 'cleanup';
	$buttons[] = 'image';
	$buttons[] = 'code';
	$buttons[] = 'media';
	$buttons[] = 'backcolor';
	$buttons[] = 'visualaid';
	return $buttons;
}
add_filter("mce_buttons_3", "tt_add_more_buttons");