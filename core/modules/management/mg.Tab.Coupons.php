<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/01/07 19:46
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $tt_mg_vars; $tt_user_id = $tt_mg_vars['tt_user_id']; $tt_page = $tt_mg_vars['tt_paged']; ?>
<div class="col col-right coupons">
    <?php $vm = MgCouponsVM::getInstance($tt_page); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Manage coupons cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $data = $vm->modelData; $coupons = $data->coupons; $count = $data->count; $max_pages = $data->max_pages; ?>
    <div class="mg-tab-box coupons-tab">
        <div class="tab-content">
            <!-- 添加优惠码 -->
            <section class="mg-coupon clearfix">
                <header><h2><?php _e('Add Coupon', 'tt'); ?></h2></header>
                <div class="form-group info-group clearfix">
                    <div class="coupon-radios">
                        <?php _e('Coupon Type', 'tt'); ?>
                        <label class="radio-inline">
                            <input type="radio" name="coupon_type" value="once" checked><?php _e('ONCE COUPON', 'tt'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="coupon_type" value="multi"><?php _e('MULTI COUPON', 'tt'); ?>
                        </label>
                    </div>
                </div>
                <div class="form-group info-group clearfix">
                    <div class="form-inline">
                        <div class="form-group">
                            <div class="input-group active">
                                <div class="input-group-addon" style="background-color: #788b90;border-color: #788b90;"><?php _e('Code', 'tt'); ?></div>
                                <input class="form-control" type="text" name="coupon_code" value="" aria-required="true" required>
                            </div>
                            <div class="input-group active">
                                <div class="input-group-addon" style="background-color: #788b90;border-color: #788b90;"><?php _e('Discount', 'tt'); ?></div>
                                <input class="form-control" type="text" name="coupon_discount" value="0.90" aria-required="true" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="form-group">
                            <div class="input-group active">
                                <div class="input-group-addon" style="background-color: #788b90;border-color: #788b90;"><?php _e('Effect Date', 'tt'); ?></div>
                                <input class="form-control" type="datetime-local" name="effect_date" value="<?php echo (new DateTime())->format('Y-m-d\TH:i:s'); ?>" aria-required="true" required>
                            </div>
                            <div class="input-group active">
                                <div class="input-group-addon" style="background-color: #788b90;border-color: #788b90;"><?php _e('Expire Date', 'tt'); ?></div>
                                <input class="form-control" type="datetime-local" name="expire_date" value="" aria-required="true" required>
                            </div>
                        </div>
                        <button class="btn btn-inverse" type="submit" id="add-coupon"><?php _e('ADD', 'tt'); ?></button>
                    </div>
                    <p class="help-block"><?php _e('折扣请填写0~1之间的小数, 并精确到2位小数, 有效期格式为2017-01-01 10:00:00', 'tt'); ?></p>
                </div>
            </section>
            <!-- 优惠码列表 -->
            <section class="mg-coupons clearfix">
                <header><h2><?php _e('Coupons List', 'tt'); ?></h2></header>
                <?php if($count > 0) { ?>
                    <table class="table table-striped table-framed table-centered">
                        <thead>
                        <tr>
                            <th class="th-cid"><?php _e('Coupon Sequence', 'tt'); ?></th>
                            <th class="th-code"><?php _e('Coupon Code', 'tt'); ?></th>
                            <th class="th-type"><?php _e('Coupon Type', 'tt'); ?></th>
                            <th class="th-discount"><?php _e('Coupon Discount', 'tt'); ?></th>
                            <th class="th-status"><?php _e('Coupon Status', 'tt'); ?></th>
                            <th class="th-effect"><?php _e('Coupon Effect Date', 'tt'); ?></th>
                            <th class="th-expire"><?php _e('Coupon Expire Date', 'tt'); ?></th>
                            <th class="th-actions"><?php _e('Actions', 'tt'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $seq = 0; ?>
                        <?php foreach ($coupons as $coupon){ ?>
                            <?php $seq++; ?>
                            <tr id="cid-<?php echo $coupon->id; ?>">
                                <td><?php echo $seq; ?></td>
                                <td><?php echo $coupon->coupon_code; ?></td>
                                <td><?php if($coupon->coupon_type !== 'multi'){_e('ONCE COUPON', 'tt');}else{_e('MULTI COUPON', 'tt');} ?></td>
                                <td><?php echo $coupon->discount_value; ?></td>
                                <td><?php if($coupon->coupon_status == 1){_e('Not Used', 'tt');}else{_e('Used', 'tt');} ?></td>
                                <td><?php echo $coupon->begin_date ?></td>
                                <td><?php echo $coupon->expire_date ?></td>
                                <td>
                                    <div class="coupon-actions">
                                        <a class="delete-coupon" href="javascript:;" data-coupon-action="delete" data-coupon-id="<?php echo $coupon->id; ?>" title="<?php _e('Delete the coupon', 'tt'); ?>"><?php _e('Delete', 'tt'); ?></a>
                                    </div>
                                </td>
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
                <?php }else{ ?>
                    <div class="empty-content">
                        <span class="tico tico-ticket"></span>
                        <p><?php _e('Nothing found here', 'tt'); ?></p>
<!--                        <a class="btn btn-info" href="/">--><?php //_e('Back to home', 'tt'); ?><!--</a>-->
                    </div>
                <?php } ?>
            </section>
        </div>
    </div>
</div>