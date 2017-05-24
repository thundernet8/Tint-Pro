<?php
/**
 * Copyright (c) 2014-2017, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2017/03/04 22:44
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php if(tt_get_option('tt_enable_dl_top_banner', false)) { ?>
    <section class="ttgg row" id="ttgg-10">
        <div class="tg-inner col-md-6">
            <?php echo tt_get_option('tt_dl_top_banner_1'); ?>
        </div>
        <div class="tg-inner col-md-6">
            <?php echo tt_get_option('tt_dl_top_banner_2'); ?>
        </div>
    </section>
<?php } ?>