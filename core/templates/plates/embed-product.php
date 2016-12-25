<div class="embed-product">
    <img src="<?=$this->e($thumb)?>">
    <div class="product-info">
        <h4><a href="<?=$this->e($link)?>"><?=$this->e($name)?></a></h4>
        <div class="price">
            <?php if(!($price > 0)) { ?>
                <span><?php echo __('FREE', 'tt'); ?></span>
            <?php }elseif($currency == 'credit') { ?>
                <i class="tico tico-diamond"></i>
                <span><?php echo (int)$price; ?></span>
            <?php }else{ ?>
                <i class="tico tico-cny"></i>
                <span><?=$this->e($price)?></span>
            <?php } ?>
        </div>
        <div class="product-rating" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
            <div class="star-rating tico-star-o" title="<?php printf(__('Rated %0.1f out of 5', 'tt'), $rating_value); ?>">
                                <span class="tico-star" style="<?php echo sprintf('width:%d', $rating['percent']) . '%;'; ?>">
                                    <?php printf(__('<strong itemprop="ratingValue" class="rating">%0.1f</strong> out of <span itemprop="bestRating">5</span>based on <span itemprop="ratingCount" class="rating">%d</span> customer ratings', 'tt'), $rating_value, $rating_count); ?>
                                </span>
            </div>
        </div>
        <a class="btn btn-success btn-buy" href="<?=$this->e($link)?>"><i class="tico tico-shopping-cart"></i><?php _e('Buy Now', 'tt'); ?></a>
    </div>
</div>