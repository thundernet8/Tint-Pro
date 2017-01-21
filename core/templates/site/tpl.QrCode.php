<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/07 00:26
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

if(!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
    wp_die(__('The request is not allowed', 'tt'), __('Illegal request', 'tt'));
}

if(!isset($_REQUEST['text'])) {
    wp_die(__('The text parameter is missing', 'tt'), __('Missing argument', 'tt'));
}

$text = trim($_REQUEST['text']);

load_class('class.QRcode');

QRcode::png($text);