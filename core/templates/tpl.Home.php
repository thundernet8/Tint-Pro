<?php
/**
 * Copyright 2016, WebApproach.net
 * All right reserved.
 *
 * Home Page Template
 *
 * @since 2.0.0
 *
 * @author Zhiyan
 * @date 2016/08/22 22:48
 * @license GPL v3 LICENSE
 */
?>
<?php tt_get_header(); ?>
<div id="content" class="wrapper container right-aside">
    <!-- 顶部Slides + Popular -->
    <section id="mod-show" class="content-section">
        <?php load_mod('mod.HomeSlide'); ?>
        <?php load_mod('mod.HomePopular'); ?>
    </section>
    <!-- 中部置顶分类 -->
    <section id="mod-featuredCats" class="content-section">
        <?php load_mod('mod.FeaturedCats'); ?>
    </section>
    <!-- 近期文章与边栏 -->
    <section id="mod-insideContent" class="content-section">
        <!-- 近期文章列表 -->
        <?php load_mod('mod.HomeLatest'); ?>
        <!-- 边栏 -->
        <?php load_mod('mod.Sidebar'); ?>
    </section>
    <!-- 商品展示 -->
    <section id="mod-sales">
        <?php load_mod('mod.ProductGallery', true); ?>
    </section>
</div>
<?php tt_get_footer(); ?>