<?php $this->layout('base', ['blogName' => $blogName, 'logo' => tt_get_option('tt_logo'), 'home' => home_url(), 'shopHome' => tt_url_for('shop_archive')]) ?>

<p>您已成功充值了<?=$this->e($creditsNum)?>积分，当前积分为：<?=$this->e($currentCredits)?>，如有任何疑问，请及时联系我们（Email:<a href="mailto:<?=$this->e($adminEmail)?>" target="_blank"><?=$this->e($adminEmail)?></a>）。</p>