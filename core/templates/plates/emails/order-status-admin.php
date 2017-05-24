<?php $this->layout('base', ['blogName' => $blogName, 'logo' => tt_get_option('tt_logo'), 'home' => home_url(), 'shopHome' => tt_url_for('shop_archive')]) ?>

<p>你的站点有新交易订单,以下是订单信息:</p>
<div style="background-color:#fefcc9; padding:10px 15px; border:1px solid #f7dfa4; font-size: 12px;line-height:160%;">
    买家名：<a href="<?=$this->e($buyerUC)?>" title="用户个人中心" target="_blank"><?=$this->e($buyerName)?></a>
    <br>商品名：<?=$this->e($productName)?>
    <br>订单号：<a href="<?=$this->e($orderUrl)?>"><?=$this->e($orderId)?></a>
    <br>总金额：<?=$this->e($orderTotalPrice)?>
    <br>下单时间：<?=$this->e($orderTime)?>
    <br>交易状态：<strong><?=$this->e($orderStatusText)?></strong>
</div>