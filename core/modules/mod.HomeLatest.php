<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/22 20:59
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<div id="main" class="main primary col-md-8" role="main">
    <?php $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1; ?>
    <?php $vm = HomeLatestVM::getInstance($paged); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Latest posts cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <div id="latest-posts" class="block5">
        <aside class="block5-widget">
            <h2 class="widget-title"><?php _e('Latest Posts', 'tt'); ?></h2>
            <?php if($data = $vm->modelData) { $pagination_args = $data->pagination; $latest_posts = $data->latest_posts; ?>
            <div class="block5_widget_content block5_list loop-rows posts-loop-rows">
                <?php if($paged === 1) { ?>
                <?php $sticky_vm = StickysVM::getInstance(); ?>
                    <?php if($sticky_vm->isCache && $sticky_vm->cacheTime) { ?>
                        <!-- Sticky posts cached <?php echo $sticky_vm->cacheTime; ?> -->
                    <?php } ?>
                    <?php if($sticky_data = $sticky_vm->modelData) {
                        $sticky_posts = $sticky_data->sticky_posts; $sticky_count = $sticky_data->count;
                        $latest_posts = $sticky_count > 0 && $sticky_posts ? array_merge($sticky_posts, $latest_posts) : $latest_posts;
                    } ?>
                <?php } ?>
                <?php foreach ($latest_posts as $latest_post) { ?>
                <article id="<?php echo 'post-' . $latest_post['ID']; ?>" class="post type-post status-publish <?php echo 'format-' . $latest_post['format'] . ' ' . $latest_post['sticky_class']; ?>">
                    <div class="entry-thumb hover-scale">
                        <a href="<?php echo $latest_post['permalink']; ?>"><img width="250" height="170" src="<?php echo LAZY_PENDING_IMAGE; ?>" data-original="<?php echo $latest_post['thumb']; ?>" class="thumb-medium wp-post-image lazy" alt="<?php echo $latest_post['title']; ?>" style="max-height: 175px;"></a>
<!--                        <span class="shadow"></span>-->
                        <!--a class="entry-category" href="">XXX</a-->
                        <?php echo $latest_post['category']; ?>
                    </div>
                    <div class="entry-detail">
                        <header class="entry-header">
                            <h2 class="entry-title">
                                <a href="<?php echo $latest_post['permalink']; ?>" rel="bookmark"><?php echo $latest_post['title']; ?></a>
                                <?php if($latest_post['sticky_class'] == 'sticky') { ?>
                                <img class="sticky-ico" src="<?php echo THEME_ASSET . '/img/sticky.png'; ?>" title="<?php _e('Sticky Post', 'tt'); ?>" >
                                <?php } ?>
                            </h2>
                            <div class="entry-meta entry-meta-1">
                                <span class="author vcard"><a class="url" href="<?php echo $latest_post['author_url']; ?>"><?php echo $latest_post['author']; ?></a></span>
                                <span class="entry-date text-muted"><time class="entry-date" datetime="<?php echo $latest_post['datetime']; ?>" title="<?php echo $latest_post['datetime']; ?>"><?php echo $latest_post['time']; ?></time></span>
                                <span class="comments-link text-muted"><i class="tico tico-comments-o"></i><a href="<?php echo $latest_post['permalink'] . '#respond'; ?>"><?php echo $latest_post['comment_count']; ?></a></span>
                            </div>
                        </header>
                        <div class="entry-excerpt">
                            <div class="post-excerpt"><?php echo $latest_post['excerpt']; ?></div>
                        </div>
                    </div>
                </article>
                <?php } ?>
            </div>

            <?php if($pagination_args['max_num_pages'] > 1) { ?>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php $pagination = paginate_links(array(
                        'base' => $pagination_args['base'],
                        'format' => '?paged=%#%',
                        'current' => $pagination_args['current_page'],
                        'total' => $pagination_args['max_num_pages'],
                        'type' => 'array',
                        'prev_next' => true,
                        'prev_text' => '<i class="tico tico-angle-left"></i>',
                        'next_text' => '<i class="tico tico-angle-right"></i>'
                    )); ?>
                    <?php foreach ($pagination as $page_item) {
                        echo '<li class="page-item">' . $page_item . '</li>';
                    } ?>
                </ul>
                <div class="page-nums">
                    <span class="current-page"><?php printf(__('Current Page %d', 'tt'), $pagination_args['current_page']); ?></span>
                    <span class="separator">/</span>
                    <span class="max-page"><?php printf(__('Total %d Pages', 'tt'), $pagination_args['max_num_pages']); ?></span>
                </div>
            </nav>
            <?php } ?>
            <?php } ?>
        </aside>
    </div>
</div>