<?php $this->layout('base', ['blogName' => get_bloginfo('name'), 'logo' => tt_get_option('tt_logo'), 'home' => home_url(), 'shopHome' => tt_url_for('shop_archive')]) ?>

<h3><?=$this->e($postAuthor)?>, 你好!</h3>
<p>你的文章<a href="<?=$this->e($postLink)?>" target="_blank"><?=$this->e($postTitle)?></a>已经发表，快去看看吧！</p>