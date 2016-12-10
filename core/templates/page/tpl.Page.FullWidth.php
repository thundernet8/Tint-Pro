<?php
/**
 * Template Name: 全宽页面
 *
 * Copyright 2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @author Zhiyan
 * @date 2016/08/21 23:51
 * @license GPL v3 LICENSE
 */
?>
<?php tt_get_header(); ?>
    <div id="content" class="wrapper container full-page">
        <section id="mod-insideContent" class="main-wrap content-section clearfix">
            <!-- 页面 -->
            <?php load_mod('mod.SinglePage'); ?>
        </section>
    </div>
<?php tt_get_footer(); ?>