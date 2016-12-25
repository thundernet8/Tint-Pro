<?php $this->layout('base', ['blogName' => get_bloginfo('name'), 'logo' => tt_get_option('tt_logo'), 'home' => home_url(), 'shopHome' => tt_url_for('shop_archive')]) ?>

<p>您的注册用户名和密码信息如下:</p>
<p>用户名: <?=$this->e($loginName)?><p>
<p>登录密码: <?=$this->e($password)?><p>
<p>登录链接: <a href="<?=$this->e($loginLink)?>"><?=$this->e($loginLink)?></a><p>