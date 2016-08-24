<?php
/**
 * Copyright 2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 *
 * @author Zhiyan
 * @date 2016/08/24 20:49
 * @license GPL v3 LICENSE
 */
?>

<?php

/**
 * 根据用户设置选择邮件发送方式
 *
 * @since   2.0.0
 *
 * @param   object  $phpmailer  PHPMailer对象
 * @return  void
 */
function tt_switch_mailer($phpmailer){
    $mailer = tt_get_option('tt_default_mailer');
    if($mailer === 'smtp'){
        $phpmailer->isSMTP();
        $phpmailer->Host = tt_get_option('tt_smtp_host');
        $phpmailer->SMTPAuth = true; // Force it to use Username and Password to authenticate
        $phpmailer->Port = tt_get_option('tt_smtp_port');
        $phpmailer->Username = tt_get_option('tt_smtp_username');
        $phpmailer->Password = tt_get_option('tt_smtp_password');

        // Additional settings…
        $phpmailer->SMTPSecure = tt_get_option('tt_smtp_secure');
        $phpmailer->FromName = tt_get_option('tt_smtp_name');
        //$phpmailer->From = "you@yourdomail.com"; // 多数SMTP提供商要求发信人与SMTP服务器匹配，自定义发件人地址无效
    }else{
        // when use php mail
        $phpmailer->FromName = tt_get_option('tt_mail_custom_sender');
        $phpmailer->From = tt_get_option('tt_mail_custom_address');
    }
}
add_action('phpmailer_init', 'tt_switch_mailer');
