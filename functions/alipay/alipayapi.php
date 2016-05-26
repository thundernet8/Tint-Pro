<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="robots" content="noindex,follow">
	<title>正在前往支付宝...</title>
</head>
<?php
/* *
 * 功能：即时到账交易接口接入页
 * 版本：3.3
 * 修改日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************注意*************************
 * 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
 * 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
 * 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
 * 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
 * 如果不想使用扩展功能请把扩展功能参数赋空值。
 */
require_once("alipay.config.php");
require_once("alipay_submit.class.php");
date_default_timezone_set('Asia/Shanghai');
//获取参数
$ali_email = trim(ot_get_option('alipay_email'));
if(empty($ali_email))wp_die('系统发生错误，卖家信息错误,请稍后重试!');
$product_id = $_POST['product_id'];
$product_name = '';
$product_des = '';
if($product_id>0){$product_name = $_POST['order_name'];$product_des = get_post_field('post_excerpt',$product_id);}elseif($product_id==-1){$product_name='开通VIP月费会员';$product_des='VIP月费会员';}elseif($product_id==-2){$product_name='开通VIP季费会员';$product_des='VIP季费会员';}elseif($product_id==-3){$product_name='开通VIP年费会员';$product_des='VIP年费会员';}elseif($product_id==-4){$product_name='积分充值';$product_des=isset($_POST['creditrechargeNum'])?'充值'.$_POST['creditrechargeNum']*(100).'积分':'充值积分';}else{}
$product_url = ($product_id>0)?get_permalink($product_id):get_bloginfo('url');
$order_id = $_POST['order_id'];
if(empty($product_id)||empty($order_id))wp_die('获取商品信息出错,请重试或联系卖家!');
global $wpdb;
$prefix = $wpdb->prefix;
$table = $prefix.'tin_orders';
$order = $wpdb->get_row("select * from ".$table." where product_id=".$product_id." and order_id=".$order_id);
if(!$order)wp_die('获取订单出错,请重试或联系卖家!');
$service = ot_get_option('alipay_service','trade_create_by_buyer'); 
/**************************请求参数**************************/
        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = Ali_URI."/notify.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数        
		//页面跳转同步通知页面路径
        $return_url = Ali_URI."/return.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/        
		//卖家支付宝帐户
        $seller_email = $ali_email;
        //必填        
		//商户订单号
        $out_trade_no = $order->order_id;
        //商户网站订单系统中唯一订单号，必填        
		//订单名称
        $subject = $product_name;
        //必填
        //付款金额//
        $price = $order->order_total_price;
        //必填
        //商品数量//
        $quantity = "1";
        //必填，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品        
		//付款金额
        $total_fee = $order->order_total_price;
        //必填
        $logistics_fee = "0.00";
        //必填，即运费
        //物流类型//
        $logistics_type = "EXPRESS";
        //必填，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
        //物流支付方式//
        $logistics_payment = "SELLER_PAY";
        //必填，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）        
		//订单描述        
		$body = $product_des;
        //商品展示地址
        $show_url = $product_url;
        //需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html        //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1
        //收货人姓名
        $receive_name = $order->user_name;
        //如：张三

        //收货人地址
        $receive_address = $order->user_address;
        //如：XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号

        //收货人邮编
        $receive_zip = $order->user_zip;
        //如：123456

        //收货人电话号码
        $receive_phone = $order->user_phone;
        //如：0571-88158090

        //收货人手机号码
        $receive_mobile = $order->user_cellphone;
        //如：13312341234

/************************************************************/

//构造要请求的参数数组，无需改动
$parameter = array(
		"service" => $service,
		"partner" => trim($alipay_config['partner']),
		"payment_type"	=> $payment_type,
		"notify_url"	=> $notify_url,
		"return_url"	=> $return_url,
		"seller_email"	=> $seller_email,
		"out_trade_no"	=> $out_trade_no,
		"subject"	=> $subject,
		"price"	=> $price,
		"quantity"	=> $quantity,
		"logistics_fee"	=> $logistics_fee,
		"logistics_type"	=> $logistics_type,
		"logistics_payment"	=> $logistics_payment,
		"body"	=> $body,
		"show_url"	=> $show_url,
		"receive_name"	=> $receive_name,
		"receive_address"	=> $receive_address,
		"receive_zip"	=> $receive_zip,
		"receive_phone"	=> $receive_phone,
		"receive_mobile"	=> $receive_mobile,
		"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
);

//建立请求
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
echo '<div style="display:none">'.$html_text.'</div>';

?>
</body>
</html>