<?php
/**
 * Copyright (c) 2014-2017, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/02/18 11:56
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php tt_get_header(); ?>
<div id="content" class="wrapper container full-page bulletin-page">
    <section id="mod-insideContent" class="main-wrap content-section clearfix">
        <!-- 页面内容 -->
        <div id="main" class="main primary post-box" role="main">
            <?php global $post; $vm = SingleBulletinVM::getInstance($post->ID); ?>
            <?php if($vm->isCache && $vm->cacheTime) { ?>
                <!-- Bulletin cached <?php echo $vm->cacheTime; ?> -->
            <?php } ?>
            <?php global $postdata; $postdata = $vm->modelData; ?>
            <div class="bulletin">
                <div class="single-header text-center">
                    <div class="header-wrap">
                        <h1 class="h2"><?php echo $postdata->title; ?></h1>
                        <div class="header-meta">
                            <span class="meta-author"><?php _e('Publisher: ', 'tt'); ?><a class="entry-author" href="<?php echo $postdata->author_url; ?>" target="_blank"><?php echo $postdata->author; ?></a></span>
                            <span class="separator" role="separator"> · </span>
                            <span class="meta-date"><?php _e('Post on: ', 'tt'); ?><time class="entry-date"><?php echo $postdata->datetime; ?></time></span>
                            <span class="separator" role="separator"> · </span>
                            <span class="meta-views"><?php _e('Views: ', 'tt'); ?><?php echo $postdata->views; ?></span>
                        </div>
                    </div>
                </div>
                <div class="single-body">
                    <article class="single-article single-bulletin">
                        <?php echo $postdata->content; apply_filters('the_content', 'content'); // 一些插件(如crayon-syntax-highlighter)将非内容性的钩子(wp_enqueue_script等)挂载在the_content上, 缓存命中时将失效 ?>
                    </article>
                </div>
            </div>
            <!-- 上下篇导航 -->
            <div class="navigation clearfix">
                <div class="col-md-6 post-navi-prev">
                    <span><?php _e('Previous article', 'tt'); ?></span>
                    <h2 class="h5"><?php echo $postdata->prev; ?></h2>
                </div>
                <div class="col-md-6 post-navi-next">
                    <span><?php _e('Next article', 'tt'); ?></span>
                    <h2 class="h5"><?php echo $postdata->next; ?></h2>
                </div>
            </div>
        </div>
    </section>
</div>
<?php tt_get_footer(); ?>