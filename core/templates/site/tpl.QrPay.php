<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/30 22:41
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

//if(!is_user_logged_in()){
//    wp_die(__('You cannot visit this page without sign in', 'tt'), __('Error: Unknown User', 'tt'), 403);
//}

if(!isset($_GET['oid'])){
    wp_die(__('The required parameters for retrieve a order  are missing', 'tt'), __('Invalid Query Parameters', 'tt'), 500);
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

if($currency != 'cash'){
    wp_die(__('The order does not support cash payment', 'tt'), __('Unsuitable Payment Method', 'tt'), 500);
}

?>
<?php tt_get_header('simple'); ?>
<body class="is-loadingApp site-page qrpay">
<?php load_template(THEME_MOD . '/mod.LogoHeader.php'); ?>
<div id="content" class="wrapper container no-aside">
    <div class="main inner-wrap">
        <section class="processor">
            <ol>
                <li class="done size1of2">
                    <div class="step_line"></div>
                    <div class="step_inner">
                        <i class="icon_step">1</i>
                        <h4><?php _e('Confirm Order', 'tt'); ?></h4>
                    </div>
                </li>
                <li class="current size1of2 no_extra">
                    <div class="step_line"></div>
                    <div class="step_inner">
                        <i class="icon_step">2</i>
                        <h4><?php _e('Accomplish Payment', 'tt'); ?></h4>
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
        <section class="payment">
            <div class="payment-wrapper">
                <h1><?php echo sprintf(__('Payment Amount for Order ID %s is %0.2f', 'tt'), $order_id, $order->order_total_price); ?></h1>
                <p class="introduction"><?php _e('Currently we only support a payment via transfer by scanning qrcode image, when you transfer cash to us, you should leave some important information for remark', 'tt'); ?></p>
                <p class="remark"><?php echo sprintf(__('Your remark is: <strong>%d</strong>', 'tt'), $order->id); ?></p>
                <div class="pay-qr-images row">
                    <div class="qrcode col-md-12 col-sm-6 col-xs-12 alipay">
                        <h4 style="color: #07b6e8;"><?php _e('Alipay', 'tt'); ?></h4>
                        <div class="ali-qr"><img src="<?php echo tt_get_option('tt_site_alipay_qr'); ?>" title="<?php _e('Scan the qrcode image and pay forward to me', 'tt'); ?>"></div>
                        <p><?php _e('Recommended, support auto delivery if you leave right remark', 'tt'); ?></p>
                    </div>
<!--                    <div class="qrcode col-md-6 col-sm-6 col-xs-12 weixin">-->
<!--                        <h4 style="color: #07b6e8;">--><?php //_e('Wechat', 'tt'); ?><!--</h4>-->
<!--                        <div class="wx-qr"><img src="--><?php //echo tt_get_option('tt_site_weixin_qr'); ?><!--" title="--><?php //_e('Scan the qrcode image and pay forward to me', 'tt'); ?><!--"></div>-->
<!--                        <p>--><?php //_e('Auto delivery not supported, we will check and handle the order manually in time', 'tt'); ?><!--</p>-->
<!--                    </div>-->
                </div>
                <div class="pay-qr-images row">
                    <p class="mb20" style="color: red;"><?php printf(__('用户注意, 如果扫码无法输入备注, 请按如下方式操作, 我的收款账户为 <strong>%s</strong>', 'tt'), tt_get_option('tt_alipay_email')); ?></p>
                    <img src="<?php echo THEME_ASSET . '/img/pay-tip.jpg'; ?>">
                </div>
<!--                <div class="contact-qr-images row">-->
<!--                    <div class="col-md-6 col-sm-6 col-xs-12 alipay">-->
<!--                        --><?php //if(tt_get_option('tt_site_alipay_qr')) { ?>
<!--                            <div class="wx-qr"><img src="--><?php //echo tt_get_option('tt_site_alipay_qr'); ?><!--" title="--><?php //_e('Scan the qrcode image and contact with me', 'tt'); ?><!--"></div>-->
<!--                            <p>--><?php //_e('Contact me via alipay', 'tt'); ?><!--</p>-->
<!--                        --><?php //} ?>
<!--                    </div>-->
<!--                    <div class="qrcode col-md-6 col-sm-6 col-xs-12 weixin">-->
<!--                        --><?php //if(tt_get_option('tt_site_weixin_qr')) { ?>
<!--                            <div class="ali-qr"><img src="--><?php //echo tt_get_option('tt_site_weixin_qr'); ?><!--" title="--><?php //_e('Scan the qrcode image and contact with me', 'tt'); ?><!--"></div>-->
<!--                            <p>--><?php //_e('Contact me via weixin', 'tt'); ?><!--</p>-->
<!--                        --><?php //} ?>
<!--                    </div>-->
<!--                </div>-->
                <div class="actions"><a class="btn btn-success btn-wide go-order-detail" href="<?php echo tt_url_for('my_order', $order->id); ?>" target="_blank"><?php _e('Check Order Detail', 'tt'); ?></a></div>
            </div>
        </section>
    </div>
</div>
</body>
<?php tt_get_footer(); ?>