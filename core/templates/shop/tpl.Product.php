<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/13 23:13
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php $product = get_queried_object(); ?>
<?php tt_get_header('shop'); ?>
    <!-- Left Menu -->
    <div class="menu_wrapper" style="margin-top: 55px;">
        <div class="menu">
            <?php //wp_nav_menu( array( 'theme_location' => 'shop-menu', 'container' => '', 'menu_id'=> 'shop-menu-items', 'menu_class' => 'menu-items', 'depth' => '1', 'fallback_cb' => false  ) ); ?>
        </div>
        <div class="icons">
            <a href="javascript:;" data-toggle="modal" data-target="#siteQrcodes" data-trigger="click"><span class="tico tico-qrcode"></span></a>
            <a href="<?php echo 'mailto:' . get_option('admin_email'); ?>"><span class="tico tico-envelope"></span></a>
            <a href="<?php echo tt_url_for('shop_archive') . '/feed'; ?>"><span class="tico tico-rss"></span></a>
        </div>
    </div>
    <div class="wrapper container">
        <?php $vm = ShopProductVM::getInstance($product->ID); ?>
        <?php if($vm->isCache && $vm->cacheTime) { ?>
            <!-- Product cached <?php echo $vm->cacheTime; ?> -->
        <?php } ?>
        <?php global $productdata; $productdata = $vm->modelData; $categories = $productdata->cats; $tags = $productdata->tags; ?>
        <div class="row">
            <!-- Main content in middle -->
            <div class="content-area col-sm-12 col-md-7 col-md-push-2 col-lg-7 col-lg-push-2 boundary-column" id="primary">
                <main id="main-content" role="main">
                    <!-- Breadcrumb -->
                    <nav class="commerce-breadcrumb">
                        <a href="<?php echo home_url(); ?>"><?php _e('HOME', 'tt'); ?></a>
                        <span class="breadcrumb-delimeter">/</span>
                        <a href="<?php echo tt_url_for('shop_archive'); ?>"><?php _e('SHOP', 'tt'); ?></a>
                        <span class="breadcrumb-delimeter">/</span>
                        <?php $cat_breads = array(); foreach($categories as $category) { ?>
                        <?php $category = (array)$category; $cat_breads[] = '<a href="' . $category['permalink'] . '">' . $category['name'] . '</a>'; ?>
                        <?php } ?>
                        <?php echo implode(', ', $cat_breads); ?>
                        <span class="breadcrumb-delimeter">/</span>
                        <?php echo $productdata->title; ?>
                    </nav>
                    <!-- Message box here -->
                    <!-- Product -->
                    <div itemscope itemtype="http://schema.org/Product" id="product-<?php echo $productdata->ID; ?>" class="product type-product">
                        <?php if(isset($productdata->discount[0]) && $productdata->discount[0] < 100){ ?><span class="onsale"><span><?php _e('Sale!', 'tt'); ?></span></span><?php } ?>
                        <!-- Images -->
                        <section class="entry-images">
                            <a href="<?php echo $productdata->thumb; ?>" itemprop="image" class="lightbox-gallery commerce-main-image" data-lightbox="postContentImages"><img src="<?php echo $productdata->thumb; ?>"></a>
                            <!--div class="thumbnails columns-4 row"><a href="" class=""><img src=""></a></div--><!-- TODO -->
                            <div class="view-share">
                                <div class="share">
                                    <div class="share-bar">
                                        <span><?php _e('Share', 'tt'); ?></span>
                                        <a class="share-btn share-weibo" href="<?php echo 'http://service.weibo.com/share/share.php?url=' . $productdata->permalink . '&count=1&title=' . $productdata->title . ' - ' . get_bloginfo('name') . '&pic=' . urlencode($productdata->thumb) . '&appkey=' . tt_get_option('tt_weibo_openkey'); ?>" title="<?php _e('Share to Weibo', 'tt'); ?>" target="_blank"></a>
                                        <a class="share-btn share-qzone" href="<?php echo 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' . $productdata->permalink . '&summary=' . $productdata->excerpt . '&title=' . $productdata->title . ' - ' . get_bloginfo('name') . '&site=' . get_bloginfo('name') . '&pics=' . urlencode($productdata->thumb); ?>" title="<?php _e('Share to QZone', 'tt'); ?>" target="_blank"></a>
                                        <a class="share-btn share-qq" href="<?php echo 'http://connect.qq.com/widget/shareqq/index.html?url=' . $productdata->permalink . '&title=' . $productdata->title . ' - ' . get_bloginfo('name') . '&summary=' . $productdata->excerpt . '&pics=' . urlencode($productdata->thumb) . '&flash=&site=' . get_bloginfo('name') . '&desc='; ?>" title="<?php _e('Share to QQ', 'tt'); ?>" target="_blank"></a>
                                        <a class="share-btn share-weixin" href="javascript: void(0)" data-trigger="focus" data-toggle="popover" data-placement="top" data-container="body" data-content='<?php echo '<img width=120 height=120 src="' . tt_qrcode($productdata->permalink, 120) . '">'; ?>' data-html="1" title="<?php _e('Share to Wechat', 'tt'); ?>" target="_blank"></a>
                                        <!--a class="share-btn share-facebook" href="<?php echo 'https://www.facebook.com/sharer/sharer.php?u=' . $productdata->permalink; ?>" target="_blank"></a-->
                                        <a class="share-btn share-twitter" href="<?php echo 'https://twitter.com/intent/tweet?url=' . $productdata->permalink . '&text=' . $productdata->title; ?>" target="_blank"></a>
                                        <!--a class="share-btn share-googleplus" href="<?php echo 'https://plus.google.com/share?url=' . $productdata->permalink; ?>" target="_blank"></a-->
                                        <a class="share-btn share-email" href="<?php echo 'mailto:?subject=' . $productdata->title . '&body=' . $productdata->permalink; ?>" target="_blank"></a>
                                    </div>
                                </div>
                                <p class="view"><?php printf(__('Product Views: %d', 'tt'), $productdata->views); ?></p>
                            </div>
                        </section>
                        <!-- Summary -->
                        <section class="summary entry-summary">
                            <h1 itemprop="name" class="product_title entry-title"><?php echo $productdata->title; ?></h1>
                            <!-- Rating -->
                            <?php $rating = $productdata->rating; ?>
                            <div class="commerce-product-rating" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
                                <div class="star-rating tico-star-o" title="<?php printf(__('Rated %0.1f out of 5', 'tt'), $rating['value']); ?>">
                                    <span class="tico-star" style="<?php echo sprintf('width:%d', $rating['percent']) . '%;'; ?>">
                                        <?php printf(__('<strong itemprop="ratingValue" class="rating">%0.1f</strong> out of <span itemprop="bestRating">5</span>based on <span itemprop="ratingCount" class="rating">%d</span> customer ratings', 'tt'), $rating['value'], $rating['count']); ?>
                                    </span>
                                </div>
                                <a href="#reviews" class="commerce-review-link" rel="nofollow">(<?php printf(__('<span itemprop="reviewCount" class="count">%d</span> customer reviews', 'tt'), $rating['count']); ?>)</a>
                            </div>
                            <!-- Price -->
                            <div class="commerce-product-price" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                                <p class="price">
                                    <!-- TODO VIP Price -->
                                    <?php if(!($productdata->price > 0)) { ?>
                                        <span class="price price-free"><?php _e('FREE', 'tt'); ?></span>
                                    <?php }elseif(!isset($productdata->discount[0]) || $productdata->discount[0] >= 100){ ?>
                                        <?php echo $productdata->price_icon; ?>
                                        <span class="price"><?php echo $productdata->price; ?></span>
                                    <?php }else{ ?>
                                        <?php echo $productdata->price_icon; ?>
                                        <del><span class="price original-price"><?php echo $productdata->price; ?></span></del>
                                        <ins><span class="price discount-price"><?php $discount_price = $productdata->currency == 'cash' ? sprintf('%0.2f', $productdata->price * $productdata->discount[0] / 100) : intval($productdata->price * $productdata->discount[0] / 100); echo $discount_price; ?></span></ins>
                                    <?php } ?>
                                </p>
                                <meta itemprop="price" content="<?php echo $productdata->price; ?>">
                                <meta itemprop="priceCurrency" content="CNY">
                                <link itemprop="availability" href="http://schema.org/InStock">
                            </div>
                            <!-- Description -->
                            <div class="commerce-product-description" itemprop="description"><p><?php echo $productdata->excerpt; ?></p></div>
                            <!-- Quantity and Action button -->
                            <div class="variations_form cart" data-product-id="<?php echo $productdata->ID; ?>">
                                <div class="single_variation_wrap">
                                    <div class="variations_button">
                                        <?php if($productdata->amount < 1) { ?>
                                        <a href="javascript:;" class="btn btn-info btn-buy" data-buy-action="contact" data-msg-title="<?php _e('SOLD OUT', 'tt'); ?>" data-msg-text="<?php echo __('Please contact the site manager via: ', 'tt') . get_option('admin_email'); ?>"><?php _e('SOLD OUT', 'tt'); ?></a>
                                        <div class="quantity">
                                            <input type="number" step="1" min="1" name="quantity" value="0" title="<?php _e('Qty', 'tt'); ?>" class="input-text qty text" size="4">
                                        </div>
                                        <?php }elseif($productdata->channel == 'taobao') { ?><!-- Link to Taobao -->
                                        <a href="<?php echo $productdata->taobao; ?>" class="btn btn-info btn-buy" data-channel="taobao" target="_blank"><?php _e('Purchase in Taobao', 'tt'); ?></a>
                                        <?php }else{ ?>
                                        <a href="javascript:;" class="btn btn-success btn-buy" data-buy-action="checkout"><?php _e('CHECK OUT', 'tt'); ?></a>
                                        <?php if($productdata->currency=='cash') { ?><a href="javascript:;" class="btn btn-danger btn-buy" data-buy-action="addcart"><?php _e('ADD TO CART', 'tt'); ?></a><?php } ?>
                                        <div class="quantity">
                                            <input type="number" step="1" min="1" name="quantity" value="1" title="<?php _e('Qty', 'tt'); ?>" class="input-text qty text" size="4">
                                        </div>
                                        <?php } ?>
                                        <input type="hidden" name="product_id" value="<?php echo $productdata->ID; ?>">
                                        <input type="hidden" name="product_amount" value="<?php echo $productdata->amount; ?>">
                                    </div>
                                </div>
                            </div>
                            <!-- Product meta -->
                            <div class="product_meta">
                                <span class="sku_wrapper"><?php _e('SKU: ', 'tt'); ?><span class="sku" itemprop="sku"><?php echo $productdata->ID; ?></span></span>
                                <span class="inventory_wrapper"><?php _e('Inventory: ', 'tt'); ?><span class="inventory" itemprop="inventory"><?php echo $productdata->amount; ?></span></span>
                                <span class="posted_in"><?php _e('Categories: ', 'tt'); ?><?php echo implode(', ', $cat_breads); ?></span>
                            </div>
                        </section>
                        <!-- Tabs -->
                        <section class="commerce-tabs wc-tabs-wrapper clearfix">
                            <ul class="nav nav-tabs tabs wc-tabs" id="product-tab" role="tablist"><!-- TODO active corresponding tab with the url hash -->
                                <li class="nav-item description_tab active">
                                    <a class="nav-link" href="javascript:;" data-toggle="tab" data-target="#tab-description" role="tab" aria-controls="tab-description"><?php _e('Product Description', 'tt'); ?></a>
                                </li>
                                <li class="nav-item reviews_tab">
                                    <a class="nav-link" href="javascript:;" data-toggle="tab" data-target="#tab-reviews" role="tab" aria-controls="tab-reviews"><?php printf(__('Reviews (%d)', 'tt'), $productdata->comment_count); ?></a>
                                </li>
                                <!--li class="nav-item history_tab">
                                    <a class="nav-link" href="javascript:;" data-toggle="tab" data-target="#tab-history" role="tab" aria-controls="tab-history"><?php _e('History', 'tt'); ?></a>
                                </li-->
                                <li class="nav-item paycontent_tab">
                                    <a class="nav-link" href="javascript:;" data-toggle="tab" data-target="#tab-paycontent" role="tab" aria-controls="tab-paycontent"><?php _e('Pay Content', 'tt'); ?></a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <!-- Description -->
                                <div class="tab-pane entry-content wc-tab active" id="tab-description" role="tabpanel">
                                    <!--h2><?php _e('Product Description', 'tt'); ?></h2-->
                                    <?php echo $productdata->content; ?>
                                </div>
                                <!-- Reviews -->
                                <div class="tab-pane entry-content wc-tab" id="tab-reviews" role="tabpanel">
                                    <div id="reviews">
                                        <!--h2><?php printf(__('%d reviews for the product', 'tt'), $productdata->comment_count); ?></h2-->
                                        <?php load_mod('shop/mod.Shop.ReplyForm'); ?>
                                        <!-- Comments list -->
                                        <?php load_mod('shop/mod.Shop.Comments'); ?>
                                    </div>
                                </div>
                                <!-- Orders history -->
                                <div class="tab-pane entry-content wc-tab" id="tab-history" role="tabpanel">

                                </div>
                                <!-- Pay content -->
                                <div class="tab-pane entry-content wc-tab" id="tab-paycontent" role="tabpanel">
                                    <div class="paycontent-wrapper">
                                        <?php echo tt_get_product_pay_content($productdata->ID); ?>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Relates -->
                        <?php load_mod('shop/mod.Shop.Relates'); ?>
                    </div>
                </main>
            </div>
            <!-- Right aside -->
            <div class="widget-area col-sm-6 col-md-3 col-md-push-2 col-lg-3 col-lg-push-2" id="secondary" role="complementary">
                <?php load_mod('shop/mod.Shop.Sidebar.Right'); ?>
            </div>
            <!-- Left aside -->
            <div class="widget-area col-sm-6 col-md-2 col-md-pull-10 col-lg-2 col-lg-pull-10" id="tertiary" role="complementary">
                <?php load_mod('shop/mod.Shop.Sidebar.Left'); ?>
            </div>
        </div>
    </div>
<?php tt_get_footer(); ?>