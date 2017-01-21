<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/11/17 21:32
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<!-- 模态二维码框 -->
<div id="siteQrcodes" class="js-qrcode qrcode-modal fadeZoomIn" role="dialog" aria-hidden="true">
    <div class="qr-wrap row">
        <div class="qrcode col-md-6 col-sm-6 col-xs-12">
            <?php if(tt_get_option('tt_site_weixin_qr')) { ?>
                <div class="wx-qr"><img src="<?php echo tt_get_option('tt_site_weixin_qr'); ?>" title="<?php _e('Scan the qrcode image and contact with me', 'tt'); ?>"></div>
            <?php } ?>
        </div>
        <div class="qrcode col-md-6 col-sm-6 col-xs-12">
            <?php if(tt_get_option('tt_site_alipay_qr')) { ?>
                <div class="ali-qr"><img src="<?php echo tt_get_option('tt_site_alipay_qr'); ?>" title="<?php _e('Scan the qrcode image and contact with me', 'tt'); ?>"></div>
            <?php } ?>
        </div>
    </div>
</div>