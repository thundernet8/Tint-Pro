<?php $this->layout('base', ['blogName' => get_bloginfo('name'), 'logo' => tt_get_option('tt_logo'), 'home' => home_url(), 'shopHome' => tt_url_for('shop_archive')]) ?>

<p style="font-size:14px;font-family:Microsoft YaHei,微软雅黑,Arial;">您在博文<a href="<?=$this->e($postLink)?>">《<?=$this->e($postTitle)?>》</a>中找到了有用的内容, 其下载链接如下:</p>
<?php $index = 0; ?>
<?php foreach($dlRess as $dlRes){ $index++; ?>
<p style="font-size:14px; font-family:Microsoft YaHei,微软雅黑,Arial">
<?php
    $dlResArr = explode('|', $dlRes);
    $length = count($dlResArr);
    if($length == 2){ ?>
    <?php echo $index . '. '; ?><a href="<?php echo $dlResArr[1]; ?>" style="color:#1cbdc5;font-size:14px; font-family:Microsoft YaHei,微软雅黑,Arial" target="_blank"><?php echo $dlResArr[0]; ?></a>
    <?php }elseif($length > 2){ ?>
    <?php echo $index . '. '; ?><a href="<?php echo $dlResArr[1]; ?>" style="color:#1cbdc5;font-size:14px; font-family:Microsoft YaHei,微软雅黑,Arial" target="_blank"><?php echo $dlResArr[0]; ?></a>(下载密码: <?php echo $dlResArr[2]; ?>)
    <?php } ?>
?>
</p>
<?php } ?>