<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/13 23:13
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php $product = get_queried_object(); ?>
<?php $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1; ?>
<?php tt_get_header('shop'); ?>
    <!-- Left Menu -->
    <div class="menu_wrapper" style="margin-top: 55px;">
        <div class="menu">
            <?php wp_nav_menu( array( 'theme_location' => 'shop-menu', 'container' => '', 'menu_id'=> 'shop-menu-items', 'menu_class' => 'menu-items', 'depth' => '1', 'fallback_cb' => false  ) ); ?>
        </div>
        <div class="icons">
            <a href="javascript:;" data-toggle="modal" data-target="#siteQrcodes" data-trigger="click"><span class="tico tico-qrcode"></span></a>
            <a href="<?php echo 'mailto:' . get_option('admin_email'); ?>"><span class="tico tico-envelope"></span></a>
            <a href="<?php echo tt_url_for('shop_archive') . '/feed'; ?>"><span class="tico tico-rss"></span></a>
        </div>
    </div>
    <div class="wrapper">
        <?php //$vm = ShopProductVM::getInstance($paged, $category->term_id); ?>
        <?php //if($vm->isCache && $vm->cacheTime) { ?>
            <!-- Product cached <?php //echo $vm->cacheTime; ?> -->
        <?php //} ?>
        <div class="content" style="min-height: 500px;">

        </div>
    </div>
<?php tt_get_footer(); ?>