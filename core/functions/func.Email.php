<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/09/05 20:46
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>

<?php

/**
 * 发送邮件
 *
 * @since 2.0.0
 *
 * @param string    $from   发件人
 * @param string    $to     收件人
 * @param string    $title  主题
 * @param string|array    $content    内容
 * @param string    $template   模板，例如评论回复邮件模板、新用户、找回密码、订阅信等模板
 * @return  void
 */
function tt_mail($from, $to, $title = '', $content, $template = 'comment') {
    $title = $title ? trim($title) : tt_get_mail_title($template);
    $content = tt_mail_render($content, $template);
    $blog_name = get_bloginfo('name');
    $sender_name = tt_get_option('tt_mail_custom_sender') || tt_get_option('tt_smtp_name', $blog_name);
    if(empty($from)){
        $from = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])); //TODO: case e.g subdomain.domain.com
    }

    $fr = "From: \"" . $sender_name . "\" <$from>";
    $headers = "$fr\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
    wp_mail( $to, $title, $content, $headers );
}


/**
 * 邮件内容的模板选择处理
 *
 * @since   2.0.0
 *
 * @param   string  $content    未处理的邮件内容或者内容必要参数数组
 * @param   string  $template   渲染模板选择(reset_pass|..)
 * @return  string
 */
function tt_mail_render($content, $template = 'comment') {
    if(is_array($content)){

    }
    // TODO
    return 'Email content rendered';
}


/**
 * 不同模板的邮件标题
 *
 * @since   2.0.0
 *
 * @param   string  $template   邮件模板
 * @return  string
 */
function tt_get_mail_title($template = 'comment') {

    // TODO
    return 'Email Title';
}