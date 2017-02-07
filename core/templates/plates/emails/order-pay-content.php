<?php $this->layout('base', ['blogName' => $blogName, 'logo' => tt_get_option('tt_logo'), 'home' => home_url(), 'shopHome' => tt_url_for('shop_archive')]) ?>

<p>你在<?=$this->e($blogName)?>商城付费购买了以下内容, 共支付了<?=$this->e($totalPrice)?>:</p>
<div style="background-color:#fefcc9; padding:10px 15px; border:1px solid #f7dfa4; font-size: 12px;line-height:160%;"><?php echo $payContent; ?></div>
<p>感谢你的支持，祝生活愉快！</p>