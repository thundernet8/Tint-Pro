<?php $this->layout('base', ['blogName' => get_bloginfo('name'), 'logo' => tt_get_option('tt_logo'), 'home' => home_url(), 'shopHome' => tt_url_for('shop_archive')]) ?>

<p style="font-size:14px; font-family:Microsoft YaHei,微软雅黑,Arial;">您的博文<a style="font-size:14px; font-family:Microsoft YaHei,微软雅黑,Arial;" href="<?=$this->e($postLink)?>">《<?=$this->e($postTitle)?>》</a>中有新用户下载了内容，其邮件地址为:</p>
<p style="font-size:14px; font-family:Microsoft YaHei,微软雅黑,Arial;"><?=$this->e($dlUserEmail)?></p>