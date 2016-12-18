<?php $this->layout('base', ['blogName' => get_bloginfo('name'), 'logo' => tt_get_option('tt_logo'), 'home' => home_url(), 'shopHome' => tt_url_for('shop_archive')]) ?>

<p>您已成功使用积分支付并购买了如下资源，以下为详细信息，如有任何疑问，请及时联系我们（Email:<a href="mailto:<?=$this->e($adminEmail)?>" target="_blank"><?=$this->e($adminEmail)?></a>）。</p>
<div style="background-color:#fefcc9; padding:10px 15px; border:1px solid #f7dfa4; font-size: 12px;line-height:160%;">
    资源名称：<?=$this->e($resourceName)?> (下载地址: <?=$this->e($resourceLink)?> 密码: <?=$this->e($resourcePass)?>)
    <br>消费积分：<?=$this->e($spentCredits)?>
    <br>剩余积分：<?=$this->e($creditsBalance)?>
</div>