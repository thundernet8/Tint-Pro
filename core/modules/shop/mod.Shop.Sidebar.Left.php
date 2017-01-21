<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/20 20:05
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $productdata; $catIDs = $productdata->catIDs; $rand_products = $productdata->rands; ?>
<?php $tool_vm = ShopHeaderSubNavVM::getInstance(); $data = $tool_vm->modelData; $all_categories = $data->categories; $all_tags = $data->tags;?>
<!-- Product category -->
<aside class="commerce-widget widget_product_categories">
    <h3 class="widget-title"><?php _e('Categories', 'tt'); ?></h3>
    <ul class="widget-content category-list">
        <?php foreach ($all_categories as $category) { ?>
            <li class="<?php if(in_array($category['ID'], $catIDs)){echo 'tico-angle cat-item active';}else{echo 'tico-angle cat-item';}; ?>">
                <a class="product-cat cat-link" href="<?php echo $category['permalink']; ?>" title=""><?php echo $category['name']; ?></a>
            </li>
        <?php } ?>
    </ul>
</aside>
<!-- Product list -->
<aside class="commerce-widget widget_products">
    <h3 class="widget-title"><?php _e('Products', 'tt'); ?></h3>
    <ul class="widget-content product-list">
        <?php foreach ($rand_products as $rand_product) { ?>
            <li>
                <a href="<?php echo $rand_product['permalink']; ?>" title="<?php echo $rand_product['title']; ?>">
                    <img class="lazy" src="<?php echo LAZY_PENDING_IMAGE; ?>" data-original="<?php echo $rand_product['thumb']; ?>">
                    <span class="product-title"><?php echo $rand_product['title']; ?></span>
                </a>
                <?php if(!($rand_product['price'] > 0)) { ?>
                    <div class="price price-free"><?php _e('FREE', 'tt'); ?></div>
                <?php }elseif(!isset($rand_product['discount'][0]) || $rand_product['discount'][0] >= 100){ ?>
                    <div class="price"><?php echo $rand_product['price_icon']; ?><?php echo $rand_product['price']; ?></div>
                <?php }else{ ?>
                    <del><?php echo $rand_product['price_icon']; ?><span class="price original-price"><?php echo $rand_product['price']; ?></span></del>
                    <ins><?php echo $rand_product['price_icon']; ?><span class="price discount-price"><?php $discount_price = $rand_product['currency'] == 'cash' ? sprintf('%0.2f', $rand_product['price'] * $rand_product['discount'][0] / 100) : intval($rand_product['price'] * $rand_product['discount'][0] / 100); echo $discount_price; ?></span></ins>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
</aside>
<!-- Product tags -->
<aside class="commerce-widget widget_product_tag_cloud">
    <h3 class="widget-title"><?php _e('Product Tags', 'tt'); ?></h3>
    <div class="widget-content tagcloud">
        <?php foreach ($all_tags as $tag) { ?>
            <a class="product-tag tag-link" href="<?php echo $tag['permalink']; ?>" title=""><?php echo $tag['name']; ?></a>
        <?php } ?>
    </div>
</aside>