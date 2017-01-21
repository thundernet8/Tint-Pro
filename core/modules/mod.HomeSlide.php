<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/20 23:35
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<!-- 幻灯 -->
<?php $vm = SlideVM::getInstance(); ?>
<?php if($vm->isCache && $vm->cacheTime) { ?>
<!-- Slide cached <?php echo $vm->cacheTime; ?> -->
<?php } ?>
<div id="slider" class="col-md-8 block2">
    <div class="slides-wrap">
        <?php if($data = $vm->modelData) { ?>
            <ul>
                <?php foreach ($data as $seq=>$slide) { ?>
                    <li class="slider-item">
                        <div class="slider-thumb">
                            <a href="<?php echo $slide['permalink']; ?>">
                                <img width="700" height="350" src="<?php echo $slide['thumb']; ?>" class="thumb-large wp-post-image" alt="<?php echo $slide['title']; ?>">
                            </a>
                        </div>
                        <div class="slider-content">
                            <span class="meta-category"><?php echo $slide['category']; ?></span>
                            <h2 class="h3 slider-title">
                                <a href="<?php echo $slide['permalink']; ?>"><?php echo $slide['title']; ?></a>
                            </h2>
                            <div class="slider-meta mt5">
                                <div class="entry-meta entry-meta-1">
                                    <span class="author vcard"><a class="url" href="<?php echo $slide['author_url']; ?>"><?php echo $slide['author']; ?></a></span>
                                    <span class="entry-date">
                            <?php //TODO abbr instead time tag ?>
                                        <time class="entry-date published updated" datetime="<?php echo $slide['datetime']; ?>" title="<?php echo $slide['datetime']; ?>"><?php echo $slide['time']; ?></time>
                        </span>
                                    <span class="comments-link"><i class="tico tico-comments-o"></i><a href="<?php echo $slide['permalink'] . '#respond'; ?>"><?php echo $slide['comment_count']; ?></a></span>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
</div>