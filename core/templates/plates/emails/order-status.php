<?php $this->layout('base', ['blogName' => $blogName, 'logo' => tt_get_option('tt_logo'), 'home' => home_url(), 'shopHome' => tt_url_for('shop_archive')]) ?>

<p><strong>亲爱的会员<?=$this->e($buyerName)?> 您好：</strong></p>
<p style="margin-bottom:20px;">感谢您在<?=$this->e($blogName)?>(<a style="color:#1cbdc5;" target="_blank" href="<?php echo home_url(); ?>"><?=$this->e($blogName)?></a>)购物!</p>
<p>以下是您的订单最新信息，您可进入<a target="_blank" href="<?=$this->e($orderUrl)?>">订单详情</a>页面随时关注订单状态，如有任何疑问，请及时联系我们（Email:<a href="mailto:<?=$this->e($adminEmail)?>" target="_blank"><?=$this->e($adminEmail)?></a>）。</p>
<div style="background-color:#fefcc9; padding:10px 15px; border:1px solid #f7dfa4; font-size: 12px;line-height:160%;">
    商品名：<?=$this->e($productName)?>
    <br>订单号：<?=$this->e($orderId)?>
    <br>总金额：<?=$this->e($orderTotalPrice)?>
    <br>下单时间：<?=$this->e($orderTime)?>
    <br>交易状态：<strong><?=$this->e($orderStatusText)?></strong>
</div>