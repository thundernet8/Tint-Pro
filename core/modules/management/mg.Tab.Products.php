<?php
/**
 * Copyright (c) 2014-2017, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/09 20:59
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_mg_vars; $tt_user_id = $tt_mg_vars['tt_user_id']; $tt_page = $tt_mg_vars['tt_paged']; ?>
<div class="col col-right products">
    <?php $vm = MgProductsVM::getInstance($tt_page); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Manage products cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $data = $vm->modelData; $products = $data->products; $count = $data->count; $max_pages = $data->max_pages; ?>
    <div class="mg-tab-box posts-tab products-tab">
        <div class="tab-content">
            <!-- 全站商品列表 -->
            <section class="mg-posts mg-products clearfix">
                <header><h2><?php _e('Products List', 'tt'); ?></h2></header>
                <?php if($count > 0) { ?>
                    <div class="loop-wrap loop-rows posts-loop-rows clearfix">
                        <?php foreach ($products as $product) { ?>
                            <article id="<?php echo 'product-' . $product['ID']; ?>" class="post product type-product status-<?php echo $product['post_status']; ?>">
                                <div class="entry-thumb hover-scale">
                                    <a href="<?php echo $product['permalink']; ?>"><img width="175" height="120" src="<?php echo LAZY_PENDING_IMAGE; ?>" data-original="<?php echo $product['thumb']; ?>" class="thumb-medium wp-post-image lazy" alt="<?php echo $product['title']; ?>"></a>
                                    <?php echo $product['category']; ?>
                                </div>
                                <div class="entry-detail">
                                    <header class="entry-header">
                                        <h2 class="entry-title"><?php if(!empty($product['status_string'])){echo '[' . $product['status_string'] . ']&nbsp;'; } ?><a href="<?php echo $product['permalink']; ?>" rel="bookmark"><?php echo $product['title']; ?></a></h2>
                                        <div class="entry-meta entry-meta-1">
                                            <span class="text-muted"><?php _e('Date: ', 'tt'); ?></span><span class="entry-date"><time class="entry-date" datetime="<?php echo $product['datetime']; ?>" title="<?php echo $product['datetime']; ?>"><?php echo $product['time']; ?></time></span>
                                            <span class="text-muted"><?php _e('Modified: ', 'tt'); ?></span><span class="entry-date"><time class="entry-date" datetime="<?php echo $product['modified_time']; ?>" title="<?php echo $product['modified_time']; ?>"><?php echo $product['modified_time']; ?></time></span>
                                            <span class="text-muted"><?php _e('Price: ', 'tt'); ?></span><span class="entry-author"><?php echo $product['price_icon'] . $product['price'] . $product['price_unit']; ?></span>
                                            <span class="text-muted"><?php _e('Amounts: ', 'tt'); ?></span><span class="entry-author"><?php echo $product['amount']; ?></span>
                                            <span class="text-muted"><?php _e('Sales: ', 'tt'); ?></span><span class="entry-author"><?php echo $product['sales']; ?></span>
                                        </div>
                                    </header>
                                    <div class="entry-excerpt">
                                        <div class="post-excerpt"><?php echo $product['excerpt']; ?></div>
                                    </div>
                                </div>
                                <div class="actions transition">
                                    <?php foreach ($product['actions'] as $action) { ?>
                                        <a class="<?php echo $action['class']; ?>" href="<?php echo $action['url']; ?>" data-product-id="<?php echo $product['ID']; ?>" data-act="<?php echo $action['action']; ?>"><?php echo $action['text']; ?></a>
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