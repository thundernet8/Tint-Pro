<?php $this->layout('base', ['blogName' => get_bloginfo('name'), 'logo' => tt_get_option('tt_logo'), 'home' => home_url(), 'shopHome' => tt_url_for('shop_archive')]) ?>

<p>你好！你的博客空间(<?php echo get_bloginfo('name'); ?>)有成功登录！</p>
<p>请确定是您自己的登录, 以防别人攻击! 登录信息如下: </p>
<div style="background-color:#fefcc9; padding:10px 15px; border:1px solid #f7dfa4; font-size: 12px;line-height:160%;">
    登录名: <?=$this->e($loginName)?>
    <br>登录密码: ******
    <br>登录时间: <?php echo date("Y-m-d H:i:s"); ?>
    <br>登录IP: <?=$this->e($ip)?><?php echo ' [' . tt_query_ip_addr($ip) . ']'; ?>
</div>