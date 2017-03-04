<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/12/11 12:43
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php global $origin_post; ?>
<?php
    $free_dls = trim(get_post_meta($origin_post->ID, 'tt_free_dl', true));
    $free_dls = !empty($free_dls) ? explode(',', str_replace(PHP_EOL, ',', $free_dls)) : array();
    $sale_dls = trim(get_post_meta($origin_post->ID, 'tt_sale_dl', true));
    $sale_dls = !empty($sale_dls) ? explode(',', str_replace(PHP_EOL, ',', $sale_dls)) : array();
?>
<div id="main" class="main primary col-md-8 download-box" role="main">
    <div class="download">
        <div class="dl-declaration contextual-callout callout-warning">
            <p><?php _e('本站所刊载内容均为网络上收集整理，包括但不限于代码、应用程序、影音资源、电子书籍资料等，并且以研究交流为目的，所有仅供大家参考、学习，不存在任何商业目的与商业用途。若您使用开源的软件代码，请遵守相应的开源许可规范和精神，若您需要使用非免费的软件或服务，您应当购买正版授权并合法使用。如果你下载此文件，表示您同意只将此文件用于参考、学习使用而非任何其他用途。', 'tt'); ?></p>
        </div>
        <?php load_mod(('banners/bn.Download.Top')); ?>
        <div class="dl-detail">
        <?php if(count($free_dls)) { ?>
            <h2><?php _e('Free Resources', 'tt'); ?></h2>
            <ul class="free-resources">
            <?php $seq = 0; foreach ($free_dls as $free_dl) { ?>
                <?php $free_dl = explode('|', $free_dl); ?>
                <?php if(count($free_dl) < 2) {continue;}else{ $seq++; ?>
                <li>
                    <?php echo sprintf(__('%d. %2$s <a href="%3$s" target="_blank"><i class="tico tico-cloud-download"></i>点击下载</a> (密码: %4$s)', 'tt'), $seq, $free_dl[0], $free_dl[1], isset($free_dl[2]) ? $free_dl[2] : __('None', 'tt')); ?>
                </li>
                <?php } ?>
            <?php } ?>
            </ul>
        <?php } ?>
        <?php if(count($sale_dls)) { ?>
            <h2><?php _e('Sale Resources', 'tt'); ?></h2>
            <ul class="sale-resources">
            <?php $seq = 0; foreach ($sale_dls as $sale_dl) { ?>
                <?php $sale_dl = explode('|', $sale_dl); ?>
                <?php if(count($sale_dl) < 2) {continue;}else{ $seq++; ?>
                    <li>
                        <?php if(tt_check_bought_post_resources($origin_post->ID, $seq)) { ?>
                        <?php echo sprintf(__('%d. %2$s <a href="%3$s" target="_blank"><i class="tico tico-cloud-download"></i>点击下载</a> (密码: %4$s)', 'tt'), $seq, $sale_dl[0], $sale_dl[1], isset($sale_dl[3]) ? $sale_dl[3] : __('None', 'tt')); ?>
                        <?php }else{ ?>
                        <?php echo sprintf(__('%1$s. %2$s <a class="buy-resource" href="javascript:;" data-post-id="%3$s" data-resource-seq="%1$s" target="_blank"><i class="tico tico-cart"></i>点击购买</a> (%4$s Credits)', 'tt'), $seq, $sale_dl[0], $origin_post->ID, isset($sale_dl[2]) ? $sale_dl[2] : 1); ?>
                        <?php } ?>
                    </li>
                <?php } ?>
            <?php } ?>
            </ul>
        <?php } ?>
        </div>
        <div class="tt-gg"></div>
        <div class="dl-help contextual-bg bg-info">
            <p><?php _e('如果您发现本文件已经失效不能下载，请联系站长修正！', 'tt'); ?></p>
            <p><?php _e('本站提供的资源多数为百度网盘下载，对于大文件，你需要安装百度云客户端才能下载！', 'tt'); ?></p>
            <p><?php _e('部分文件引用的官方或者非网盘类他站下载链接，你可能需要使用迅雷、BT等下载工具下载！', 'tt'); ?></p>
            <p><?php _e('本站推荐的资源均经由站长检测或者个人发布，不包含恶意软件病毒代码等，如果你发现此类问题，请向站长举报！', 'tt'); ?></p>
            <p><?php _e('本站仅提供文件的免费下载服务，如果你对代码程序软件的使用有任何疑惑，请留意相关网站论坛。对于本站个人发布的资源，站长会提供有限的帮助！', 'tt'); ?></p>
        </div>
    </div>
    <?php load_mod(('banners/bn.Download.Bottom')); ?>
</div>