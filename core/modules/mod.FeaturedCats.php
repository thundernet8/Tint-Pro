<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/22 21:08
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<div class="row">
    <?php for($cat_seq=1; $cat_seq<=3; ++$cat_seq): ?>
    <?php $vm = FeaturedCategoryVM::getInstance($cat_seq); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
    <!-- Featured category <?php echo $cat_seq; ?> cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <div class="col-md-4">
        <div class="<?php echo 'featured-cat featured-cat' . $cat_seq . ' block4'; ?>">
            <aside class="block4-widget">
                <?php if($data = $vm->modelData) { $i = 0; $cat_info = $data->cat; $cat_posts = $data->cat_posts; ?>
                <h2 class="h3 widget-title"><span><a href="<?php echo $cat_info['cat_link']; ?>"><?php echo $cat_info['cat_name']; ?></a></span></h2>
                <div class="block3_widget_content">
                    <?php foreach ($cat_posts as $seq=>$cat_post) { $i += 1; ?>
                    <?php if($i===1) : ?>
                    <article class="block-item-large mb20">
                        <div class="entry-thumb hover-overlay">
                            <a href="<?php echo $cat_post['permalink']; ?>"><img width="375" height="250" src="<?php echo LAZY_PENDING_IMAGE; ?>" data-original="<?php echo $cat_post['thumb']; ?>" class="thumb-medium wp-post-image lazy" alt="<?php echo $cat_post['title']; ?>"></a>
<!--                            <span class="shadow"></span>-->
                        </div>
                        <div class="entry-detail">
                            <header class="entry-header">
                                <h2 class="h4 entry-title"><a href="<?php echo $cat_post['permalink']; ?>" rel="bookmark"><?php echo $cat_post['title']; ?></a></h2>
                                <div class="entry-meta entry-meta-1">
                                    <span class="author vcard"><a class="url" href="<?php echo $cat_post['author_url']; ?>"><?php echo $cat_post['author']; ?></a></span>
                                    <span class="entry-date text-muted"><time class="entry-date" datetime="<?php echo $cat_post['datetime']; ?>" title="<?php echo $cat_post['datetime']; ?>"><?php echo $cat_post['time']; ?></time></span>
                                    <span class="comments-link text-muted"><i class="tico tico-comments-o"></i><a href="<?php echo $cat_post['permalink'] . '#respond'; ?>"><?php echo $cat_post['comment_count']; ?></a></span>
                                </div>
                            </header>
                            <div class="entry-excerpt mt15">
                                <div class="post-excerpt"><?php echo $cat_post['excerpt']; ?></div>
                            </div>
                        </div>
                    </article>
                    <?php else : ?>
                    <article class="block-item mb20">
                        <div class="entry-thumb">
                            <a href="<?php echo $cat_post['permalink']; ?>"><img width="100" height="75" src="<?php echo LAZY_PENDING_IMAGE; ?>" data-original="<?php echo $cat_post['thumb']; ?>" class="thumb-small wp-post-image lazy" alt="<?php echo $cat_post['title']; ?>"></a>
<!--                            <span class="shadow"></span>-->
                        </div>
                        <div class="entry-detail">
                            <h2 class="h5 entry-title"><a href="<?php echo $cat_post['permalink']; ?>"><?php echo $cat_post['title']; ?></a></h2>
                            <div class="block-meta">
                                <span class="entry-date text-muted"><time class="entry-date" datetime="<?php echo $cat_post['datetime']; ?>" title="<?php echo $cat_post['datetime']; ?>"><?php echo $cat_post['time']; ?></time></span>
                            </div>
                        </div>
                    </article>
                    <?php endif; ?>
                    <?php } ?>
                <?php } ?>
                </div>
            </aside>
        </div>
    </div>
    <?php endfor; ?>
</div>