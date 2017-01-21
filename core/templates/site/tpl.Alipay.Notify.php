<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/02 22:07
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

if(!isset($_POST['trade_status'])) {
    wp_die(__('You are acting an illegal visit', 'tt'), __('Illegal Visit', 'tt'), 404); // 防止直接GET访问
}

$alipay_config = tt_get_alipay_config();

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//验证成功
    //商户订单号
    $out_trade_no = htmlspecialchars(trim($_POST['out_trade_no']));
    //支付宝交易号
    $trade_no = trim($_POST['trade_no']);
    //交易状态
    $trade_status = trim($_POST['trade_status']);
    //买家支付宝邮箱账号
    $email = trim($_POST['buyer_email']);
    //物流公司名称
    $logistics_name = '无';
    //物流发货单号
    $invoice_no = '';
    //物流运输类型
    $transport_type = 'EXPRESS';//三个值可选：POST（平邮）、EXPRESS（快递）、EMS（EMS）
    //购买者支付宝帐户
    $buyer_alipay = $_POST['buyer_email'];

    if($_POST['trade_status'] == 'WAIT_BUYER_PAY') {
        //该判断表示买家已在支付宝交易管理中产生了交易记录，但没有付款

        //判断该笔订单是否在商户网站中已经做过处理
        //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
        //如果有做过处理，不执行商户的业务程序

        echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }else if($_POST['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
        //该判断表示买家已在支付宝交易管理中产生了交易记录且付款成功，但卖家没有发货

        //判断该笔订单是否在商户网站中已经做过处理
        //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
        //如果有做过处理，不执行商户的业务程序
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
            if($order && $order->order_status <= 2){
                tt_update_order($out_trade_no, array(
                    'order_status' => 3
                ), array('%d'));
            }
        }
        echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }else if($_POST['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {
        //该判断表示卖家已经发了货，但买家还没有做确认收货的操作

        //判断该笔订单是否在商户网站中已经做过处理
        //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
        //如果有做过处理，不执行商户的业务程序
        $order = tt_get_order($out_trade_no);
        if($order && $order->order_status <= 2){
            tt_update_order($out_trade_no, array(
                'order_status' => 3,
                'trade_no' => $trade_no,
                'user_alipay' => $buyer_alipay
            ), array('%d', '%s', '%s', '%s'));
        }
        echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }else if($_POST['trade_status'] == 'TRADE_FINISHED'||$_POST['trade_status'] == 'TRADE_SUCCESS') {
        //该判断表示买家已经确认收货，这笔交易完成

        //判断该笔订单是否在商户网站中已经做过处理
        //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
        //如果有做过处理，不执行商户的业务程序
        $order = tt_get_order($out_trade_no);
        if($order && $order->order_status <= 3){
            tt_update_order($out_trade_no, array(
                'order_status' => 4,
                'order_success_time' => current_time('mysql'),
                'trade_no' => $trade_no,
                'user_alipay' => $buyer_alipay
            ), array('%d', '%s', '%s', '%s'));
        }
        echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }elseif($_POST['trade_status'] == 'TRADE_CLOSED'){
        $order = tt_get_order($out_trade_no);
        if($order && $order->order_status <= 3){
            tt_update_order($out_trade_no, array(
                'order_status' => 9,
                'order_success_time' => current_time('mysql'), //关闭的交易success_time字段实际为交易关闭时间
                'trade_no' => $trade_no,
                'user_alipay' => $buyer_alipay
            ), array('%d', '%s', '%s', '%s'));
        }
        echo "success";
    }else {
        //其他状态判断
        echo "success";

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult ("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }

    //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
} else {
    //验证失败
    echo "fail";

    //调试用，写文本函数记录程序运行情况是否正常
    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
}