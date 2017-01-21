<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/17 19:49
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_me_vars; $tt_user_id = $tt_me_vars['tt_user_id']; $tt_page = $tt_me_vars['tt_paged']; ?>
<div class="col col-right membership">
    <?php $vm = MeMembershipVM::getInstance($tt_user_id, $tt_page); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Membership info cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $data = $vm->modelData; $info = $data->info; $orders = $data->orders; $max_pages = $data->max_pages; ?>
    <div class="me-tab-box member-tab">
        <div class="tab-content me-member">
            <!-- 会员信息 -->
            <section class="member-info clearfix">
                <header><h2><?php _e('Member Info', 'tt'); ?></h2></header>
                <div class="info-group clearfix">
                    <div class="row clearfix">
                        <label class="col-md-3 control-label"><?php _e('Member Type', 'tt'); ?></label>
                        <div class="col-md-9"><p class="form-control-static"><?php echo tt_get_member_type_string($info['member_type']); ?></p></div>
                    </div>
                    <div class="row clearfix">
                        <label class="col-md-3 control-label"><?php _e('Member Status', 'tt'); ?></label>
                        <div class="col-md-9"><p class="form-control-static"><?php echo $info['member_status']; ?></p></div>
                    </div>
                    <div class="row clearfix">
                        <label class="col-md-3 control-label"><?php _e('Join Time', 'tt'); ?></label>
                        <div class="col-md-9"><p class="form-control-static"><?php echo $info['join_time']; ?></p></div>
                    </div>
                    <div class="row clearfix">
                        <label class="col-md-3 control-label"><?php _e('Expire Date', 'tt'); ?></label>
                        <div class="col-md-9"><p class="form-control-static"><?php echo $info['end_time']; ?></p></div>
                    </div>
                </div>
            </section>
            <!-- 开通/续费会员 -->
            <section class="member-join clearfix">
                <header><h2><?php _e('Join Member', 'tt'); ?><small><?php _e('Join, Renew', 'tt'); ?></small></h2></header>
                <div class="info-group clearfix">
                    <div class="form-group join-vip-form">
                        <label class="radio-inline"><input type="radio" name="vip_product_id" value="-1" aria-required="true" required><?php printf(__('Monthly VIP(%d YUAN/Month)', 'tt'), tt_get_vip_price(Member::MONTHLY_VIP)); ?></label>
                        <label class="radio-inline"><input type="radio" name="vip_product_id" value="-2" aria-required="true" required><?php printf(__('Annual VIP(%d YUAN/Year)', 'tt'), tt_get_vip_price(Member::ANNUAL_VIP)); ?></label>
                        <label class="radio-inline"><input type="radio" name="vip_product_id" value="-3" aria-required="true" required checked><?php printf(__('Permanent VIP(%d YUAN)', 'tt'), tt_get_vip_price(Member::PERMANENT_VIP)); ?></label>
                        <button class="btn btn-success" id="joinvip-submit"><?php if($info['is_vip']){_e('Renew VIP', 'tt'); }else{_e('Confirm Join', 'tt'); }; ?></button>
                        <p class="help-block"><?php _e('提示:若已开通会员则按照选择开通的类型自动续费,若会员已到期,则按重新开通计算有效期', 'tt'); ?></p>
                    </div>
                </div>
            </section>
            <!-- 会员订单 -->
            <section class="member-orders clearfix">
                <header><h2><?php _e('Member Records', 'tt'); ?><small><?php _e('Member Orders', 'tt'); ?></small></h2></header>
                <div class="info-group clearfix">
                    <table class="table table-striped table-framed table-centered" width="100%" border="0" cellspacing="0">
                        <thead>
                        <tr>
                            <th scope="col"><?php _e('Order Id', 'tt'); ?></th>
                            <th scope="col"><?php _e('Payment Time', 'tt'); ?></th>
                            <th scope="col"><?php _e('Payment Amount', 'tt'); ?></th>
                            <th scope="col"><?php _e('VIP Type', 'tt'); ?></th>
                            <th scope="col"><?php _e('VIP Order Status', 'tt'); ?></th>
                        </tr>
                        </thead>
                        <tbody class="order-list">
                        <?php foreach ($orders as $order) { ?>
                            <tr>
                                <td><?php echo $order->order_id; ?></td>
                                <td><?php echo $order->order_success_time; ?></td>
                                <td><?php echo '<i class="tico tico-cny"></i>' . $order->order_total_price; ?></td>
                                <td><?php echo tt_get_vip_product_name($order->product_id); ?></td>
                                <td><?php echo tt_get_order_status_text($order->order_status); ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
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