<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/20 20:03
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $productdata; ?>
<section class="related-products">
    <h2><span><?php _e('Related Products', 'tt'); ?></span></h2>
    <ul class="products row">
        <?php $relates = $productdata->relates; ?>
        <?php foreach ($relates as $relate) { ?>
            <?php $relate_rating = $relate['rating']; ?>
            <li class="col-md-3 col-sm-4 col-xs-6 product">
                <a href="<?php echo $relate['permalink']; ?>" title="<?php echo $relate['title']; ?>">
                    <img class="lazy" src="<?php echo LAZY_PENDING_IMAGE; ?>" data-original="<?php echo $relate['thumb']; ?>" alt="<?php echo $relate['title']; ?>" title="<?php echo $relate['title']; ?>">
                    <h3><?php echo $relate['title']; ?></h3>
                    <div class="star-rating tico-star-o" title="<?php printf(__('Rated %0.1f out of 5', 'tt'), $relate_rating['value']); ?>">
                        <span class="tico-star" style="<?php echo sprintf('width:%d', $relate_rating['percent']) . '%;'; ?>">
                            <?php printf(__('<strong itemprop="ratingValue" class="rating">%0.1f</strong> out of <span itemprop="bestRating">5</span>based on <span itemprop="ratingCount" class="rating">%d</span> customer ratings', 'tt'), $relate_rating['value'], $relate_rating['count']); ?>
                        </span>
                    </div>
                    <div class="price">
                        <?php if(!($relate['price'] > 0)) { ?>
                            <span class="price price-free"><?php _e('FREE', 'tt'); ?></span>
                        <?php }elseif(!isset($relate['discount'][0]) || $relate['discount'][0] >= 100){ ?>
                            <span class="price"><?php echo $relate['price_icon']; ?><?php echo $relate['price']; ?></span>
                        <?php }else{ ?>
                            <del><?php echo $relate['price_icon']; ?><span class="price original-price"><?php echo $relate['price']; ?></span></del>
                            <ins><?php echo $relate['price_icon']; ?><span class="price discount-price"><?php $relate_discount_price = $relate['currency'] == 'cash' ? sprintf('%0.2f', $relate['price'] * $relate['discount'][0] / 100) : intval($relate['price'] * $relate['discount'][0] / 100); echo $relate_discount_price; ?></span></ins>
                        <?php } ?>
                    </div>
                </a>
            </li>
        <?php } ?>
    </ul>
</section>