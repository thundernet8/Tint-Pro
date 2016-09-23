<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/22 20:48
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<!-- 热门文章 -->
<?php $vm = PopularVM::getInstance(); ?>
<?php if($vm->isCache && $vm->cacheTime) { ?>
    <!-- Popular posts cached <?php echo $vm->cacheTime; ?> -->
<?php } ?>
<div id="popular" class="col-md-4 block3">
    <aside class="block3-widget">
        <h2 class="block3-title"><?php _e('Most Popular', 'tt'); ?></h2>
        <div class="block3-content">
        <?php if($data = $vm->modelData) { ?>
            <?php foreach ($data as $seq=>$popular) { ?>
            <article class="block-item">
                <div class="entry-thumb">
                    <a href="<?php echo $popular['permalink']; ?>"><img width="90" height="60" src="<?php echo $popular['thumb']; ?>" class="thumb-small wp-post-image" alt="<?php echo $popular['title']; ?>"></a>
                </div>
                <div class="entry-detail">
                    <h2 class="entry-title">
                        <a href="<?php echo $popular['permalink']; ?>"><?php echo $popular['title']; ?></a>
                    </h2>
                    <div class="block-meta">
                        <span class="entry-date"><time class="entry-date published" datetime="<?php echo $popular['datetime']; ?>" title="<?php echo $popular['datetime']; ?>"><?php echo $popular['time']; ?></time></span>
                    </div>
                </div>
            </article>
            <?php } ?>
        <?php } ?>
        </div>
    </aside>
</div>