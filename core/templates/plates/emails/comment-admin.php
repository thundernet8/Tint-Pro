<?php $this->layout('base', ['blogName' => get_bloginfo('name'), 'logo' => tt_get_option('tt_logo'), 'home' => home_url(), 'shopHome' => tt_url_for('shop_archive')]) ?>

<style>
    img{max-width:100%;}
</style>
<p><?=$this->e($commentAuthor)?>回复了文章<a href="<?=$this->e($commentLink)?>" target="_blank"><?=$this->e($postTitle)?></a>, 快去看看吧：<br><?=$this->e($commentContent)?></p>