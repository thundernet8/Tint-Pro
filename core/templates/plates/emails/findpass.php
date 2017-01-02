<?php $this->layout('base', ['blogName' => get_bloginfo('name'), 'logo' => tt_get_option('tt_logo'), 'home' => home_url(), 'shopHome' => tt_url_for('shop_archive')]) ?>

<p>有人要求重设如下帐号的密码:</p>
<br>
<p>网站: <?=$this->e($home)?></p>
<p>用户名: <?=$this->e($userLogin)?></p>
<p>若这不是您本人要求的，请忽略本邮件，一切如常</p>
<p>要重置您的密码，请打开下面的链接:<br><a href="<?=$this->e($resetPassLink)?>" style="word-break: break-all;"><?=$this->e($resetPassLink)?></a></p>