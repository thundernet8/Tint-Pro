<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/12 23:32
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class OrderStatus
 *
 * 定义order的status enum
 */
final class OrderStatus {

    const DEFAULT_STATUS = 0;

    const WAIT_PAYMENT = 1;

    const PAYED_AND_WAIT_DELIVERY = 2;

    const DELIVERED_AND_WAIT_CONFIRM = 3;

    const TRADE_SUCCESS = 4;

    const TRADE_CLOSED = 9;
}