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
defined('APSV_DEBUG') || define('APSV_DEBUG', false);

// Debug //
function tt_debug_log($text){
    if(!APSV_DEBUG) return;
    $file = '/home/apsv.log';
    file_put_contents($file, $text . PHP_EOL, FILE_APPEND);
}
tt_debug_log('ping....');

//////////////////////////////////////////////////////////////////

// TODO
// tt_get_option('tt_pay_channel', 'alipay')=='apsv' ?
//[orderData.time.toString(), orderData.tradeNo.toString(), orderData.status.toString(), this.secret.toString()].join('|');

$event = isset($_GET['event']) ? trim($_GET['event']) : 'new_order';

if($event != 'new_order') {
    echo 'fail(invalid event)';
    tt_debug_log('fail(invalid event)');
    exit();
}

if(!isset($_GET['appId']) || !isset($_GET['appKey'])){
    //echo 'fail';
    tt_debug_log('fail(miss appId or appKey)');
    wp_die(__('You are acting an illegal visit', 'tt'), __('Illegal Visit', 'tt'), 404); // 防止直接GET访问
    exit();
}

$appid = htmlspecialchars(trim($_GET['appId']));
$appkey = htmlspecialchars(trim($_GET['appKey']));

// TODO verify appid appkey
//if(!tt_verify_apsv_app($appid, $appkey)) {
//    echo 'fail';
//}

if(!isset($_POST['sig'])){
    //echo 'fail';
    tt_debug_log('no sig');
    wp_die(__('You are acting an illegal visit', 'tt'), __('Illegal Visit', 'tt'), 404); // 防止直接GET访问
    exit();
}

$secret = tt_get_option('tt_apsv_secret');
$order_data = array(
    'time' => isset($_POST['time']) ? $_POST['time'] : '',
    'tradeNo' => isset($_POST['tradeNo']) ? $_POST['tradeNo'] : '',
    'status' => isset($_POST['status']) ? $_POST['status'] : '',
    'secret' => $secret
);

if(md5(implode('|', $order_data)) != trim($_POST['sig'])){
    echo 'fail(wrong-token)';
    tt_debug_log('fail(wrong-token)');
    exit();
}

// 所有验证通过, 开始业务逻辑

// 订单的序号可能保存在备注字段, 即$_POST['memo']
if(!isset($_POST['memo']) || intval(trim($_POST['memo'])) < 1){
    echo 'fail(no-remark)'; // 可以返回成功, 是因为缺少正确的用户备注, 无法被处理, 也不用浪费机会再次请求处理了
    tt_debug_log('fail(no-remark)');
    exit();
}
$order_seq = intval(trim($_POST['memo']));

$order = tt_get_order_by_sequence($order_seq);

if(!$order) {
    echo 'fail(no-order)';
    tt_debug_log('fail(no-order');
    exit();
}

// 验证金额
$amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0.0;
if($order->order_total_price - $amount > 0.1){ // 少了1毛钱就不干 // 0.01?
    echo 'fail(insufficient-pay)'; //TODO email notify 未足额支付
    tt_debug_log('fail(insufficient-pay) - PAY:' . $amount . ' NEED:' . $order->order_total_price);
    exit();
}

$order_status = isset($_POST['status']) ? trim($_POST['status']) : '';

if($order_status == '交易成功'){ // 转账支付只会有`交易成功`这个状态
    $success_time = isset($_POST['time']) ? date('Y-m-d H:i:s', strtotime(str_replace('.', '-', $_POST['time']))) : current_time('mysql');
    $trade_no = isset($_POST['tradeNo']) ? trim($_POST['tradeNo']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    if($order->order_status <= 3){
        tt_update_order($order->order_id, array(
            'order_status' => 4,
            'order_success_time' => $success_time,
            'trade_no' => $trade_no,
            'user_alipay' => $username
        ), array('%d', '%s', '%s', '%s'));
    }
    tt_debug_log('success');
    echo "success";		//请不要修改或删除
    exit();
}else{
    echo 'fail(wrong-order-status)';
    tt_debug_log('fail(wrong-order-status');
    exit();
}