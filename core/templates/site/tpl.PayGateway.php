<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/30 22:42
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
    wp_die(__('The required parameters for payment are missing', 'tt'), __('Invalid Payment Parameters', 'tt'), 500);
}

if(!wp_verify_nonce(htmlspecialchars($_GET['spm']), 'pay_gateway')){
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

//获取参数
$seller_alipay_email = trim(tt_get_option('tt_alipay_email'));
if(empty($seller_alipay_email)){
    wp_die(__('The seller information verify failed, please contact the site manager', 'tt'), __('Internal Error',  'tt'), 500);
}

$product_id = $order->product_id;
$product_name = '';
$product_des = '';

if($product_id>0){
    $product_name = $order->product_name;
    $product_des = get_post_field('post_excerpt', $product_id);
}elseif($product_id==Product::MONTHLY_VIP){
    $product_name = __('VIP Membership(Monthly)', 'tt');
    $product_des = __('Subscribe VIP Membership(Monthly)', 'tt');
}elseif($product_id==Product::ANNUAL_VIP){
    $product_name = __('VIP Membership(Annual)', 'tt');
    $product_des = __('Subscribe VIP Membership(Annual)', 'tt');
}elseif($product_id==Product::PERMANENT_VIP){
    $product_name = __('VIP Membership(Permanent)', 'tt');
    $product_des = __('Subscribe VIP Membership(Permanent)', 'tt');
}elseif($product_id==Product::CREDIT_CHARGE){
    $product_name = __('Credits Charge', 'tt');
    $product_des=$order->product_name;
}else{
    // TODO more
}
$product_url = ($product_id>0) ? get_permalink($product_id) : tt_url_for('my_settings');
$order_id = $_POST['order_id'];

// 用户创建订单选择的地址信息
$addr_info = tt_get_address($order->address_id);

// 支付接口(当前只有Alipay)
$channel = isset($_GET['channel']) && in_array(trim($_GET['channel']), array('alipay')) ? trim($_GET['channel']) : 'alipay';

if($channel == 'alipay'):
    require_once(THEME_CLASS . "/shop/alipay/alipay_submit.class.php");
    $alipay_config = tt_get_alipay_config();
    $alipay_service = tt_get_option('tt_alipay_service', 'trade_create_by_buyer');
    /**************************请求参数**************************/
    //支付类型
    $payment_type = "1";
    //必填，不能修改
    //服务器异步通知页面路径
    $notify_url = tt_url_for('alipaynotify');
    //需http://格式的完整路径，不能加?id=123这类自定义参数
    //页面跳转同步通知页面路径
    $return_url = tt_url_for('alipayreturn');
    //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
    //卖家支付宝帐户
    $seller_email = $seller_alipay_email;
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
    $receive_name = $addr_info->user_name;
    //如：张三

    //收货人地址
    $receive_address = $addr_info->user_address;
    //如：XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号

    //收货人邮编
    $receive_zip = $addr_info->user_zip;
    //如：123456

    //收货人电话号码
    $receive_phone = $addr_info->user_phone;
    //如：0571-88158090

    //收货人手机号码
    $receive_mobile = $addr_info->user_phone; //user_cellphone;
    //如：13312341234

    /************************************************************/

    //构造要请求的参数数组，无需改动
    $parameter = array(
        "service" => $alipay_service,
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
    $html_text = $alipaySubmit->buildRequestForm($parameter, 'get', __('Confirm', 'tt'));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="robots" content="noindex,follow">
    <title><?php echo __('Payment Gateway', 'tt') . ' - ' . get_bloginfo('name'); ?></title>
</head>
<body>
    <p><?php _e('Redirecting to alipay...', 'tt'); ?></p>
    <div style="display:none">
        <?php echo $html_text; ?>
    </div>
</body>
</html>
<?php
    else:
        wp_die(__('The payment channel you choose is not supported, please select another one or contact with the site manager', 'tt'), __('Unsupported Payment Channel'), 500);

endif;