<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/24 23:01
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<footer class="footer">
    <!--div class="footer-before"><img src="<?php echo THEME_ASSET . '/img/colorful-line.png'; ?>" ></div-->
    <div class="footer-wrap">
        <!-- 页脚小工具区 -->
        <div class="footer-widgets">

        </div>
        <!-- 页脚菜单/版权信息 IDC No. -->
        <div class="footer-nav">
            <div class="footer-nav-links">
                <?php wp_nav_menu( array( 'theme_location' => 'footer-menu', 'container' => '', 'menu_class' => 'footer-menu', 'depth' => '1', 'fallback_cb' => 'header-menu'  ) ); ?>
            </div>
            <div class="footer-shares">
                <?php if($facebook = tt_get_option('tt_site_facebook')) { ?>
                <a class="fts-facebook" href="<?php echo 'https://www.facebook.com/' . $facebook; ?>" target="_blank">
                    <span class="tico tico-facebook">
                      <span class="se-icon tico tico-facebook"></span>
                    </span>
                </a>
                <?php } ?>
                <?php if($twitter = tt_get_option('tt_site_twitter')) { ?>
                    <a class="fts-twitter" href="<?php echo 'https://www.twitter.com/' . $twitter; ?>" target="_blank">
                    <span class="tico tico-twitter">
                      <span class="se-icon tico tico-twitter"></span>
                    </span>
                    </a>
                <?php } ?>
                <?php if($qq = tt_get_option('tt_site_qq')) { ?>
                    <a class="fts-qq" href="<?php echo 'http://wpa.qq.com/msgrd?v=3&uin=' . $qq . '&site=qq&menu=yes'; ?>" target="_blank">
                    <span class="tico tico-qq">
                      <span class="se-icon tico tico-qq"></span>
                    </span>
                    </a>
                <?php } ?>
                <?php if($qq_group = tt_get_option('tt_site_qq_group')) { ?>
                    <a class="fts-qq" href="<?php echo 'http://shang.qq.com/wpa/qunwpa?idkey=' . $qq_group; ?>" target="_blank">
                    <span class="tico tico-users2">
                      <span class="se-icon tico tico-users2"></span>
                    </span>
                    </a>
                <?php } ?>
                <?php if($weibo = tt_get_option('tt_site_weibo')) { ?>
                    <a class="fts-twitter" href="<?php echo 'http://www.weibo.com/' . $weibo; ?>" target="_blank">
                    <span class="tico tico-weibo">
                      <span class="se-icon tico tico-weibo"></span>
                    </span>
                    </a>
                <?php } ?>
                <?php if($weixin = tt_get_option('tt_site_weixin')) { ?>
                    <a class="fts-weixin" href="javascript:void(0)" rel="weixin-qr" target="_blank">
                    <span class="tico tico-weixin">
                      <span class="se-icon tico tico-weixin"></span>
                    </span>
                    </a>
                <?php } ?>
                <?php if($qq_mailme = tt_get_option('tt_mailme_id')) { ?>
                    <a class="fts-email" href="<?php echo 'http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&email=' . $qq_mailme; ?>" target="_blank">
                    <span class="tico tico-envelope">
                      <span class="se-icon tico tico-envelope"></span>
                    </span>
                    </a>
                <?php } ?>
                <a class="fts-rss" href="<?php bloginfo('rss2_url'); ?>" target="_blank">
                    <span class="tico tico-rss">
                      <span class="se-icon tico tico-rss"></span>
                    </span>
                </a>

            </div>
            <div class="footer-copy">
                &copy;&nbsp;<?php echo tt_copyright_year(); ?><?php echo ' ' . get_bloginfo('name') . ' All Right Reserved '; ?>
                <?php if($beian = tt_get_option('tt_beian')){
                    echo '·&nbsp;<a href="http://www.miitbeian.gov.cn/" rel="link" target="_blank">' . $beian . '</a>';
                } ?>
                <?php echo '·&nbsp;<b style="color: #ff4425;">♥</b>&nbsp;<a href="' . TT_SITE . '/tint.html" title="Tint" rel="link" target="_blank">Tint</a> & Design by <a href="' . TT_SITE . '" title="WebApproach" rel="link" target="_blank">WebApproach.</a>'; ?>
                <?php if(tt_get_option('tt_show_queries_num', false)) printf(__(' ·&nbsp;%1$s queries in %2$ss', 'tt'), get_num_queries(), timer_stop(0)); ?>
            </div>
        </div>
    </div>
</footer>
<?php load_mod('mod.FixedControls'); ?>
<?php load_mod('mod.ModalSearch'); ?>
<?php if(is_author() && current_user_can('edit_users'))load_mod('mod.ModalBanBox'); ?>
<?php if(is_home() || is_single() || is_author()){
    load_mod('mod.ModalPmBox');
    do_action('tt_ref'); // 推广检查的钩子
} ?>
<?php if(!is_user_logged_in()) load_mod('mod.ModalLoginForm'); ?>
<!-- 页脚自定义代码 -->
<?php if(tt_get_option('tt_foot_code')) { echo tt_get_option('tt_foot_code'); } ?>
<?php wp_footer(); ?>
<!--<?php echo get_num_queries();?> queries in <?php timer_stop(1); ?> seconds.-->
</body>
</html>