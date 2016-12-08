<?php $this->layout('base', ['blogName' => get_bloginfo('name'), 'logo' => tt_get_option('tt_logo'), 'home' => home_url(), 'shopHome' => tt_url_for('shop_archive')]) ?>

<p>您的站点「<?php echo get_bloginfo('name'); ?>」有新用户注册:</p>
<p>用户名: <?=$this->e($loginName)?><p>
<p>注册邮箱: <?=$this->e($email)?><p>
<p>注册时间: <?php echo date("Y-m-d H:i:s"); ?><p>
<p>注册IP: <?=$this->e($ip)?><?php echo ' [' . tt_query_ip_addr($ip) . ']'; ?><p>