<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/02 22:06
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

if(!is_user_logged_in()){
    wp_die(__('You cannot visit this page without sign in', 'tt'), __('Error: Unknown User', 'tt'), 403);
}

$current_user = wp_get_current_user();

$alipay_config = tt_get_alipay_config();

if(empty($alipay_config['partner']) || empty($alipay_config['key'])){
    wp_die(__('Alipay trade interface configuration is incorrect', 'tt'), __('Error: Incorrect Alipay Trade Interface Configuration', 'tt'), 500);
}

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();

if($verify_result) {//验证成功
    //商户订单号
    $out_trade_no = htmlspecialchars(trim($_GET['out_trade_no']));
    //支付宝交易号
    $trade_no = trim($_GET['trade_no']);
    //买家支付宝邮箱账号
    $email = trim($_GET['buyer_email']);
    //物流公司名称
    $logistics_name = '无';
    //物流发货单号
    $invoice_no = '';
    //物流运输类型
    $transport_type = 'EXPRESS';
    //三个值可选：POST（平邮）、EXPRESS（快递）、EMS（EMS）
    //交易状态
    $trade_status = $_GET['trade_status'];
    //购买者支付宝帐户
    $buyer_alipay = $_GET['buyer_email'];
    // 付款额
    $total_fee = sprintf('%0.2f', trim($_GET['total_fee']));

    $product_id = 0;

    if($_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
        //判断该笔订单是否在商户网站中已经做过处理
        //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
        //如果有做过处理，不执行商户的业务程序
        //更新交易状态
        $order = tt_get_order($out_trade_no);
        $product_id = $order->product_id;
        if($order && $order->order_status <= 1){
            tt_update_order($out_trade_no, array(
                'order_status' => 2,
                'trade_no' => $trade_no,
                'user_alipay' => $buyer_alipay
            ), array('%d', '%s', '%s'));
        }

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "send_goods_confirm_by_platform",
            "partner" => trim($alipay_config['partner']),
            "trade_no"	=> $trade_no,
            "logistics_name"	=> $logistics_name,
            "invoice_no"	=> $invoice_no,
            "transport_type"	=> $transport_type,
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestHttp($parameter);
        //解析XML
        //注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
        $doc = new DOMDocument();
        $doc->loadXML($html_text);
        if(!empty($doc->getElementsByTagName( "alipay" )->item(0)->nodeValue) ){
            $order = tt_get_order($out_trade_no);
            $product_id = $order->product_id;
            if($order && $order->order_status <= 2){
                tt_update_order($out_trade_no, array(
                    'order_status' => 3
                ), array('%d'));
            }
        }


    }elseif($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
        //判断该笔订单是否在商户网站中已经做过处理
        $order = tt_get_order($out_trade_no);
        $product_id = $order->product_id;
        if($order && $order->order_status <= 3){
            tt_update_order($out_trade_no, array(
                'order_status' => 4,
                'order_success_time' => current_time('mysql'),
                'trade_no' => $trade_no,
                'user_alipay' => $buyer_alipay
            ), array('%d', '%s', '%s', '%s'));
        }
        //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
        //如果有做过处理，不执行商户的业务程序
    } else {
        $order = tt_get_order($out_trade_no);
        $product_id = $order->product_id;
    }
} else {
    //验证失败
    wp_die(__('Verify return result failed, please contact the site administrator if you have finished your payment', 'tt'), __('Verify Failed', 'tt'));
    exit;
}

$updated_order = tt_get_order($out_trade_no);

?>
<?php tt_get_header('simple'); ?>
<body class="is-loadingApp site-page payresult alipay-return">
<?php load_template(THEME_MOD . '/mod.LogoHeader.php'); ?>
<div id="content" class="wrapper container no-aside">
    <div class="main inner-wrap">
        <section class="processor">
            <ol>
                <li class="size1of2">
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
                <li class="current size1of2 last">
                    <div class="step_line"></div>
                    <div class="step_inner">
                        <i class="icon_step">3</i>
                        <h4><?php _e('Confirm Delivery', 'tt'); ?></h4>
                    </div>
                </li>
            </ol>
        </section>
        <section class="result">
            <div class="result-wrapper">
                <h1><?php echo sprintf(__('Payment for Order ID %s has finished successfully', 'tt'), $updated_order->order_id); ?></h1>
                <p class="order-status"><?php echo sprintf(__('You have payed %f yuan, Currently the order status is: %s', 'tt'), $total_fee, tt_get_order_status_text($updated_order->order_status)); if($updated_order->order_status < 4) _e('<br>You need to go to visit your alipay account and confirm delivery.', 'tt'); ?></p>
                <p><a class="btn btn-success btn-wide go-order-detail" href="<?php echo tt_url_for('my_order', $updated_order->id); ?>" target="_blank"><?php _e('Check Order Detail', 'tt'); ?></a></p>
            </div>
        </section>
    </div>
</div>
</body>
<?php tt_get_footer(); ?>