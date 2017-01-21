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
<?php global $tt_mg_vars; $tt_user_id = $tt_mg_vars['tt_user_id']; ?>
<div class="col col-right status">
    <?php $vm = MgStatusVM::getInstance(); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Site status cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $info = $vm->modelData; ?>
    <div class="mg-tab-box status-tab">
        <div class="tab-content mg-status">
            <!-- 统计信息 -->
            <section class="statistic-info clearfix">
                <header><h2><?php _e('Site Statistic', 'tt'); ?></h2></header>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Site Open Date', 'tt'); ?></label>
                    <p class="col-md-10"><?php printf(__('%s (Online for %d days)', 'tt'), $info->site_open_date, $info->site_open_days); ?></p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Site Link', 'tt'); ?></label>
                    <p class="col-md-10"><?php printf(__('%d links', 'tt'), $info->links_count); ?></p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Last Modified', 'tt'); ?></label>
                    <p class="col-md-10"><?php printf(__('%s', 'tt'), $info->last_modified); ?></p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Site Users', 'tt'); ?></label>
                    <p class="col-md-10"><?php printf(__('%d Registered users', 'tt'), $info->user_count); ?></p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Site Members', 'tt'); ?></label>
                    <p class="col-md-10"><?php printf(__('%d Payed members (%d monthly VIP, %d annual VIP, %d permanent VIP)', 'tt'), $info->member_count, $info->monthly_member_count, $info->annual_member_count, $info->permanent_member_count); ?></p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Site Posts', 'tt'); ?></label>
                    <p class="col-md-10"><?php printf(__('%d posts in total (%d published, %d drafts, %d pending)', 'tt'), $info->post_count, $info->publish_post_count, $info->draft_post_count, $info->pending_post_count); ?></p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Site Pages', 'tt'); ?></label>
                    <p class="col-md-10"><?php printf(__('%d pages', 'tt'), $info->page_count); ?></p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Sale Products', 'tt'); ?></label>
                    <p class="col-md-10"><?php printf(__('%d products', 'tt'), $info->product_count); ?></p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('User Comments', 'tt'); ?></label>
                    <p class="col-md-10"><?php printf(__('%d comments', 'tt'), $info->comment_count); ?></p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Post Categories', 'tt'); ?></label>
                    <p class="col-md-10"><?php printf(__('%d categories', 'tt'), $info->category_count); ?></p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Post Tags', 'tt'); ?></label>
                    <p class="col-md-10"><?php printf(__('%d tags', 'tt'), $info->tag_count); ?></p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Product Categories', 'tt'); ?></label>
                    <p class="col-md-10"><?php printf(__('%d categories', 'tt'), $info->product_category_count); ?></p>
                </div>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Product Tags', 'tt'); ?></label>
                    <p class="col-md-10"><?php printf(__('%d tags', 'tt'), $info->product_tag_count); ?></p>
                </div>
            </section>
        </div>
    </div>
</div>