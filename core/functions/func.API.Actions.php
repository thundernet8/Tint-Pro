<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/22 22:48
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php

/**
 * 执行向API发送的action
 *
 * @since 2.0.0
 * @param $action
 * @return WP_Error|WP_REST_Response
 */
function tt_exec_api_actions($action) {
    switch ($action){
        case 'daily_sign':
            $result = tt_daily_sign();
            if($result instanceof WP_Error){
                return $result;
            }
            if($result){
                return tt_api_success(sprintf(__('Daily sign successfully and gain %d credits', 'tt'), (int)tt_get_option('tt_daily_sign_credits', 10)));
            }
            break;
        case 'credits_charge':
            $charge_order = tt_create_credit_charge_order(get_current_user_id(), intval($_POST['amount']));
            if(!$charge_order) {
                return tt_api_fail(__('Create credits charge order failed', 'tt'));
            }elseif(is_array($charge_order) && isset($charge_order['order_id'])){
                $pay_method = tt_get_cash_pay_method();
                switch ($pay_method){
                    case 'alipay':
                        return tt_api_success('', array('data' => array( // 返回payment gateway url
                            'orderId' => $charge_order['order_id'],
                            'url' => tt_get_alipay_gateway($charge_order['order_id'])
                        )));
                    default: //qrcode
                        return tt_api_success('', array('data' => array( // 直接返回扫码支付url,后面手动修改订单
                            'orderId' => $charge_order['order_id'],
                            'url' => tt_get_qrpay_gateway($charge_order['order_id'])
                        )));
                }
            }
            break;
    }
    return null;
}
