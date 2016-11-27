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
 * @link https://www.webapproach.net/tint
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

if($order->order_status > 1){
    wp_die(__('The order with order id you specified has been payed', 'tt'), __('Invalid Order', 'tt'), 500);
}

$sub_orders = array();
if($order->parent_id == -1){
    $sub_orders = tt_get_sub_orders($order->id);
}

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

            </section>
            <section class="address">

            </section>
            <section class="pay-method">

            </section>
            <section class="submit">

            </section>
        </div>
    </div>
</body>
<?php tt_get_footer(); ?>