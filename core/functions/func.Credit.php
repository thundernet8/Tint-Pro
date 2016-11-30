<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/30 22:07
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php

function tt_credit_pay($amount = 0, $rest = false) {
    //TODO
    $user_id = get_current_user_id();
    if(!$user_id) {
        return $rest ? new WP_Error('unknown_user', __('You must sign in before payment', 'tt')) : false;
    }

    $credits = (int)get_user_meta($user_id, 'tt_credits', true);
    if($credits < $amount) {
        return $rest ? new WP_Error('insufficient_credits', __('You do not have enough credits to accomplish this payment', 'tt')) : false;
    }

    $new_credits = $credits - $amount;
    update_user_meta($user_id, 'tt_credits', $new_credits);
    $consumed = (int)get_user_meta($user_id, 'tt_consumed_credits', true);
    update_user_meta($user_id, 'tt_consumed_credits', $consumed + $amount);
    return true;
}
