<?php

/**
 * Copyright 2016, Zhiyanblog.com
 * All right reserved.
 *
 * @author Zhiyan
 * @date 16/5/27 17:33
 * @license GPL v3 LICENSE
 */
 
?>

<?php

/* 建立Avatar文件夹 */
function tin_add_avatar_folder() {
	$upload = wp_upload_dir();
	$upload_dir = $upload['basedir'];
	$upload_dir = $upload_dir . '/avatars';
	if (! is_dir($upload_dir)) {
		mkdir( $upload_dir, 0755 );
	}
}
add_action('load-themes.php', 'tin_add_avatar_folder');

/* 后台编辑器预览样式 */
add_editor_style(THEME_ASSET.'/css/editor-style.css');

/* 后台编辑器强化 */
function tin_add_more_buttons($buttons){
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
add_filter("mce_buttons_3", "tin_add_more_buttons");

