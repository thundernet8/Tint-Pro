<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/12 14:42
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint
 */
?>
<?php

/**
 * 捕获链接中的推广者
 *
 * @since 2.0.0
 * @return void
 */
function tt_retrieve_referral_keyword() {
    if(isset($_REQUEST['ref'])) {
        $ref = absint($_REQUEST['ref']);
        do_action('tt_ref', $ref);
    }
}
//add_action('template_redirect', 'tt_retrieve_referral_keyword');


function tt_handle_ref($ref) {
    //TODO
}
//add_action('tt_ref', 'tt_handle_ref', 10, 1);