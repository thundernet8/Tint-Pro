<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/27 17:20
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

if(!is_user_logged_in()){
    wp_die(__('You cannot visit this page without sign in', 'tt'), __('Error: Unknown User', 'tt'), 403);
}

if(!isset($_GET['oid']) || !isset($_GET['spm'])){
    wp_die(__('The required parameters for checking out order are missing', 'tt'), __('Invalid Checkout Parameters', 'tt'), 500);
}

if(!wp_verify_nonce(htmlspecialchars($_GET['spm']), 'checkout')){
    wp_die(__('You are acting an illegal visit', 'tt'), __('Illegal Visit', 'tt'), 500);
}

$order_id = htmlspecialchars($_GET['oid']);
$order = tt_get_order($order_id);
if(!$order){
    wp_die(__('The order with order id you specified is not existed', 'tt'), __('Invalid Order', 'tt'), 500);
}

if(in_array($order->order_status, array(2, 3, 4))){
    wp_die(__('The order with order id you specified has been payed', 'tt'), __('Invalid Order', 'tt'), 500);
}

$currency = $order->order_currency;

$sub_orders = array();
if($order->parent_id == -1){
    $sub_orders = tt_get_sub_orders($order->id);
}

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

?>
<?php tt_get_header('simple'); ?>
<body class="is-loadingApp site-page checkout">
<?php load_template(THEME_MOD . '/mod.LogoHeader.php'); ?>
    <div id="content" class="wrapper container no-aside">
        <div class="main inner-wrap">
            <section class="processor">
                <ol>
                    <li class="current size1of2">
                        <div class="step_line"></div>
                        <div class="step_inner">
                            <i class="icon_step">1</i>
                            <h4><?php _e('Confirm Order', 'tt'); ?></h4>
                        </div>
                    </li>
                    <li class="size1of2 no_extra">
                        <div class="step_line"></div>
                        <div class="step_inner">
                            <i class="icon_step">2</i>
                            <h4><?php _e('Payment Accomplish', 'tt'); ?></h4>
                        </div>
                    </li>
                    <li class="size1of2 last">
                        <div class="step_line"></div>
                        <div class="step_inner">
                            <i class="icon_step">3</i>
                            <h4><?php _e('Confirm Delivery', 'tt'); ?></h4>
                        </div>
                    </li>
                </ol>
            </section>
            <section class="orders">
                <h3><?php _e('Order Details', 'tt'); ?></h3>
                <?php $order_list = count($sub_orders) ? $sub_orders : array($order); $total_price = 0; ?>
                <ul class="order-items">
                    <?php if(count($order_list)) { ?>
                        <li class="order-head clearfix" id="">
                            <span class="col-md-2 th th-thumb"></span>
                            <span class="col-md-5 th th-name"><?php _e('Product Name', 'tt'); ?></span>
                            <span class="col-md-1 th th-price"><?php _e('Single Price', 'tt'); ?></span>
                            <span class="col-md-1 th th-quantity"><?php _e('Quantity', 'tt'); ?></span>
                            <span class="col-md-1 th th-total-price"><?php _e('Gross', 'tt'); ?></span>
                            <span class="col-md-2 th th-action"><?php _e('Actions', 'tt'); ?></span>
                        </li>
                    <?php } ?>
                    <?php foreach ($order_list as $order_item){ ?>
                    <?php $total_price += $order_item->order_total_price; ?>
                    <li class="order-item clearfix" id="<?php echo 'order-' . $order_item->id; ?>" data-order-id="<?php echo $order_item->order_id; ?>">
                        <span class="col-md-2 td td-thumb"><img class="thumbnail" src="<?php echo tt_get_thumb($order_item->product_id, array('width' => 100, 'height' => 100, 'str' => 'thumbnail')); ?>" ></span>
                        <span class="col-md-5 td td-name"><h2><?php echo $order_item->product_name; ?></h2></span>
                        <span class="col-md-1 td td-price">
                            <?php if(($order_item->order_price * $order_item->order_quantity - $order_item->order_total_price) > 0.01) { ?>
                            <span class="td-inner td-origin-price"><del><?php if($order_item->order_currency == 'cash') { ?><i class="tico tico-cny"></i><?php printf('%0.2f', $order_item->order_price); ?><?php }else{ ?><i class="tico tico-diamond"></i><?php echo absint($order_item->order_price); ?><?php } ?></del></span>
                            <span class="td-inner td-discount-price"><?php if($order_item->order_currency == 'cash') { ?><i class="tico tico-cny"></i><?php printf('%0.2f', $order_item->order_total_price / $order_item->order_quantity); ?><?php }else{ ?><i class="tico tico-diamond"></i><?php echo absint($order_item->order_total_price / $order_item->order_quantity); ?><?php } ?></span>
                            <?php }else{ ?>
                            <span class="td-inner td-origin-price"><?php if($order_item->order_currency == 'cash') { ?><i class="tico tico-cny"></i><?php printf('%0.2f', $order_item->order_price); ?><?php }else{ ?><i class="tico tico-diamond"></i><?php echo absint($order_item->order_price); ?><?php } ?></span>
                            <?php } ?>
                        </span>
                        <span class="col-md-1 td td-quantity"><?php echo 'x ' . absint($order_item->order_quantity); ?></span>
                        <span class="col-md-1 td td-total-price"><?php if($order_item->order_currency == 'cash') { ?><i class="tico tico-cny"></i><?php printf('%0.2f', $order_item->order_total_price); ?><?php }else{ ?><i class="tico tico-diamond"></i><?php echo absint($order_item->order_total_price); ?><?php } ?></span>
                        <span class="col-md-2 td td-view"><a class="product-link" href="<?php echo get_permalink($order_item->product_id); ?>" target="_blank"><?php _e('View Details', 'tt'); ?></a></span>
                    </li>
                    <?php } ?>
                </ul>
                <div class="order-memo">
                    <label class="memo-name" for="memo-textarea"><?php _e('Message sent to vendor: ', 'tt'); ?></label>
                    <div class="memo-detail"><textarea id="memo-textarea" name="order-memo" placeholder="<?php _e('Optional, please leave some remind note for this deal to the vendor', 'tt'); ?>"></textarea></div>
                </div>
            </section>
            <section class="address clearfix">
                <h3><?php _e('Address Info', 'tt'); ?></h3>
                <?php $addresses = tt_get_addresses(); ?>
                <?php if(count($addresses)) { ?>
                    <?php $default_address_id = (int)get_user_meta($user_id, 'tt_default_address_id', true); ?>
                    <ul class="address-list row">
                        <?php foreach ($addresses as $address) { ?>
                        <li class="<?php if($default_address_id == $address->id) echo 'address col-md-3 active'; else echo 'address col-md-3'; ?>" id="<?php echo 'address-' . $address->id; ?>" data-address-id="<?php echo $address->id; ?>">
                            <div class="inner">
                                <div class="addr-hd"><?php printf(__('Receiver: %s', 'tt'), $address->user_name); ?></div>
                                <div class="addr-bd">
                                    <p class="email"><?php echo $address->user_email; ?></p>
                                    <?php if($address->user_cellphone) { ?>
                                    <p class="cellphone"><?php echo $address->user_cellphone; ?></p>
                                    <?php } ?>
                                    <?php if($address->user_address) { ?>
                                        <p class="location"><?php echo $address->user_address; ?><?php if($address->user_zip) echo ', ' . $address->user_zip; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </li>
                        <?php } ?>
                    </ul>
                    <a class="add-new-address" data-show-sel=".address-input-group" data-hide-sel=".address-list"><?php _e('Use New Address', 'tt'); ?></a>
                    <?php $address_input_class = 'address-input-group row'; ?>
                <?php }else{ ?>
                    <?php $address_input_class = 'address-input-group row active'; ?>
                <?php } ?>
                    <div class="<?php echo $address_input_class; ?>">
                        <!-- Name Email Phone Address Zip -->
                        <div class="input-group col-md-3">
                            <span class="input-group-addon required" id="receiver-name"><?php _e('Name', 'tt'); ?></span>
                            <input type="text" class="form-control" name="receiver-name" aria-describedby="receiver-name" value="<?php echo $current_user->display_name; ?>" required>
                        </div>
                        <div class="input-group col-md-5">
                            <span class="input-group-addon required" id="receiver-email"><?php _e('Email', 'tt'); ?></span>
                            <input type="text" class="form-control" name="receiver-email" aria-describedby="receiver-email" value="<?php echo $current_user->user_email; ?>" required>
                        </div>
                        <div class="input-group col-md-4">
                            <span class="input-group-addon" id="receiver-phone"><?php _e('Phone', 'tt'); ?></span>
                            <input type="text" class="form-control" name="receiver-phone" aria-describedby="receiver-phone">
                        </div>
                        <div class="input-group col-md-9">
                            <span class="input-group-addon" id="receiver-address"><?php _e('Address', 'tt'); ?></span>
                            <input type="text" class="form-control" name="receiver-address" aria-describedby="receiver-address">
                        </div>
                        <div class="input-group col-md-3">
                            <span class="input-group-addon" id="receiver-zip"><?php _e('Zip', 'tt'); ?></span>
                            <input type="text" class="form-control" name="receiver-zip" aria-describedby="receiver-zip">
                        </div>
                    </div>
            </section>
            <?php if($currency=='cash' && tt_get_option('tt_pay_channel', 'alipay')=='alipay' && tt_get_option('tt_alipay_email') && tt_get_option('tt_alipay_partner')) { ?>
            <section class="pay-method">
                <h3><?php _e('Pay Methods', 'tt'); ?></h3>
                <ul class="pay-method-list">
                    <li>
                        <label class="radio alipay-radio">
                            <input type="radio" name="pay_method" value="alipay" checked>
                            <i class="alipay-logo"></i>
                        </label>
                    </li>
                </ul>
            </section>
            <?php }elseif($currency!='cash'){ ?>
                <section class="pay-method">
                    <h3><?php _e('Pay Methods', 'tt'); ?></h3>
                    <ul class="pay-method-list">
                        <li>
                            <label class="radio credit-radio">
                                <input type="radio" name="pay_method" value="credit" checked>
                                <i class="credit-logo"></i>
                            </label>
                        </li>
                    </ul>
                </section>
            <?php } ?>
            <section class="goto-pay clearfix">
                <div class="pay-wrapper pull-right">
                    <?php if($currency == 'cash') { ?>
                    <div class="input-group active">
                        <span><?php _e('Coupon: ', 'tt'); ?></span>
                        <input type="text" class="form-control" name="coupon">
                        <span class="input-group-btn">
                            <button class="btn btn-default" id="apply-coupon" data-order-id="<?php echo $order->order_id; ?>" type="button"><?php _e('Apply', 'tt'); ?></button>
                        </span>
                    </div>
                    <?php } ?>
                    <div class="order-realPay">
                        <span class="realPay-title"><?php _e('Real Pay: ', 'tt'); ?></span>
                        <?php if($currency == 'cash'){ ?><i class="tico tico-cny"></i><?php }else{ ?><i class="tico tico-diamond"></i><?php } ?>
                        <span class="real-price"><?php echo $currency == 'cash' ? sprintf('%0.2f', $total_price) : intval($total_price); ?></span>
                    </div>
                </div>
                <a role="button" title="<?php _e('Submit Order', 'tt'); ?>" class="btn btn-wide btn-danger btn-submit pull-right" id="submit-order" data-order-id="<?php echo $order->order_id; ?>"><?php _e('Submit Order', 'tt'); ?></a>
            </section>
        </div>
    </div>
</body>
<?php tt_get_footer(); ?>