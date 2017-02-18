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
 * @link https://webapproach.net/tint.html
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
            <?php if($order) { ?>
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
                        <div class="col-md-9">
                            <p class="form-control-static"><?php if($order->product_id > 0) { ?><a href="<?php echo get_permalink($order->product_id); ?>" target="_blank"><?php echo $order->product_name; ?></a><?php }else{ echo $order->product_name; } ?></p>
                        </div>
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
                        <li class="sub-order mt10"><a class="btn btn-info" href="<?php echo tt_url_for('my_order', $sub_order->id); ?>"><?php printf(__('Order %s', 'tt'), $sub_order->order_id); ?></a></li>
                        <?php } ?>
                    </ul>
                    <?php }else{ ?>
                    <div class="row clearfix">
                        <?php if(isset($pay_content['download_content'])){ $downloads = $pay_content['download_content']; ?>
                        <label class="col-md-3 control-label"><?php _e('Download Content', 'tt'); ?></label>
                        <div class="col-md-9">
                            <div class="form-control-static">
                                <?php foreach ($downloads as $download) { ?>
                                <p><?php printf(__('Download Name: %s', 'tt'), $download['name']); ?></p>
                                <p><?php printf(__('Download Link: <a href="%1$s">%1$s</a>', 'tt'), $download['link']); ?></p>
                                <p><?php printf(__('Download Password: %s', 'tt'), $download['password']); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="row clearfix">
                        <?php if(isset($pay_content['pay_content'])) { ?>
                        <label class="col-md-3 control-label"><?php _e('Payed Content', 'tt'); ?></label>
                        <div class="col-md-9">
                            <div class="form-control-static"><p><?php echo $pay_content['pay_content']; ?></p></div>
                        </div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </section>
            <?php }else{ ?>
            <section class="my-order clearfix">
                <header><h2><?php _e('Order Detail', 'tt'); ?></h2></header>
                <div class="empty-content">
                    <span class="tico tico-cart"></span>
                    <p><?php _e('No this order', 'tt'); ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>