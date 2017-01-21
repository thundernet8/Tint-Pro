<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/07 23:52
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * Class Product
 *
 * 定义order的product_id enum
 */
final class Product {
    const MONTHLY_VIP = -1;

    const MONTHLY_VIP_NAME = '月费会员';

    const ANNUAL_VIP = -2;

    const ANNUAL_VIP_NAME = '年费会员';

    const PERMANENT_VIP = -3;

    const PERMANENT_VIP_NAME = '永久会员';

    const CREDIT_CHARGE = -4;

    const CREDIT_CHARGE_NAME = '站内积分';
}