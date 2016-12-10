<?php $this->layout('base', ['blogName' => get_bloginfo('name'), 'logo' => tt_get_option('tt_logo'), 'home' => home_url(), 'shopHome' => tt_url_for('shop_archive')]) ?>

<p><?=$this->e($senderName)?>给你发送了一条站内消息：<br></p>
<p style="padding:10px 15px;background-color:#f4f4f4;margin-top:10px;color:#000;border-radius:3px;"><?=$this->e($message)?></p>
<p style="margin-top:10px;">查看更多完整内容, 请点击<a href="<?=$this->e($chatLink)?>" target="_blank" style="color:#07b6e8;">查看对话</a></p>