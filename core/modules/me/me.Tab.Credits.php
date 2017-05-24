<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/15 00:16
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_me_vars; $tt_user_id = $tt_me_vars['tt_user_id']; $tt_page = $tt_me_vars['tt_paged']; ?>
<div class="col col-right credits">
    <?php $vm = MeCreditRecordsVM::getInstance($tt_user_id, $tt_page); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- User credit records cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $data = $vm->modelData; $records = $data->records; $max_pages = $data->max_pages; ?>
    <div class="me-tab-box credits-tab">
        <div class="tab-content me-credits">
            <!-- 积分信息 -->
            <section class="credits-info clearfix">
                <header><h2><?php _e('My Credits', 'tt'); ?></h2></header>
                <div class="info-group clearfix">
                    <div class="row clearfix">
                        <label class="col-md-3 control-label"><?php _e('Credits Balance', 'tt'); ?></label>
                        <div class="col-md-9"><p class="form-control-static"><?php echo tt_get_user_credit($tt_user_id); ?></p></div>
                    </div>
                    <div class="row clearfix">
                        <label class="col-md-3 control-label"><?php _e('Credits Consumed', 'tt'); ?></label>
                        <div class="col-md-9"><p class="form-control-static"><?php echo tt_get_user_consumed_credit($tt_user_id); ?></p></div>
                    </div>
                    <div class="row clearfix">
                        <label class="col-md-3 control-label"><?php _e('Daily Sign', 'tt'); ?></label>
                        <div class="col-md-9"><?php echo tt_daily_sign_anchor($tt_user_id); ?></div>
                    </div>
                </div>
            </section>
            <!-- 积分充值 -->
            <section class="credits-charge clearfix">
                <header><h2><?php _e('Credits Charge', 'tt'); ?></h2></header>
                <div class="info-group clearfix">
                    <div class="form-group charge-credits-form">
                        <label><?php printf(__('当前积分兑换比率为：100 积分 = %d 元', 'tt'), tt_get_option('tt_hundred_credit_price', 1)); ?></label>
                        <div class="form-inline">
                            <div class="form-group">
                                <div class="input-group active">
                                    <div class="input-group-addon"><?php _e('Credits*100', 'tt'); ?></div>
                                    <input class="form-control" type="text" name="credits-charge-num" value="10" aria-required="true" required="">
                                </div>
                            </div>
                            <button class="btn btn-inverse" type="submit" id="charge-credits"><?php _e('CHARGE', 'tt'); ?></button>
                        </div>
                        <p class="help-block"><?php _e('积分数以100为单位起计算,请填写整数数值，如填1即表明充值100积分，所需现金根据具体兑换比率计算。', 'tt'); ?></p>
                    </div>
                </div>
            </section>
            <!-- 积分获取方式 -->
            <section class="credits-approach clearfix">
                <header><h2><?php _e('Way to Gain Credits', 'tt'); ?></h2></header>
                <div class="info-group clearfix">
                    <table class="table table-centered table-framed table-striped credit-table">
                        <thead>
                        <tr>
                            <th><?php _e('积分方法', 'tt'); ?></th>
                            <th><?php _e('一次得分', 'tt'); ?></th>
                            <th><?php _e('可用次数', 'tt'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?php _e('注册奖励', 'tt'); ?></td>
                            <td><?php printf(__('%d Credits', 'tt'), tt_get_option('tt_reg_credit', 50)); ?></td>
                            <td><?php _e('Only Once', 'tt'); ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('文章投稿', 'tt'); ?></td>
                            <td><?php printf(__('%d Credits', 'tt'), tt_get_option('tt_rec_post_credit', 50)); ?></td>
                            <td><?php printf(__('%d per Day', 'tt'), tt_get_option('tt_rec_post_num', 5)); ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('评论回复', 'tt'); ?></td>
                            <td><?php printf(__('%d Credits', 'tt'), tt_get_option('tt_rec_comment_credit', 5)); ?></td>
                            <td><?php printf(__('%d per Day', 'tt'), tt_get_option('tt_rec_comment_num', 5)); ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('访问推广', 'tt'); ?></td>
                            <td><?php printf(__('%d Credits', 'tt'), tt_get_option('tt_rec_view_credit', 5)); ?></td>
                            <td><?php printf(__('%d per Day', 'tt'), tt_get_option('tt_rec_view_num', 50)); ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('注册推广', 'tt'); ?></td>
                            <td><?php printf(__('%d Credits', 'tt'), tt_get_option('tt_rec_reg_credit', 30)); ?></td>
                            <td><?php printf(__('%d per Day', 'tt'), tt_get_option('tt_rec_reg_num', 5)); ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('每日签到', 'tt'); ?></td>
                            <td><?php printf(__('%d Credits', 'tt'), tt_get_option('tt_daily_sign_credits', 10)); ?></td>
                            <td><?php printf(__('%d per Day', 'tt'), 1); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            <!-- 积分记录 -->
            <section class="credit-records clearfix">
                <header><h2><?php _e('Credit Records', 'tt'); ?></h2></header>
                <div class="info-group clearfix">
                    <ul class="records-list">
                    <?php foreach ($records as $record) { ?>
                        <li id="record-<?php echo $record->msg_id; ?>"><?php echo $record->msg_date; ?><span class="record-text"><?php echo $record->msg_title; ?></span></li>
                    <?php } ?>
                    </ul>
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
                </div>
            </section>
        </div>
    </div>
</div>