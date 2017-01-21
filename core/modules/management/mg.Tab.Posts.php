<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/07 19:45
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_mg_vars; $tt_user_id = $tt_mg_vars['tt_user_id']; $tt_page = $tt_mg_vars['tt_paged']; ?>
<div class="col col-right posts">
    <?php $vm = MgPostsVM::getInstance($tt_page); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Manage posts cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $data = $vm->modelData; $posts = $data->posts; $count = $data->count; $max_pages = $data->max_pages; ?>
    <div class="mg-tab-box posts-tab">
        <div class="tab-content">
            <!-- 全站文章列表 -->
            <section class="mg-posts clearfix">
                <header><h2><?php _e('Posts List', 'tt'); ?></h2></header>
                <?php if($count > 0) { ?>
                    <div class="loop-wrap loop-rows posts-loop-rows clearfix">
                        <?php foreach ($posts as $post) { ?>
                            <article id="<?php echo 'post-' . $post['ID']; ?>" class="post type-post status-<?php echo $post['post_status']; ?> <?php echo 'format-' . $post['format']; ?>">
                                <div class="entry-thumb hover-scale">
                                    <a href="<?php echo $post['permalink']; ?>"><img width="175" height="120" src="<?php echo LAZY_PENDING_IMAGE; ?>" data-original="<?php echo $post['thumb']; ?>" class="thumb-medium wp-post-image lazy" alt="<?php echo $post['title']; ?>"></a>
                                    <?php echo $post['category']; ?>
                                </div>
                                <div class="entry-detail">
                                    <header class="entry-header">
                                        <h2 class="entry-title"><?php if(!empty($post['status_string'])){echo '[' . $post['status_string'] . ']&nbsp;'; } ?><a href="<?php echo $post['permalink']; ?>" rel="bookmark"><?php echo $post['title']; ?></a></h2>
                                        <div class="entry-meta entry-meta-1">
                                            <span class="text-muted"><?php _e('Date: ', 'tt'); ?></span><span class="entry-date"><time class="entry-date" datetime="<?php echo $post['datetime']; ?>" title="<?php echo $post['datetime']; ?>"><?php echo $post['time']; ?></time></span>
                                            <span class="text-muted"><?php _e('Author: ', 'tt'); ?></span><span class="entry-author"><a href="<?php echo $post['author_url']; ?>" target="_blank"><?php echo $post['author']; ?></a></span>
                                        </div>
                                    </header>
                                    <div class="entry-excerpt">
                                        <div class="post-excerpt"><?php echo $post['excerpt']; ?></div>
                                    </div>
                                </div>
                                <div class="actions transition">
                                    <?php foreach ($post['actions'] as $action) { ?>
                                        <a class="<?php echo $action['class']; ?>" href="<?php echo $action['url']; ?>" data-post-id="<?php echo $post['ID']; ?>" data-act="<?php echo $action['action']; ?>"><?php echo $action['text']; ?></a>
                                    <?php } ?>
                                </div>
                            </article>
                        <?php } ?>
                    </div>
                    <?php if($max_pages > 1) { ?>
                        <div class="pagination-mini clearfix">
                            <?php if($tt_page == 1) { ?>
                                <div class="col-md-3 prev disabled"><a href="javascript:;"><?php _e('← 上一页', 'tt'); ?></a></div>
                            <?php }else{ ?>
                                <div class="col-md-3 prev"><a href="<?php echo $data->prev_page; ?>"><?php _e('← 上一页', 'tt'); ?></a></div>
                            <?php } ?>
                            <div class="col-md-6 page-nums">
                                <span class="current-page"><?php printf(__('Current Page %d', 'tt'), $tt_page); ?></span>
                                <span class="separator">/</span>
                                <span class="max-page"><?php printf(__('Total %d Pages', 'tt'), $max_pages); ?></span>
                            </div>
                            <?php if($tt_page != $data->max_pages) { ?>
                                <div class="col-md-3 next"><a href="<?php echo $data->next_page; ?>"><?php _e('下一页 →', 'tt'); ?></a></div>
                            <?php }else{ ?>
                                <div class="col-md-3 next disabled"><a href="javascript:;"><?php _e('下一页 →', 'tt'); ?></a></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php }else{ ?>
                    <div class="empty-content">
                        <span class="tico tico-dropbox"></span>
                        <p><?php _e('Nothing found here', 'tt'); ?></p>
                        <a class="btn btn-info" href="/"><?php _e('Back to home', 'tt'); ?></a>
                    </div>
                <?php } ?>
            </section>
        </div>
    </div>
</div>
