<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/11 11:23
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

if(!is_user_logged_in()){
    wp_die(__('You cannot visit this page without sign in', 'tt'), __('Error: Unknown User', 'tt'), 403);
}

if(!isset($_GET['_'])){
    wp_die(__('The required resource id is missing', 'tt'), __('Invalid Resource ID', 'tt'), 404);
}

$post_id = (int)tt_decrypt(trim($_GET['_']), tt_get_option('tt_private_token'));

global $origin_post;
$origin_post = get_post($post_id);

if(!$origin_post){
    wp_die(__('The resource id is invalid or resource is not exist', 'tt'), __('Invalid Resource ID', 'tt'), 404);
}

?>
<?php tt_get_header(); ?>
<div id="content" class="wrapper container download-wrapper">
    <?php sload_mod(('banners/bn.Top')); ?>
    <section id="mod-insideContent" class="main-wrap content-section clearfix">
        <!-- 下载页面 -->
        <?php load_mod('mod.Download'); ?>
        <!-- 边栏 -->
        <?php load_mod('mod.Sidebar'); ?>
    </section>
</div>
<?php tt_get_footer(); ?>