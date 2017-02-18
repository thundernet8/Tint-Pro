<?php
/**
 * Copyright (c) 2014-2017, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/02/18 15:45
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php if(!tt_get_option('tt_enable_homepage_bulletins', true)) return; ?>
<?php $now_stamp = time(); $close_time = isset($_COOKIE['tt_close_bulletins']) ? intval($_COOKIE['tt_close_bulletins']) : 0; if($now_stamp - $close_time < 3600*24) return; ?>
<?php $vm = HomeBulletinsVM::getInstance(); ?>
<?php if($vm->isCache && $vm->cacheTime) { ?>
    <!-- Bulletins cached <?php echo $vm->cacheTime; ?> -->
<?php } ?>
<?php $data = $vm->modelData; $count = $data->count; $bulletins = $data->bulletins; ?>
<?php if($count > 0 && $bulletins) { ?>
<section class="top-bulletins" id="topBulletins">
    <div class="container inner">
        <i class="tico tico-bullhorn2"></i>
        <div id="bulletins-scroll-zone">
            <ul>
            <?php foreach ($bulletins as $bulletin) { ?>
                <li class="bulletin">
                    <a href="<?php echo $bulletin['permalink']; ?>" target="_blank"><?php printf('<span>[%1$s]</span> %2$s', $bulletin['modified'], $bulletin['title']); ?></a>
                </li>
            <?php } ?>
            </ul>
        </div>
    </div>
    <span class="act_close" data-toggle="close" data-target="#topBulletins"><i></i><i></i></span>
</section>
<?php } ?>