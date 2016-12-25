<?php $this->layout('base', ['blogName' => get_bloginfo('name'), 'logo' => tt_get_option('tt_logo'), 'home' => home_url(), 'shopHome' => tt_url_for('shop_archive')]) ?>

<style>
    img{max-width:100%;}
</style>
<p><?=$this->e($commentAuthor)?>在文章<a href="<?=$this->e($commentLink)?>" target="_blank"><?=$this->e($postTitle)?></a>中发表了回复，快去看看吧：<br></p>
<p style="padding:10px 15px;background-color:#f4f4f4;margin-top:10px;color:#000;border-radius:3px;"><?=$this->e($commentContent)?></p>