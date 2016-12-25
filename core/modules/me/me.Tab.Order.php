<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/15 00:14
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php global $tt_me_vars; $tt_user_id = $tt_me_vars['tt_user_id']; $tt_order_seq = get_query_var('me_grandchild_route'); ?>
<div class="col col-right order">
    <?php $vm = MeOrderVM::getInstance($tt_order_seq, $tt_user_id); ?>
    <?php if($vm->isCache && $vm->cacheTime) { ?>
        <!-- Order detail cached <?php echo $vm->cacheTime; ?> -->
    <?php } ?>
    <?php $data = $vm->modelData; $order = $data->order; $order_status_text = $data->order_status_text; $pay_method = $data->pay_method; $pay_amount = $data->pay_amount; $pay_content = $data->pay_content; $is_combined = $data->is_combined; $sub_orders = $data->sub_orders; ?>
    <div class="me-tab-box order-tab">
        <div class="tab-content me-order">
            <!-- 订单信息 -->
            <section class="my-order clearfix">
                <header><h2><?php _e('Order Detail', 'tt'); ?></h2></header>
                <div class="info-group clearfix">
                    <div class="row clearfix">
                        <label class="col-md-3 control-label"><?php _e('Order ID', 'tt'); ?></label>
                        <div class="col-md-9"><p class="form-control-static"><?php echo $order->order_id; ?></p></div>
                    </div>
                    <div class="row clearfix">
                        <label class="col-md-3 control-label"><?php _e('Order Content', 'tt'); ?></label>
                        <div class="col-md-9"><p class="form-control-static"><?php echo $order->product_name; ?></p></div>
                    </div>
                    <div class="row clearfix">
                        <label class="col-md-3 control-label"><?php _e('Order Create Time', 'tt'); ?></label>
                        <div class="col-md-9"><p class="form-control-static"><?php echo $order->order_time; ?></p></div>
                    </div>
                    <?php if($order->order_status == OrderStatus::TRADE_SUCCESS) { ?>
                    <div class="row clearfix">
                        <label class="col-md-3 control-label"><?php _e('Order Success Time', 'tt'); ?></label>
                        <div class="col-md-9"><p class="form-control-static"><?php echo $order->order_success_time; ?></p></div>
                    </div>
                    <?php } ?>
                    <div class="row clearfix">
                        <label class="col-md-3 control-label"><?php _e('Payment Status', 'tt'); ?></label>
                        <div class="col-md-9">
                            <p class="form-control-static order-actions">
                                <?php echo $order_status_text; ?>
                                <?php if($order->order_status == OrderStatus::WAIT_PAYMENT) { ?>
                                (<a class="continue-pay" href="javascript:;" data-order-action="continue_pay" data-order-id="<?php echo $order->order_id; ?>" data-order-seq="<?php echo $order->id; ?>" title="<?php _e('Finish the payment', 'tt'); ?>"><?php _e('Continue Pay', 'tt'); ?></a>)
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <label class="col-md-3 control-label"><?php _e('Payment Method', 'tt'); ?></label>
                        <div class="col-md-9"><p class="form-control-static"><?php echo $pay_method; ?></p></div>
                    </div>
                    <div class="row clearfix">
                        <label class="col-md-3 control-label"><?php _e('Payment Amount', 'tt'); ?></label>
                        <div class="col-md-9"><p class="form-control-static"><?php echo $pay_amount; ?></p></div>
                    </div>
                </div>
            </section>
            <!-- 付费内容 -->
            <section class="pay-content clearfix">
                <header><h2><?php _e('Payed Content', 'tt'); ?></h2></header>
                <div class="info-group clearfix">
                    <?php if($is_combined) { ?>
                    <div class="tip"><?php _e('This is a combined order, please visit sub orders below for more specified payed content', 'tt'); ?></div>
                    <ul class="sub-orders">
                        <?php foreach ($sub_orders as $sub_order) { ?>
                        <li class="sub-order"><a class="btn btn-info" href="<?php echo tt_url_for('my_order', $sub_order->id); ?>"><?php printf(__('Order %s', 'tt'), $sub_order->order_id); ?></a></li>
                        <?php } ?>
                    </ul>
                    <?php }else{ ?>
                    <div class="row clearfix">
                        <label class="col-md-3 control-label"><?php _e('Download Content', 'tt'); ?></label>
                        <div class="col-md-9">
                            <div class="form-control-static">
                                <?php if(isset($pay_content['download_content'])){ $download = $pay_content['download_content']; ?>
                                <p><?php printf(__('Download Name: %s'), $download['name']); ?></p>
                                <p><?php printf(__('Download Link: %s'), $download['link']); ?></p>
                                <p><?php printf(__('Download Password: %s'), $download['password']); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <label class="col-md-3 control-label"><?php _e('Payed Content', 'tt'); ?></label>
                        <div class="col-md-9">
                            <div class="form-control-static"><p><?php echo isset($pay_content['pay_content']) ? $pay_content['pay_content'] : ''; ?></p></div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </section>
        </div>
    </div>
</div>