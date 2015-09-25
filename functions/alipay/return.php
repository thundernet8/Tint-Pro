<?php
/* * 
 * 功能：支付宝页面跳转同步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
 */
require_once("alipay.config.php");
require_once("alipay_notify.class.php");
require_once("alipay_submit.class.php");
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
if(!is_user_logged_in()){
	wp_die('请先登录系统');
}
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代码
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

	//商户订单号

	$out_trade_no = $_GET['out_trade_no'];

	//支付宝交易号

	$trade_no = $_GET['trade_no'];

	//买家支付宝邮箱账号
	$email = $_GET['buyer_email'];

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

	//需要查询的数据表名
	$prefix = $wpdb->prefix;
	$table = $prefix.'tin_orders';

	if($_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
		//更新交易状态
		global $wpdb;
		$row=$wpdb->get_row("select * from ".$table." where order_id=".$out_trade_no);
		$product_id = $row->product_id; 
		if($row){
			if($row->order_status<=1){$wpdb->query( "UPDATE $table SET order_status=2, trade_no='$trade_no', user_alipay='$buyer_alipay' WHERE order_id='$out_trade_no'" );
				if(!empty($row->user_email)){$email = $row->user_email;}
				store_email_template($out_trade_no,'',$email);}
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
			$row_new=$wpdb->get_row("select * from ".$table." where order_id=".$out_trade_no);
			if($row_new){
				if($row_new->order_status<=2){$wpdb->query( "UPDATE $table SET order_status=3 WHERE order_id='$out_trade_no'" );store_email_template($out_trade_no,'',$email);}
			}
		}


    }elseif($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
		global $wpdb;
		$row=$wpdb->get_row("select * from ".$table." where order_id=".$out_trade_no);
		$product_id = $row->product_id;
		if($row){
			if($row->order_status<=3){
				$success_time = $_POST['notify_time'];
				$wpdb->query( "UPDATE $table SET order_status=4, trade_no='$trade_no', order_success_time='$success_time', user_alipay='$buyer_alipay' WHERE order_id='$out_trade_no'" );
				update_success_order_product($row->product_id,$row->order_quantity);
				if(!empty($row->user_email)){$email = $row->user_email;}
				//发送订单状态变更email
				store_email_template($out_trade_no,'',$email);
				//发送购买可见内容或下载链接或会员状态变更
				send_goods_by_order($out_trade_no,'',$email);
				
			}		
		}
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
    }
    else {
      echo "<br /><br />";
    }
		
	echo "<br /><br />";

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    //如要调试，请看alipay_notify.php页面的verifyReturn函数
    wp_die('错误的请求！如果您已经完成付款，请联系管理员!');
    exit;
}
?>
<?php global $wpdb;$row=$wpdb->get_row("select * from ".$table." where order_id=".$_GET['out_trade_no']);$product_id = $row->product_id; $product_url = ($product_id>0)?get_permalink($product_id):get_bloginfo('url'); ?>
        <title>支付宝支付结果</title>
		<style type="text/css">
            .font_title{
                font-family:"Microsoft Yahei",微软雅黑;
                font-size:16px;
                color:#000;
                font-weight:bold;
            }
            .font_content{
                font-family:"Microsoft Yahei",微软雅黑;
                font-size:13px;
                color:#888;
                font-weight:normal;
            }
            table{
                border: 0 solid #CCCCCC;
            }
        </style>
	</head>
    <body>
		<table align="center" width="350" cellpadding="5" cellspacing="0">
            <tr>
                <td align="center" class="font_title" colspan="2">恭喜，支付成功!</td>
            </tr>
            <tr>
                <td class="font_content" align="left">支付金额:<?php echo $_GET['total_fee'].' 元'; ?>服务器正在自动提交发货状态，选择担保交易的请主动至支付宝交易记录中确认收货。</td>
            </tr>
			<tr>
                <td class="font_content" align="center"><a href="<?php echo $product_url; ?>" title="返回商品主页"><button style="cursor:pointer;">返回商品主页</button></a></td>
            </tr>
        </table>
    </body>
</html>