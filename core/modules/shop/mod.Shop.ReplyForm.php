<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/20 19:58
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php
global $productdata;
//TODO 判断是否购买用户,方可评论
?>
<div class="submit-box comment-form clearfix" id="comment-form">
    <?php //comment_id_fields(); ?>
    <input type="hidden" name="comment_post_ID" value="<?php echo $productdata->ID; ?>" id="comment_post_ID">
    <input type="hidden" name="comment_parent" id="comment_parent" value="0">
    <input type="hidden" name="tt_comment_nonce" id="comment_nonce" value="<?php echo wp_create_nonce('tt_comment_nonce'); ?>">
    <?php do_action('comment_form', $productdata->ID); ?>
    <div class="rating-radios">
        <span><?php _e('RATING','tt'); ?></span>
        <label class="radio rating-radio">
            <input type="radio" name="product_star_rating" value="1">
            <span class="tico-star one-star"></span>
        </label>
        <label class="radio rating-radio">
            <input type="radio" name="product_star_rating" value="2">
            <span class="tico-star two-star"></span>
        </label>
        <label class="radio rating-radio">
            <input type="radio" name="product_star_rating" value="3">
            <span class="tico-star three-star"></span>
        </label>
        <label class="radio rating-radio">
            <input type="radio" name="product_star_rating" value="4">
            <span class="tico-star four-star"></span>
        </label>
        <label class="radio rating-radio">
            <input type="radio" name="product_star_rating" value="5" checked>
            <span class="tico-star five-star"></span>
        </label>
    </div>

    <div class="text">
        <?php if(is_user_logged_in()) { ?>
            <textarea name="comment" placeholder="<?php _e('Leave some words...', 'tt'); ?>" id="comment-text" required></textarea>
        <?php }else{ ?>
            <textarea name="comment" placeholder="<?php _e('Signin and Leave some words...', 'tt'); ?>" id="comment-text" required></textarea>
        <?php } ?>
    </div>
    <?php if(is_user_logged_in()) { ?>
        <button class="btn btn-info comment-submit" id="submit" type="submit" title="<?php _e('Submit', 'tt'); ?>"><?php _e('Submit', 'tt'); ?></button>
        <div class="err text-danger"></div>
        <div class="comment-kits">
            <span class="emotion-ico transition" data-emotion="0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="tico tico-smile-o"></i><?php _e('Emotion', 'tt'); ?></span>
            <div class="qqFace dropdown-menu" data-inputbox-id="comment-text"></div>
        </div>
    <?php }else{ ?>
        <button class="btn btn-success comment-submit" id="submit" type="submit" title="<?php _e('Submit', 'tt'); ?>" disabled><?php _e('Submit', 'tt'); ?></button>
    <?php } ?>
</div>