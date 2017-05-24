<?php
/**
 * Copyright 2016, WebApproach.net
 * All right reserved.
 *
 * Single Template
 *
 * @since 2.0.0
 *
 * @author Zhiyan
 * @date 2016/08/22 22:07
 * @license GPL v3 LICENSE
 */
?>
<?php tt_get_header(); ?>
<div id="content" class="wrapper container right-aside">
<<<<<<< HEAD
=======
    <?php load_mod(('banners/bn.Top')); ?>
>>>>>>> dev
    <section id="mod-insideContent" class="main-wrap content-section clearfix">
        <!-- 文章 -->
        <?php load_mod('mod.SinglePost'); ?>
        <!-- 边栏 -->
        <?php load_mod('mod.Sidebar'); ?>
    </section>
<<<<<<< HEAD
=======
    <?php load_mod(('banners/bn.Bottom')); ?>
>>>>>>> dev
</div>
<?php tt_get_footer(); ?>