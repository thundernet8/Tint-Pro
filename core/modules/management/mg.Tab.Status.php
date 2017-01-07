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
 * @link https://www.webapproach.net/tint
 */
?>
<?php global $tt_mg_vars; $tt_user_id = $tt_mg_vars['tt_user_id']; ?>
<div class="col col-right status">
    <?php $vm = MgStatusVM::getInstance(); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Site status cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $info = $vm->modelData; ?>
    <div class="mg-tab-box setting-tab">
        <div class="tab-content mg-status">
            <!-- 统计信息 -->
            <section class="statistic-info clearfix">
                <header><h2><?php _e('Site Statistic', 'tt'); ?></h2></header>
                <div class="form-group info-group clearfix">
                    <label class="col-md-2 control-label"><?php _e('Site Open Date', 'tt'); ?></label>
                    <p class="col-md-10"><?php printf(__('%s (Online for %d days)', 'tt'), $info->site_open_date, $info->site_open_days); ?></p>
                </div>
            </section>
        </div>
    </div>
</div>